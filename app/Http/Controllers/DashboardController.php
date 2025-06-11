<?php

namespace App\Http\Controllers;

use stdClass;
use Carbon\Carbon;
use Inertia\Inertia;
use App\Models\IPD\Room;
use XelentAbrar\HospitalDonation\Models\Donation\Donor;
use App\Models\IPD\RoomBed;
use XelentAbrar\HospitalLab\Models\Lab\LabTest;
use XelentAbrar\HospitalLab\Models\Lab\Package;
use App\Models\OPD\Patient;
use App\Models\OPD\Service;
use App\Services\ZfService;
use XelentAbrar\HospitalExpense\Models\Expenses\Expense;
use App\Models\IPD\RoomType;
use App\Models\OPD\FollowUp;
use Illuminate\Http\Request;
use App\Models\HRMS\Employee;
use App\Models\IPD\Admission;
use XelentAbrar\HospitalLab\Models\Lab\WidalTest;
use XelentAbrar\HospitalDonation\Models\Donation\DonorFund;
use XelentAbrar\HospitalLab\Models\Lab\SubPackage;
use App\Models\HRMS\Department;
use XelentAbrar\HospitalInventory\Models\Inventory\Stock;
use XelentAbrar\HospitalLab\Models\Lab\PatientTest;
use App\Models\OPD\Appointment;
use XelentAbrar\HospitalLab\Models\Lab\TestCategory;
use App\Services\BackupsService;
use XelentAbrar\HospitalInventory\Models\Inventory\Product;
use XelentAbrar\HospitalLab\Models\Lab\LabTestDetail;
use Illuminate\Support\Facades\DB;
use App\Models\IPD\AdmissionDetail;
use App\Http\Controllers\Controller;
use App\Models\HRMS\DailyAttendance;
use Illuminate\Support\Facades\Auth;
use XelentAbrar\HospitalLab\Models\Lab\TestGeneralDetail;
use App\Models\OPD\AppointmentDetail;
use XelentAbrar\HospitalInventory\Models\Inventory\GoodsReceiptNote;
use XelentAbrar\HospitalInventory\Models\Inventory\MaterialIssueNote;
use XelentAbrar\HospitalInventory\Models\Inventory\InwardGatepassDetail;
use XelentAbrar\HospitalInventory\Models\Inventory\MaterialIssueNoteDetail;
use App\Models\OPD\Donation;
use App\Models\OT\Operation;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = $roles = $careoffs = $zf_fees = [];
        foreach(\Auth::user()->userRole->toArray() as $user){
            $roles[] = $user['role_id'];
        }
        // dd($roles);


        // dd($roledata);
        if(in_array('3', $roles) || in_array('2', $roles) || in_array('4', $roles) || in_array('10', $roles)){
            if(file_exists(base_path('config/donation.php'))) {
                $careoffs = Donor::with('funds')->select('id', 'name')->where('type','care_off')->orderBy('name')->get();
                $zf_fees = new ZfService();
                $zf_fees = $zf_fees->getZfFees();
            }
        }


        if(in_array('2', $roles) || in_array('3', $roles) || in_array('4', $roles) || in_array('10', $roles)){
            $doctor_appointments = Appointment::with('doctor:id,name')->select('consulting_doctor_id',\DB::raw('count(id) as counts'))->where('appointment_date', date('Y-m-d'))->groupBy('consulting_doctor_id')->get();
            $doctors = Employee::with('doctorCharges')->select('id', 'name','doctor_type')->whereHas('designation', function ($q) {
                $q->where('is_doctor', 1);
            })->orderBy('name')->get();
            $today = date('Y-m-d');
           $follow_ups = FollowUp::where('follow_up_date', $today)->pluck('appointment_id')->toArray();
            $follow_up_appointments = Appointment::with('doctor:id,name')
                ->select('consulting_doctor_id', \DB::raw('count(id) as counts'), \DB::raw('GROUP_CONCAT(id) as appointment_ids'))
                ->whereIn('id', $follow_ups)  // Match appointments with follow-up appointment_ids
                ->groupBy('consulting_doctor_id')
                ->get();
           $doctor_follow_up_counts = $follow_up_appointments->map(function ($appointment) use ($follow_ups) {
                $related_follow_ups = collect($follow_ups)->filter(function ($follow_up_id) use ($appointment) {
                    return in_array($follow_up_id, explode(',', $appointment->appointment_ids));
                });
                return [
                    'doctor_name' => $appointment->doctor->name,
                    'follow_up_count' => $related_follow_ups->count(),
                ];
            });

            $patients = Patient::select('id', 'name')->orderBy('name')->get();
            $services = Service::select('id', 'name', 'charges','is_nursing', 'is_advance')->where('type','OPD')->orderBy('name')->get();
            $servicesIpd = Service::select('id', 'name', 'charges','is_nursing', 'is_advance')->whereIn('type',['IPD','Other'])->orderBy('name')->get();

            $departments = Department::whereType('payroll')->select('id', 'name')->orderBy('name')->get();
            $room_types = RoomType::select('id', 'name')->orderBy('name')->get();
            $rooms = Room::select('id', 'room_number','room_type_id','charges')->orderBy('room_number')->get();
            $beds = RoomBed::select('id', 'bed_number','room_id')->orderBy('bed_number')->get();
            $file_no = Admission::latest()->value('file_no') + 1;


            $test_categories = TestCategory::whereStatus('active')->get();
            if(file_exists(base_path('config/lab.php'))) {
                $lab_tests = LabTest::select('id', 'name', 'price', 'test_category_id')->where('status','active')->where('lab_type','lab_test')->orderBy('name')->get();
                $other_tests = LabTest::select('id', 'name', 'price', 'test_category_id')->where('status','active')->where('lab_type','other_receipts')->orderBy('name')->get();
                $lab_test_details = LabTestDetail::select('id', 'test_report','lab_test_id')->orderBy('test_report')->get();
                $widal_tests = WidalTest::select('id', 'name', 'charges')->orderBy('name')->get();
                $packages = Package::with(['subPackages.labTests.testCategory'])->whereStatus('active')->orderBy('name')->get();
                $sub_package = SubPackage::with(['labTests.testCategory'])->whereStatus('active')->get();
            }


            $last_mr_number = Patient::max('mr_number');
            $new_mr_number = intval($last_mr_number) ? intval($last_mr_number) + 1 : 1;
            $new_mr_number = str_pad($new_mr_number, 5, '0', STR_PAD_LEFT);
            // $lastLab = PatientTest::latest('test_date')
            // ->whereYear('test_date', Carbon::now()->year)
            // ->whereMonth('test_date', Carbon::now()->month)
            // ->value('lab_no');
            // $newLab = intval($lastLab) ? intval($lastLab) + 1 : 1;
            $test_nature = 'General';
            // $prefix = $test_nature === 'General' ? 'G' : 'OR';
            if(file_exists(base_path('config/lab.php'))) {
                $lastLab = PatientTest::where('test_nature', $test_nature)
                ->max('lab_no');

                // ->whereYear('test_date', Carbon::now()->year)
                // ->whereMonth('test_date', Carbon::now()->month)

                $newLab = intval($lastLab) ? intval($lastLab) + 1 : 1;
            }
            $operation_theater = Operation::with('careoff');
            $selectColumns = [
                'patient_name',
                'patient_phone',
                'patient_cnic',
                'patient_age',
                'patient_gender',
                'patient_relation_name',
                'patient_address',
                'welfare',
            ];
            if (file_exists(base_path('config/donation.php'))) {
                $selectColumns = array_merge($selectColumns, ['donor_fee', 'careoff_id', 'zf_fee']);
            }
            $operation_theater = $operation_theater->select($selectColumns)->get();


            $data = [
                'doctor_appointments' => $doctor_appointments,
                'operation_theater' => $operation_theater,
                'follow_ups' => $doctor_follow_up_counts,
                'patients' => $patients,
                'doctors' => $doctors,
                'services' => $services,
                'servicesIpd' => $servicesIpd,
                'careoffs' => $careoffs,
                'zf_fees' => $zf_fees,
                'departments' => $departments,
                'rooms' => $rooms,
                'beds' => $beds,
                'room_types' => $room_types,
                'file_no' => $file_no,
                'test_categories' => $test_categories,
                'lab_tests' => $lab_tests ?? null,
                'other_tests' => $other_tests ?? null,
                'lab_test_details' => $lab_test_details ?? null,
                'widal_tests' => $widal_tests ?? null,
                'packages' => $packages ?? null,
                'sub_packages' => $sub_package ?? null,
                'mr_number' => $new_mr_number,
                'lab_no' => $newLab ?? null,
            ];
        }

        if(in_array('1', $roles) || in_array('6', $roles) || in_array('7', $roles)){
            $filterDate = '';
            if(isset($_GET['filterDate']) && $_GET['filterDate'] == 'all'){
                $filterDate = $_GET['filterDate'];
                $currrent_from_date = date('Y-m-d', strtotime('1990-12-01'));
                $currrent_to_date = date('Y-m-d');

                $last_from_date = date('Y-m-d', strtotime('1990-12-01'));
                $last_to_date = date('Y-m-d');
            }
            else if(isset($_GET['filterDate']) && $_GET['filterDate'] == 'monthly'){
                $filterDate = $_GET['filterDate'];
                $date = \Carbon\Carbon::createFromFormat('Y-m-d', date('Y-m-d'));
                $currrent_from_date = date('Y-m-d', strtotime($date->copy()->subDays(30)));
                $currrent_to_date = date('Y-m-d');


                $date = \Carbon\Carbon::createFromFormat('Y-m-d', date('Y-m-d'));
                $last_from_date = date('Y-m-d', strtotime($date->copy()->subDays(60)));
                $last_to_date = $currrent_from_date;
            }
            else if(isset($_GET['filterDate']) && $_GET['filterDate'] == 'weekly'){
                $filterDate = $_GET['filterDate'];
                $date = \Carbon\Carbon::createFromFormat('Y-m-d', date('Y-m-d'));
                $currrent_from_date = date('Y-m-d', strtotime($date->copy()->subDays(7)));
                $currrent_to_date = date('Y-m-d');


                $date = \Carbon\Carbon::createFromFormat('Y-m-d', date('Y-m-d'));
                $last_from_date = date('Y-m-d', strtotime($date->copy()->subDays(14)));
                $last_to_date = $currrent_from_date;
            }
            else{
                $currrent_from_date = date('Y-m-d');
                $currrent_to_date = date('Y-m-d');

                $last_from_date = \Carbon\Carbon::yesterday();
                $last_to_date = \Carbon\Carbon::yesterday();
            }

            ////////////////////////////opd
            $opd_fee_sum = AppointmentDetail::whereHas('appointment', function($q) use($currrent_from_date, $currrent_to_date){
                $q->whereBetween('appointment_date', [$currrent_from_date, date('Y-m-d', strtotime($currrent_to_date))]);
            })->sum('fee');
            $opd_zf_sum = [];
            if(file_exists(base_path('config/donation.php'))) {
                $opd_zf_sum = Appointment::whereBetween('appointment_date', [$currrent_from_date, date('Y-m-d', strtotime($currrent_to_date))])->selectRaw('SUM(zf_fee) as zf_fee, SUM(donor_fee) as donor_fee')->first();
            }
            $current_opd_sum = $opd_fee_sum - +($opd_zf_sum?->zf_fee ?? null) - +($opd_zf_sum?->donor_fee ?? null);
            // - +$opd_zf_sum?->donor_fee
            $startDate = Carbon::now()->startOfDay();
            $endDate = Carbon::now()->endOfDay();
            $donor = 0;
            if(file_exists(base_path('config/donation.php'))) {
                $donor = DonorFund::whereBetween('created_at', [$startDate, $endDate])
                ->sum('charges');
            }
            $donation = Donation::whereBetween('created_at', [$startDate, $endDate])
            ->sum('charges');
            $donor_sum =$donor + $donation;
            $today = \Carbon\Carbon::now('Asia/Karachi');
            $startDateYesterday = $today->copy()->subDay()->startOfDay();
            $endDateYesterday = $today->copy()->subDay()->endOfDay();
            $yesterday_donor = 0;
            if(file_exists(base_path('config/donation.php'))) {
                $yesterday_donor = DonorFund::whereBetween('created_at', [$startDateYesterday, $endDateYesterday])->sum('charges');
            }
            $yesterday_donation = Donation::whereBetween('created_at', [$startDateYesterday, $endDateYesterday])->sum('charges');
            $yesterday_donor_sum = +$yesterday_donor + $yesterday_donation;

            $opd_fee_sum = AppointmentDetail::whereHas('appointment', function($q) use($last_from_date, $last_to_date){
                $q->whereBetween('appointment_date', [$last_from_date, date('Y-m-d', strtotime($last_to_date))]);
            })->sum('fee');
            $opd_zf_sum = [];
            if(file_exists(base_path('config/donation.php'))) {
                $opd_zf_sum = Appointment::whereBetween('appointment_date', [$last_from_date, date('Y-m-d', strtotime($last_to_date))])->selectRaw('SUM(zf_fee) as zf_fee, SUM(donor_fee) as donor_fee')->first();
            }
            $yesterday_opd_sum = $opd_fee_sum - +($opd_zf_sum?->zf_fee ?? null) - +($opd_zf_sum?->donor_fee ?? null);
            //


            $current_opd_cout = Appointment::whereBetween('appointment_date', [$currrent_from_date, date('Y-m-d', strtotime($currrent_to_date))])->count();
            $yesterday_opd_cout = Appointment::whereBetween('appointment_date', [$last_from_date, date('Y-m-d', strtotime($last_to_date))])->count();



            //////////////////////////////////ipd
        //     $current_ipd_cout = Admission::whereBetween('admission_date', [$currrent_from_date, date('Y-m-d', strtotime($currrent_to_date))])->where('status','Pending')->count();
        //     $yesterday_ipd_cout = Admission::whereBetween('admission_date', [$last_from_date, date('Y-m-d', strtotime($last_to_date))])->count();

        //     // $ipd_fee_sum = Admission::whereBetween('admission_date', [$currrent_from_date, date('Y-m-d', strtotime($currrent_to_date))])->selectRaw('SUM(advance_amount) as advance_amount, SUM(received_amount) as received_amount')->first();
        //     // $ipd_refund_sum = Admission::whereBetween('discharge_date', [$currrent_from_date, date('Y-m-d', strtotime($currrent_to_date))])->selectRaw('SUM(refund_amount) as refund_amount, SUM(received_amount) as received_amount')->first();
        //     // $current_ipd_sum = $ipd_fee_sum?->advance_amount + +$ipd_fee_sum?->received_amount -  $ipd_refund_sum?->refund_amount ;
        //     $ipd_fee_sum = Admission::whereBetween('admission_date', [$currrent_from_date, date('Y-m-d', strtotime($currrent_to_date))])
        //     ->selectRaw('SUM(advance_amount) as advance_amount, SUM(received_amount) as received_amount')
        //     ->first();

        // $ipd_refund_sum = Admission::whereBetween('discharge_date', [$currrent_from_date, date('Y-m-d', strtotime($currrent_to_date))])
        //     ->selectRaw('SUM(refund_amount) as refund_amount, SUM(received_amount) as received_amount')
        //     ->first();

        //     $ipd_zf_sum = Admission::where(function($query) use ($currrent_from_date, $currrent_to_date) {
        //         $query->where('admission_date', '>=', $currrent_from_date)
        //               ->where('admission_date', '<=', $currrent_to_date)
        //               ->orWhere('discharge_date', '>=', $currrent_from_date)
        //               ->where('discharge_date', '<=', $currrent_to_date);
        //     })
        //     ->selectRaw('SUM(zf_fee) as zf_fee, SUM(donor_fee) as donor_fee')
        //     ->first();
        //          $current_ipd_sum =
        //                 ($ipd_fee_sum->advance_amount ?? 0) +
        //                 ($ipd_refund_sum->received_amount ?? 0) -
        //                 ($ipd_refund_sum->refund_amount ?? 0) - +$ipd_zf_sum?->zf_fee - +$ipd_zf_sum?->donor_fee;


        //             $ipd_fee_sum = Admission::whereBetween('admission_date', [$last_from_date, date('Y-m-d', strtotime($last_to_date))])
        //                 ->selectRaw('SUM(advance_amount) as advance_amount, SUM(received_amount) as received_amount')
        //                 ->first();

        //             $ipd_refund_sum = Admission::whereBetween('discharge_date', [$last_from_date, date('Y-m-d', strtotime($last_to_date))])
        //     ->selectRaw('SUM(refund_amount) as refund_amount, SUM(received_amount) as received_amount')
        //                 ->first();
        //     $ipd_zf_sum = Admission::where(function($query) use ($last_from_date, $last_to_date) {
        //         $query->where('admission_date', '>=', $last_from_date)
        //               ->where('admission_date', '<=', $last_to_date)
        //               ->orWhere('discharge_date', '>=', $last_from_date)
        //               ->where('discharge_date', '<=', $last_to_date);
        //     })->selectRaw('SUM(zf_fee) as zf_fee, SUM(donor_fee) as donor_fee')
        //     ->first();
        //             $yesterday_ipd_sum =
        //                 ($ipd_fee_sum->advance_amount ?? 0) +
        //                 ($ipd_refund_sum->received_amount ?? 0) -
        //                 ($ipd_refund_sum->refund_amount ?? 0)- +$ipd_zf_sum?->zf_fee - +$ipd_zf_sum?->donor_fee;

        //     // $ipd_fee_sum = Admission::whereBetween('admission_date', [$last_from_date, date('Y-m-d', strtotime($last_to_date))])->selectRaw('SUM(advance_amount) as advance_amount, SUM(received_amount) as received_amount')->first();
        //     // $yesterday_ipd_sum = $ipd_fee_sum?->advance_amount + +$ipd_fee_sum?->received_amount;



        //     ///////////////////////////////////lab
        //     $current_lab_cout = PatientTest::whereBetween('test_date', [$currrent_from_date, date('Y-m-d', strtotime($currrent_to_date))])->count();
        //     $yesterday_lab_cout = PatientTest::whereBetween('test_date', [$last_from_date, date('Y-m-d', strtotime($last_to_date))])->count();

        //     $lad_tests = PatientTest::whereBetween('test_date', [$currrent_from_date, date('Y-m-d', strtotime($currrent_to_date))])->get();
        //     $current_lab_sum = 0;
        //     foreach($lad_tests as $lad_test){
        //         $discount = 0;
        //         if($lad_test->discount_type == 'Percentage'){
        //             $discount = ($lad_test->total_amount / 100) * $lad_test->discount_value;
        //         }
        //         else if($lad_test->discount_type == 'Fixed'){
        //             $discount = $lad_test->discount_value;
        //         }

        //         $current_lab_sum = +$current_lab_sum + +$lad_test->total_amount - +$lad_test->zf_fee - +$lad_test->donor_fee - +$discount;
        //     }

        //     $lad_tests = PatientTest::whereBetween('test_date', [$last_from_date, date('Y-m-d', strtotime($last_to_date))])->get();
        //     $yesterday_lab_sum = 0;
        //     foreach($lad_tests as $lad_test){
        //         $discount = 0;
        //         if($lad_test->discount_type == 'Percentage'){
        //             $discount = ($lad_test->total_amount / 100) * $lad_test->discount_value;
        //         }
        //         else if($lad_test->discount_type == 'Fixed'){
        //             $discount = $lad_test->discount_value;
        //         }

        //         $yesterday_lab_sum = +$yesterday_lab_sum + +$lad_test->total_amount - +$lad_test->zf_fee - +$lad_test->donor_fee - +$discount;
        //     }
        $current_ipd_cout = Admission::whereBetween('admission_date', [$currrent_from_date, date('Y-m-d', strtotime($currrent_to_date))])->where('status','Pending')->count();
        $yesterday_ipd_cout = Admission::whereBetween('admission_date', [$last_from_date, date('Y-m-d', strtotime($last_to_date))])->count();

        // $ipd_fee_sum = Admission::whereBetween('admission_date', [$currrent_from_date, date('Y-m-d', strtotime($currrent_to_date))])->selectRaw('SUM(advance_amount) as advance_amount, SUM(received_amount) as received_amount')->first();
        // $ipd_refund_sum = Admission::whereBetween('discharge_date', [$currrent_from_date, date('Y-m-d', strtotime($currrent_to_date))])->selectRaw('SUM(refund_amount) as refund_amount, SUM(received_amount) as received_amount')->first();
        // $current_ipd_sum = $ipd_fee_sum?->advance_amount + +$ipd_fee_sum?->received_amount -  $ipd_refund_sum?->refund_amount ;
        $ipd_fee_sum = Admission::whereBetween('admission_date', [$currrent_from_date, date('Y-m-d', strtotime($currrent_to_date))])
        ->selectRaw('SUM(advance_amount) as advance_amount, SUM(received_amount) as received_amount')
        ->first();

    $ipd_refund_sum = Admission::whereBetween('discharge_date', [$currrent_from_date, date('Y-m-d', strtotime($currrent_to_date))])
        ->selectRaw('SUM(refund_amount) as refund_amount, SUM(received_amount) as received_amount')
        ->first();

            $ipd_zf_sum = Admission::where(function($query) use ($currrent_from_date, $currrent_to_date) {
                $query->where('admission_date', '>=', $currrent_from_date)
                ->where('admission_date', '<=', $currrent_to_date)
                ->orWhere('discharge_date', '>=', $currrent_from_date)
                ->where('discharge_date', '<=', $currrent_to_date);
            });
            if(file_exists(base_path('config/donation.php'))) {
                $ipd_zf_sum = $ipd_zf_sum->selectRaw('SUM(zf_fee) as zf_fee, SUM(donor_fee) as donor_fee , SUM(discount_amount) as discount_amount');
            }
            else{
                $ipd_zf_sum = $ipd_zf_sum->selectRaw('SUM(discount_amount) as discount_amount');
            }
                $ipd_zf_sum = $ipd_zf_sum->first();
             $current_ipd_sum =
                    ($ipd_fee_sum->advance_amount ?? 0) + +$ipd_zf_sum?->donor_fee + +$ipd_zf_sum?->zf_fee  + + $ipd_zf_sum?->discount_amount +
                    ($ipd_refund_sum->received_amount ?? 0) -
                    ($ipd_refund_sum->refund_amount ?? 0)- +$ipd_zf_sum?->donor_fee - +$ipd_zf_sum?->zf_fee  - + $ipd_zf_sum?->discount_amount ;

                    //
                $ipd_fee_sum = Admission::whereBetween('admission_date', [$last_from_date, date('Y-m-d', strtotime($last_to_date))])
                    ->selectRaw('SUM(advance_amount) as advance_amount, SUM(received_amount) as received_amount')
                    ->first();

                $ipd_refund_sum = Admission::whereBetween('discharge_date', [$last_from_date, date('Y-m-d', strtotime($last_to_date))])
        ->selectRaw('SUM(refund_amount) as refund_amount, SUM(received_amount) as received_amount')
                    ->first();
        $ipd_zf_sum = Admission::where(function($query) use ($last_from_date, $last_to_date) {
            $query->where('admission_date', '>=', $last_from_date)
                  ->where('admission_date', '<=', $last_to_date)
                  ->orWhere('discharge_date', '>=', $last_from_date)
                  ->where('discharge_date', '<=', $last_to_date);
        });
        if(file_exists(base_path('config/donation.php'))) {
            $ipd_zf_sum = $ipd_zf_sum->selectRaw('SUM(zf_fee) as zf_fee, SUM(donor_fee) as donor_fee , SUM(discount_amount) as discount_amount');
        }
        else{
            $ipd_zf_sum = $ipd_zf_sum->selectRaw('SUM(discount_amount) as discount_amount');
        }
        $ipd_zf_sum = $ipd_zf_sum->first();
                $yesterday_ipd_sum =
                    ($ipd_fee_sum->advance_amount ?? 0) + +$ipd_zf_sum?->donor_fee + +$ipd_zf_sum?->zf_fee  + + $ipd_zf_sum?->discount_amount +
                    ($ipd_refund_sum->received_amount ?? 0) -
                    ($ipd_refund_sum->refund_amount ?? 0)- +$ipd_zf_sum?->donor_fee - +$ipd_zf_sum?->zf_fee  - + $ipd_zf_sum?->discount_amount ;
                    //
        // $ipd_fee_sum = Admission::whereBetween('admission_date', [$last_from_date, date('Y-m-d', strtotime($last_to_date))])->selectRaw('SUM(advance_amount) as advance_amount, SUM(received_amount) as received_amount')->first();
        // $yesterday_ipd_sum = $ipd_fee_sum?->advance_amount + +$ipd_fee_sum?->received_amount;



        ///////////////////////////////////lab
        if(file_exists(base_path('config/lab.php'))) {
            $current_lab_cout = PatientTest::whereBetween('test_date', [$currrent_from_date, date('Y-m-d', strtotime($currrent_to_date))])->count();
            $yesterday_lab_cout = PatientTest::whereBetween('test_date', [$last_from_date, date('Y-m-d', strtotime($last_to_date))])->count();

            $lad_tests = PatientTest::whereBetween('test_date', [$currrent_from_date, date('Y-m-d', strtotime($currrent_to_date))])->get();

            $current_lab_sum = 0;
            foreach($lad_tests as $lad_test){
                $discount = 0;
                if($lad_test->discount_type == 'Percentage'){
                    $discount = ($lad_test->total_amount / 100) * $lad_test->discount_value;
                }
                else if($lad_test->discount_type == 'Fixed'){
                    $discount = $lad_test->discount_value;
                }

                $current_lab_sum = +$current_lab_sum + +$lad_test->total_amount - +$lad_test->donor_fee - +$lad_test->zf_fee - +$discount;
            }

            $lad_tests = PatientTest::whereBetween('test_date', [$last_from_date, date('Y-m-d', strtotime($last_to_date))])->get();
            $yesterday_lab_sum = 0;
            foreach($lad_tests as $lad_test){
                $discount = 0;
                if($lad_test->discount_type == 'Percentage'){
                    $discount = ($lad_test->total_amount / 100) * $lad_test->discount_value;
                }
                else if($lad_test->discount_type == 'Fixed'){
                    $discount = $lad_test->discount_value;
                }

                $yesterday_lab_sum = +$yesterday_lab_sum + +$lad_test->total_amount - +$lad_test->donor_fee - +$lad_test->zf_fee  - +$discount;
            }
        }

        //

            //////////////////////////////EXPENSE
            if(file_exists(base_path('config/expense.php'))) {
                $current_expense_sum = Expense::whereBetween('date', [$currrent_from_date, date('Y-m-d', strtotime($currrent_to_date))])->sum('amount');
                $yesterday_expense_sum = Expense::whereBetween('date', [$last_from_date, date('Y-m-d', strtotime($last_to_date))])->sum('amount');
            }


            $latest_opd_patient =  Appointment::with('appointmentDetails')->latest()->limit(3)->get();
            $latest_ipd_patient =  Admission::latest()->limit(3)->get();
            $male = Appointment::where('patient_gender', 'Male')->count();
            $fe_male = Appointment::where('patient_gender', 'Female')->count();
            $child = Appointment::where('patient_gender', 'Children')->count();

            $male = Admission::where('gender', 'Male')->count() + $male;
            $fe_male = Admission::where('gender', 'Female')->count() + $fe_male;
            $child = Admission::where('gender', 'Children')->count() + $child;

            $online_doctors = DailyAttendance::with('employee')->whereDate('created_at', date('Y-m-d'))
            ->whereHas('employee', function($q){
                $q->whereHas('designation', function($q1){
                    $q1->where('is_doctor','1');
                });
            })
            ->get();
            $doctorIds = $online_doctors->pluck('employee.id')->toArray();

            $appointmentCounts = Appointment::whereDate('created_at', date('Y-m-d'))
                ->whereIn('consulting_doctor_id', $doctorIds)
                ->select('consulting_doctor_id', DB::raw('COUNT(*) as appointment_count'))
                ->groupBy('consulting_doctor_id')
                ->get()
                ->keyBy('consulting_doctor_id');
            $online_doctors = $online_doctors->map(function ($attendance) use ($appointmentCounts) {
                $doctorId = $attendance->employee->id;
                $attendance->employee->appointment_count = $appointmentCounts->get($doctorId)->appointment_count ?? 0;
                return $attendance;
            });
            $online_doctors = DailyAttendance::with('employee')
            ->whereDate('created_at', date('Y-m-d'))
            ->whereHas('employee', function($q) {
                $q->whereHas('designation', function($q1) {
                    $q1->where('is_doctor', '1');
                });
            })
            ->orderByRaw('check_out IS NULL DESC')
            ->get();

            $doctorIds = $online_doctors->pluck('employee.id')->toArray();

            $appointmentCounts = Appointment::whereDate('created_at', date('Y-m-d'))
                ->whereIn('consulting_doctor_id', $doctorIds)
                ->select('consulting_doctor_id', DB::raw('COUNT(*) as appointment_count'))
                ->groupBy('consulting_doctor_id')
                ->get()
                ->keyBy('consulting_doctor_id');
                $admissionCounts = AdmissionDetail::join('admissions', 'admission_details.admission_id', '=', 'admissions.id') // Join with admissions table
                ->whereDate('admissions.admission_date', Carbon::today())
                ->whereIn('admission_details.doctor_id', $doctorIds)
                ->select('admission_details.doctor_id', DB::raw('COUNT(DISTINCT admission_details.admission_id) as admission_count'))  // Count distinct admissions
                ->groupBy('admission_details.doctor_id')
                ->get()
                ->keyBy('doctor_id');

            $online_doctors = $online_doctors->map(function ($attendance) use ($appointmentCounts, $admissionCounts) {
                $doctorId = $attendance->employee->id;
                $attendance->employee->appointment_count = $appointmentCounts->get($doctorId)->appointment_count ?? 0;
                $attendance->employee->admission_count = $admissionCounts->get($doctorId)->admission_count ?? 0;
                return $attendance;
            });
            $all_doctors = Employee::with('designation')
            ->whereHas('designation', function ($q) {
                $q->where('is_doctor', '1');
            })
            ->whereNotIn('id', $doctorIds)
            ->get();
            $all_doctors->each(function ($doctor) use (&$online_doctors) {
                $attendance = new stdClass();
                $attendance->check_in = null;
                $attendance->check_out = null;
                $attendance->employee = $doctor;
                $attendance->employee->appointment_count = "";
                $attendance->employee->admission_count = "";
                $online_doctors->push($attendance);
            });
            $online_doctors = $online_doctors->sortBy(function ($attendance) {
                if ($attendance->check_in === null && $attendance->check_out === null) {
                    return 2;
                }
                if ($attendance->check_in !== null && $attendance->check_out === null) {
                    return 0;
                }
                return 1;
            })->values();
            $filterDate = '';
            $current_from_date = null;
            $current_to_date = null;
            $last_from_date = null;
            $last_to_date = null;

            if (isset($_GET['filterDate']) && $_GET['filterDate'] === 'all') {
                $filterDate = $_GET['filterDate'];
                $current_from_date = '1990-12-01'; // Using a static date for 'all'
                $current_to_date = date('Y-m-d');

                $last_from_date = '1990-12-01';
                $last_to_date = date('Y-m-d');
            } else if (isset($_GET['filterDate']) && $_GET['filterDate'] === 'monthly') {
                $filterDate = $_GET['filterDate'];
                $date = \Carbon\Carbon::now();
                $current_from_date = $date->subDays(30)->toDateString();
                $current_to_date = date('Y-m-d');

                $last_from_date = $date->copy()->subDays(60)->toDateString();
                $last_to_date = $current_from_date;
            } else if (isset($_GET['filterDate']) && $_GET['filterDate'] === 'weekly') {
                $filterDate = $_GET['filterDate'];
                $date = \Carbon\Carbon::now();
                $current_from_date = $date->subDays(7)->toDateString();
                $current_to_date = date('Y-m-d');

                $last_from_date = $date->copy()->subDays(14)->toDateString();
                $last_to_date = $current_from_date;
            } else {
                $current_from_date = date('Y-m-d');
                $current_to_date = date('Y-m-d');

                $last_from_date = \Carbon\Carbon::yesterday()->toDateString();
                $last_to_date = \Carbon\Carbon::yesterday()->toDateString();
            }
            $current_from_date = Carbon::parse($current_from_date)->startOfDay();
            $current_to_date = Carbon::parse($current_to_date)->endOfDay();
            if(file_exists(base_path('config/inventory.php'))) {
                $materialIssueQuery = MaterialIssueNote::query();
                if ($current_from_date && $current_to_date) {
                    $materialIssueQuery->whereBetween('date', [$current_from_date, $current_to_date]);
                }
                $totalMaterialIssueCount = $materialIssueQuery->count();

                $goodReceiptQuery = GoodsReceiptNote::query();
                if ($current_from_date && $current_to_date) {
                    $goodReceiptQuery->whereBetween('created_at', [$current_from_date, $current_to_date]);
                }
                $totalGoodReceiptCount = $goodReceiptQuery->count();

                $inwardGatepassQuery = InwardGatepassDetail::with('product')
                    ->whereBetween('created_at', [$current_from_date, $current_to_date]);

                $inwardGatepassDetails = $inwardGatepassQuery->get();
                $totalInwardGatepassValue = $inwardGatepassDetails->sum(function ($detail) {
                    return $detail->qty * $detail->price * (1 + $detail->tax_rate / 100);
                });

                        $prevDay = date('Y-m-d', strtotime('-1 day'));
                        $prevDayStart = "$prevDay 00:00:00";
                        $prevDayEnd = "$prevDay 23:59:59";
                        $prevDayMaterialIssueCount = MaterialIssueNote::whereBetween('created_at', [$prevDayStart, $prevDayEnd])->count();
                        $prevDayGoodReceiptCount = GoodsReceiptNote::whereBetween('created_at', [$prevDayStart, $prevDayEnd])->count();

                        $prevDayInwardGatepassDetails = InwardGatepassDetail::with('product')
                            ->whereBetween('created_at', [$prevDayStart, $prevDayEnd])
                            ->get();
                        $prevDayInwardGatepassValue = $prevDayInwardGatepassDetails->sum(function ($detail) {
                            return $detail->qty * $detail->price * (1 + $detail->tax_rate / 100);
                        });

                        $insulinProductNames = [
                            'INSULINE 70*30',
                            'DACIATASVIR (DACLATA ) 60 MG ( H C V )',
                            'SOFOSBUVIR 400 MG (FOSBU) {H C V }',
                            'FOREEN (RESTANE ) 100 ML','PYODINE 450 ML','PYODINE SCRUB 450 ML'

                        ];

                    $insulinProducts = Stock::with('product')
                        ->whereHas('product', function ($query) use ($insulinProductNames) {
                            $query->where('status', 'active')
                                ->whereIn('name', $insulinProductNames);
                        })
                        ->get(['product_id', 'min_qty', 'current_stock']);

                    // dd($insulinProducts);


                    $formattedInsulinProducts = $insulinProducts->map(function ($stock) {
                        return [
                            'product_id' => $stock->product_id,
                            'product_name' => $stock->product->name,
                            'min_qty' => $stock->min_qty,
                            'current_stock' => $stock->current_stock,
                        ];
                    });




                $currentMonthExpiry = [];
                    $currentMonthStart = Carbon::now()->startOfMonth();
                    $currentMonthEnd = Carbon::now()->endOfMonth();

                    // return $inwardGatepassDetails;
                    $inwardGatepassDetails = InwardGatepassDetail::all();
                    foreach ($inwardGatepassDetails as $detail) {
                        $expiryDate = Carbon::parse($detail->expiry_date);
                        if ($expiryDate->between($currentMonthStart, $currentMonthEnd)) {
                            $currentMonthExpiry[] = [
                                'product_id' => $detail->product_id,
                                'product_name' => $detail->product->name,
                                'expiry_date' => $expiryDate->toDateString(),
                            ];
                        }
                    }

                    $materialIssueDetails = MaterialIssueNoteDetail::all();
                    $materialIssueCounts = [];

                    foreach ($materialIssueDetails as $issueDetail) {
                        $productId = $issueDetail->product_id;
                        if (!isset($materialIssueCounts[$productId])) {
                            $materialIssueCounts[$productId] = 0;
                        }
                        $materialIssueCounts[$productId]++;
                    }

                    // Sort and get top 5 products
                    arsort($materialIssueCounts);
                    $topProducts = array_slice(array_keys($materialIssueCounts), 0, 5);

                    $topProductsDetails = [];
                    foreach ($topProducts as $productId) {
                        $product = Product::find($productId);
                        if ($product) {
                            $topProductsDetails[] = [
                                'product_id' => $product->id,
                                'product_name' => $product->name,
                            ];
                        }
                    }
                }

            $data = [
                'inventory_dashboard' => [
                    'total_material_issue_count' => $totalMaterialIssueCount ?? null,
                    'total_good_receipt_count' => $totalGoodReceiptCount ?? null,
                    'total_inward_gatepass_value' => $totalInwardGatepassValue ?? null,
                    'prev_day_material_issue_count' => $prevDayMaterialIssueCount ?? null,
                    'prev_day_good_receipt_count' => $prevDayGoodReceiptCount ?? null,
                    'prev_day_inward_gatepass_value' => $prevDayInwardGatepassValue ?? null,
                    'insulin_products' => $formattedInsulinProducts ?? null,
                    'current_month_expiry' => $currentMonthExpiry ?? null,
                    'top_products' => $topProductsDetails ?? null,
                    'filterDate' => $filterDate ?? null,

                ],

                'admin_dashboard' =>
                    [   'current_opd_cout' => $current_opd_cout,
                        'yesterday_opd_cout' => $yesterday_opd_cout,
                        'current_opd_sum' => $current_opd_sum,
                        'donor_sum' => $donor_sum,
                        'yesterday_donor_sum' => $yesterday_donor_sum,
                        'yesterday_opd_sum' => $yesterday_opd_sum,

                        'current_ipd_cout' => $current_ipd_cout,
                        'yesterday_ipd_cout' => $yesterday_ipd_cout,
                        'current_ipd_sum' => $current_ipd_sum,
                        'yesterday_ipd_sum' => $yesterday_ipd_sum,

                        'current_lab_cout' => $current_lab_cout ?? null,
                        'yesterday_lab_cout' => $yesterday_lab_cout ?? null,
                        'current_lab_sum' => $current_lab_sum ?? null,
                        'yesterday_lab_sum' => $yesterday_lab_sum ?? null,

                        'current_expense_sum' => $current_expense_sum ?? null,
                        'yesterday_expense_sum' => $yesterday_expense_sum ?? null,

                        'latest_opd_patient' => $latest_opd_patient,
                        'latest_ipd_patient' => $latest_ipd_patient,

                        'male' => $male,
                        'fe_male' => $fe_male,
                        'child' => $child,

                        'online_doctors' => $online_doctors,


                        'filterDate' => $filterDate,
                    ]
            ];

        }

        return Inertia::render('Dashboard', $data);


    }



    public function resetSerialNumbers()
    {
        $currentYear = date('Y');
        $lastResetYear = cache('last_reset_year', $currentYear);
        if ($currentYear != $lastResetYear) {
            DB::transaction(function () {
                Appointment::query()->update(['doc_serial' => null]);
                Admission::query()->update(['ipd_serial' => null]);
                if(file_exists(base_path('config/lab.php'))) {
                    PatientTest::query()->update(['lab_serial' => null]);
                    TestGeneralDetail::query()->update(['lab_serial' => null]);
                    LabTest::query()->update(['serial_no' => null]);
                }
                Employee::query()->update(['serial_no' => null]);
            });
            cache(['last_reset_year' => $currentYear]);
        }
    }
    public function createBackup(BackupsService $backupService)
    {
        $backupResult = $backupService->takeBackupIfDue();
        // dd($backupResult);
        return response()->json([
            'backupMessage' => $backupResult ? 'Backup completed successfully!' : 'Backup completed successfully!',
        ]);
    }


    // public function PatientCount()
    // {
    //     $today = Carbon::today();
    //     $startOfWeek = Carbon::now()->startOfWeek();
    //     $startOfMonth = Carbon::now()->startOfMonth();

    //     $todayAppointments = Appointment::where('cancel', 0)->whereDate('created_at', $today)->get();
    //     $todayAdmissions = Admission::where('cancel', 0)->whereDate('created_at', $today)->get();

    //     $weekAppointments = Appointment::where('cancel', 0)->whereBetween('created_at', [$startOfWeek, Carbon::now()])->get();
    //     $weekAdmissions = Admission::where('cancel', 0)->whereBetween('created_at', [$startOfWeek, Carbon::now()])->get();

    //     $monthAppointments = Appointment::where('cancel', 0)->whereBetween('created_at', [$startOfMonth, Carbon::now()])->get();
    //     $monthAdmissions = Admission::where('cancel', 0)->whereBetween('created_at', [$startOfMonth, Carbon::now()])->get();

    //     $data = [
    //         'today' => $this->calculateData($todayAppointments, $todayAdmissions),
    //         'this_week' => $this->calculateData($weekAppointments, $weekAdmissions),
    //         'this_month' => $this->calculateData($monthAppointments, $monthAdmissions),
    //     ];

    //     return inertia('Reports/PatientReport', $data);
    // }

    // private function calculateData($appointments, $admissions)
    // {
    //     $appointmentCount = $appointments->count();
    //     $admissionCount = $admissions->count();
    //     $totalPatients = $appointmentCount + $admissionCount;
    //     $totalFees = $appointments->sum('fee');
    //     $averagePatients = $totalPatients > 0 ? $totalPatients / 2 : 0;

    //     return [
    //         'appointment_count' => $appointmentCount,
    //         'admission_count' => $admissionCount,
    //         'total_patients' => $totalPatients,
    //         'total_fees' => $totalFees,
    //         'average_patients' => $averagePatients,
    //     ];
    // }
    public function updateDoctorChecked(Request $request)
    {
        $request->validate([
            'appointments' => 'required|array',
            'appointments.*.id' => 'required|exists:appointments,id',
            'appointments.*.is_doctor_checked' => 'required|boolean',
        ]);
        foreach ($request->appointments as $appointmentData) {
            $appointment = Appointment::find($appointmentData['id']);
            $appointment->is_doctor_checked = $appointmentData['is_doctor_checked'];
            $appointment->save();
        }

        return redirect()->back()->with('success', 'Appointments updated successfully.');
    }
}
