<?php

namespace App\Http\Controllers\IPD;

use Carbon\Carbon;
use Inertia\Inertia;
use App\Models\IPD\Room;
use App\Models\UserRole;
use XelentAbrar\HospitalDonation\Models\Donation\Donor;
use App\Models\IPD\RoomBed;
use App\Models\OPD\Patient;
use App\Models\OPD\Service;
use App\Services\ZfService;
use App\Models\IPD\RoomType;
use App\Models\HRMS\Employee;
use App\Models\IPD\Admission;
use App\Models\HRMS\Department;
use Illuminate\Support\Facades\DB;
use App\Models\IPD\AdmissionDetail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\VoucherAuditService;
use App\Http\Requests\IPD\AdmissionRequest;
use XelentAbrar\HospitalAccounts\Models\Accounts\VoucherAudit;

class AdmissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkPermission:admissions.index')->only('index');
        $this->middleware('checkPermission:admissions.create')->only('create', 'store');
        $this->middleware('checkPermission:admissions.show')->only('show');
        $this->middleware('checkPermission:admissions.edit')->only('edit', 'update');
        $this->middleware('checkPermission:admissions.destroy')->only('destroy');
    }
    public function index()
    {
        $admissions = Admission::with(['patient:id,name', 'updator:id,name', 'creator:id,name', 'room:id,room_number', 'roomBed:id,bed_number'])->orderBy('created_at', 'desc');
        if (isset($_GET['search']) && $_GET['search'] != '') {
            $admissions = $admissions->where(function ($query) {
                $query = $query->where('name', 'LIKE', '%' . $_GET['search'] . '%')->orWhere('file_no', 'LIKE', '%' . $_GET['search'] . '%')->orWhere('admission_date', 'LIKE', '%' . $_GET['search'] . '%')->orWhere('admission_time', 'LIKE', '%' . $_GET['search'] . '%')->orWhere('cnic', 'LIKE', '%' . $_GET['search'] . '%')->orWhere('address', 'LIKE', '%' . $_GET['search'] . '%')->orWhere('relation_name', 'LIKE', '%' . $_GET['search'] . '%')->orWhere('phone', 'LIKE', '%' . $_GET['search'] . '%')->orWhere('slip_no', 'LIKE', '%' . $_GET['search'] . '%')->orWhere('refered_by', 'LIKE', '%' . $_GET['search'] . '%')->orWhere('chart_no', 'LIKE', '%' . $_GET['search'] . '%');
                $query = $query->orWhereHas('department', function ($q) {
                    $q = $q->where('name', 'LIKE', '%' . $_GET['search'] . '%');
                });
                $query = $query->orWhereHas('details', function ($q) {
                    $q = $q->whereHas('service', function ($q1) {
                        $q1 = $q1->where('name', 'LIKE', '%' . $_GET['search'] . '%');
                    });
                    $q = $q->orWhereHas('doctor', function ($q1) {
                        $q1 = $q1->where('name', 'LIKE', '%' . $_GET['search'] . '%');
                    });
                });
            });
        }
        $admissions = $admissions->where('status', 'Pending')->paginate(200);
        $admissions = $admissions->appends(['search' => request('search')]);
        $user = Auth::user();
        $role = UserRole::where('user_id', $user->id)->whereIn('role_id', [1, 7])->first();
        return Inertia::render('IPD/Admissions/Index', [
            'admissions' => $admissions,
            'role' => $role,
            'filters' => request()->only(['search']),
            'type' => 'admission',
        ]);
    }


    public function dischargeIndex()
    {
        $admissions = Admission::with(['patient:id,name', 'updator:id,name', 'creator:id,name', 'room:id,room_number', 'roomBed:id,bed_number'])->orderBy('created_at', 'desc');
        if (isset($_GET['search']) && $_GET['search'] != '') {
            $admissions = $admissions->where(function ($query) {
                $query = $query->where('name', 'LIKE', '%' . $_GET['search'] . '%')->orWhere('file_no', 'LIKE', '%' . $_GET['search'] . '%')->orWhere('admission_date', 'LIKE', '%' . $_GET['search'] . '%')->orWhere('admission_time', 'LIKE', '%' . $_GET['search'] . '%')->orWhere('cnic', 'LIKE', '%' . $_GET['search'] . '%')->orWhere('address', 'LIKE', '%' . $_GET['search'] . '%')->orWhere('relation_name', 'LIKE', '%' . $_GET['search'] . '%')->orWhere('phone', 'LIKE', '%' . $_GET['search'] . '%')->orWhere('slip_no', 'LIKE', '%' . $_GET['search'] . '%')->orWhere('refered_by', 'LIKE', '%' . $_GET['search'] . '%')->orWhere('chart_no', 'LIKE', '%' . $_GET['search'] . '%');
                $query = $query->orWhereHas('department', function ($q) {
                    $q = $q->where('name', 'LIKE', '%' . $_GET['search'] . '%');
                });
                $query = $query->orWhereHas('details', function ($q) {
                    $q = $q->whereHas('service', function ($q1) {
                        $q1 = $q1->where('name', 'LIKE', '%' . $_GET['search'] . '%');
                    });
                    $q = $q->orWhereHas('doctor', function ($q1) {
                        $q1 = $q1->where('name', 'LIKE', '%' . $_GET['search'] . '%');
                    });
                });
            });
        }
        $admissions = $admissions->where('status', 'Complete')->orderBy('discharge_date', 'desc')->paginate(100);
        $admissions = $admissions->appends(['search' => request('search')]);
        $user = Auth::user();
        $role = UserRole::where('user_id', $user->id)->where('role_id', 1)->first();
        return Inertia::render('IPD/Admissions/Index', [
            'admissions' => $admissions,
            'filters' => request()->only(['search']),
            'type' => 'discharge',
            'role' => $role,
        ]);
    }

    public function create()
    {
        $patients = Patient::select('id', 'name')->orderBy('name')->get();
        $services = Service::select('id', 'name', 'charges', 'is_nursing', 'is_advance', 'is_days')->whereType('IPD')->orderBy('name')->get();
        $departments = Department::whereType('payroll')->select('id', 'name')->orderBy('name')->get();
        $room_types = RoomType::select('id', 'name')->orderBy('name')->get();
        $rooms = Room::select('id', 'room_number', 'room_type_id', 'charges')->orderBy('room_number')->get();
        $beds = RoomBed::select('id', 'bed_number', 'room_id')->orderBy('bed_number')->get();
        $doctors = Employee::with('doctorCharges')->select('id', 'name')->whereHas('designation', function ($q) {
            $q->where('is_doctor', 1);
        })->orderBy('name')->get();
        if(file_exists(base_path('config/donation.php'))) {
            $careoffs = Donor::with('funds')->select('id', 'name')->where('type', 'care_off')->orderBy('name')->get();
        }

        $last_mr_number = Patient::max('mr_number') ?? 0;
        $new_mr_number = intval($last_mr_number) ? intval($last_mr_number) + 1 : 1;
        $new_mr_number = str_pad($new_mr_number, 5, '0', STR_PAD_LEFT);

        $zf_fees = new ZfService();
        $zf_fees = $zf_fees->getZfFees();
        $mrNumber = $this->generateUniqueMRNumber();
        $file_no = Admission::latest()->value('file_no') + 1;
        $user = Auth::user();
        $role = UserRole::where('user_id', $user->id)->where('role_id', 1)->first();
        return Inertia::render('IPD/Admissions/Create', [
            'patients' => $patients,
            'doctors' => $doctors,
            'services' => $services,
            'departments' => $departments,
            'rooms' => $rooms,
            'beds' => $beds,
            'room_types' => $room_types,
            'careoffs' => $careoffs ?? null,
            'zf_fees' => $zf_fees,
            'file_no' => $file_no,
            'role' => $role,
            'mr_number' => $new_mr_number,
        ]);
    }
    private function generateUniqueMRNumber()
    {
        $lastMRNumber = Patient::max('mr_number');
        $newMRNumber = intval($lastMRNumber) ? intval($lastMRNumber) + 1 : 1;

        while (Patient::where('mr_number', $newMRNumber)->exists()) {
            $newMRNumber++;
        }

        return str_pad($newMRNumber, 5, '0', STR_PAD_LEFT);
    }
    public function store(AdmissionRequest $request)
    {
        // dd($request->all());
        DB::beginTransaction();
        $mr_number = $request->input('mr_number._value', $request->input('mr_number', null));
        $patientData = [
            'name' => $request->name,
            'cnic' => $request->cnic,
            'dob' => $request->dob,
            'gender' => $request->gender,
            'relation_name' => $request->relation_name,
            'address' => $request->address,
            'phone' => $request->phone,
            'mr_number' => $mr_number,
        ];

        $patient = Patient::where('phone', $request->phone)->first();
        if (!$patient) {
            $patientData['mr_number'] = $mr_number;
            $patient = Patient::create($patientData);
        } else {
            $patientData['mr_number'] = $patient->mr_number;
            // unset($patientData['mr_number']);
            // $patient->update($patientData);
        }
        $admission = new Admission();
        $admissionData = $request->only($admission->getFillable());
        $admissionData['admission_date'] = date('Y-m-d',strtotime($admissionData['admission_date']));
        $check_admission = Admission::query();

        $fillableFields = (new Admission())->getFillable();

        $filteredData = array_filter(
            $admissionData,
            function ($key) use ($fillableFields) {
                return in_array($key, $fillableFields) && $key !== 'admission_time' && $key !== 'file_no' && $key !== 'discharge_date' && $key !== 'chart_no';
            },
            ARRAY_FILTER_USE_KEY
        );

        foreach ($filteredData as $key => $value) {
            if($key != 'careoff_id' && $key != 'zf_id' && $key != 'donor_fee' && $key != 'zf_fee'){
                $check_admission->where($key, $value);
            }
        }

        $last_admission_id = $check_admission->value('id');

        if ($last_admission_id) {
            if (isset($request->source) && $request->source == 'dashboard') {
                return redirect()->route('dashboard', ['admission_id' => $last_admission_id]);
            }
            return redirect()->route('admissions.index');
        }

        $file_no = Admission::latest()->value('file_no') + 1;
        $request->merge(['file_no' => $file_no]);
        $slip_no = Admission::whereDate('created_at', \Carbon\Carbon::now()->toDateString())->latest()->value('slip_no') + 1;
        $request->merge(['slip_no' => $slip_no]);
        $request->merge(['created_by' => Auth::id()]);

        if ($request->status == 'Complete' && empty($request->discharge_date)) {
            \Carbon\Carbon::setLocale('Asia/Karachi');
            $request->merge([
                'discharge_date' => \Carbon\Carbon::now()->toDateString(),
                'discharge_time' => Carbon::now()->format('H:i'),
            ]);
            $discharge_slip_no = Admission::whereDate('created_at', \Carbon\Carbon::now()->toDateString())->latest()->value('discharge_slip_no') + 1;
            $request->merge(['discharge_slip_no' => $discharge_slip_no]);
        } else {
            $request->merge([
                'discharge_date' => null,
                'discharge_time' => null,
                'discharge_slip_no' => null,
            ]);
        }

        $latestIpdSerial = Admission::max(DB::raw('CAST(Ipd_serial AS UNSIGNED)'));
        $newIpdSerial = is_null($latestIpdSerial) ? 1 : intval($latestIpdSerial) + 1;

        $request->merge(['Ipd_serial' => (string) $newIpdSerial]);

        $admission = new Admission();
        $formData = $request->only($admission->getFillable());
        $formData['admission_date'] = date('Y-m-d',strtotime($formData['admission_date']));

        $formData['patient_id'] = $patient->id;

        if(!file_exists(base_path('config/donation.php'))) {
            unset($formData['careoff_id'], $formData['zf_id'], $formData['donor_fee'], $formData['zf_fee']);
        }
        $admission = Admission::create($formData);
        if (!empty($request->details) && is_array($request->details)) {
            $details = [];
            $detailData = [];
            foreach ($request->details as $details) {
                $detailData['admission_id'] = $admission->id;
                $detailData['service_id'] = $details['service']['id'] ?? null;
                $detailData['doctor_id'] = $details['doctor']['id'] ?? null;
                $detailData['date'] = $details['date'] ?? null;
                $detailData['is_doctor'] = $details['is_doctor'] ?? null;
                $detailData['amount'] = $details['amount'] ?? null;
                $detailData['service_amount'] = $details['service_amount'] ?? null;
                $detailData['no_of_days'] = $details['no_of_days'] ?? null;
                $detailData['day_charges'] = $details['day_charges'] ?? null;
                $detailData['desc'] = $details['desc'] ?? null;
                $detailData['is_posted'] = 1;
                AdmissionDetail::create($detailData);
            }
        }
        $voucher_audit = new VoucherAuditService();
        $voucher_audit->storeVoucher('ipd', $request, 'store', $admission->id);



        DB::commit();
        if (isset($request->source) && $request->source == 'dashboard') {
            return redirect()->route('dashboard', ['admission_id' => $admission->id]);
        }
        return redirect()->route('admissions.index');
    }


    public function edit($id)
    {
        $admission = Admission::where('id', $id)->first();

        if (!$admission || $admission->status == 'Complete') {
            return redirect()->route('admissions.index');
        }
        $patient = null;
        if ($admission->cnic) {
            $patient = Patient::where('cnic', $admission->cnic)->first();
        }
        if (!$patient && $admission->phone) {
            $patient = Patient::where('phone', $admission->phone)->first();
        }

        $mr_number = $patient ? $patient->mr_number : null;
        $admission->loadMissing(['details', 'details.service', 'details.doctor']);
        $patients = Patient::select('id', 'name', 'mr_number')->orderBy('name')->get();
        $services = Service::select('id', 'name', 'charges', 'is_nursing', 'is_advance', 'is_days')->whereType('IPD')->orderBy('name')->get();
        $departments = Department::whereType('payroll')->select('id', 'name')->orderBy('name')->get();
        $room_types = RoomType::select('id', 'name')->orderBy('name')->get();
        $rooms = Room::select('id', 'room_number', 'room_type_id', 'charges')->orderBy('room_number')->get();
        $beds = RoomBed::select('id', 'bed_number', 'room_id')->orderBy('bed_number')->get();
        $doctors = Employee::with('doctorCharges')->select('id', 'name')->whereHas('designation', function ($q) {
            $q->where('is_doctor', 1);
        })->orderBy('name')->get();

        if(file_exists(base_path('config/donation.php'))) {
            $careoffs = Donor::with('funds')->select('id', 'name')->where('type', 'care_off')->orderBy('name')->get();
        }
        foreach ($admission->details as $details) {
            $details['is_nursing'] = $details?->service?->is_nursing ?? 0;
        }

        $zf_fees = new ZfService();
        $zf_fees = $zf_fees->getZfFees();

        $user = Auth::user();
        $role = UserRole::where('user_id', $user->id)->first();


        return Inertia::render('IPD/Admissions/Create', [
            'admission' => $admission,
            'patients' => $patients,
            'doctors' => $doctors,
            'services' => $services,
            'departments' => $departments,
            'rooms' => $rooms,
            'beds' => $beds,
            'room_types' => $room_types,
            'careoffs' => $careoffs ?? null,
            'role' => $role,
            'zf_fees' => $zf_fees,
            'mr_number' => $mr_number,
        ]);
    }


    public function editFile($file_no)
    {
        $admission = Admission::where('file_no', $file_no)->first();

        if (!$admission) {
            return redirect()->route('admissions.index');
        }
        return redirect('/admissions/' . $admission->id . '/edit');
    }
    public function update(AdmissionRequest $request, Admission $admission)
    {

        if ($admission->status == 'Complete' && !VoucherAudit::whereType('ipd')->where('parent_id', $admission->id)->exists()) {
            return redirect()->route('admissions.index')->with('error', 'Admission with status "Complete" cannot be updated.');
        }
        DB::beginTransaction();

        if ($request->status == 'Complete' && empty($request->discharge_date)) {
            $request->merge([
                'discharge_date' => \Carbon\Carbon::now('Asia/Karachi')->toDateString(),
                'discharge_time' => \Carbon\Carbon::now('Asia/Karachi')->format('H:i'),
            ]);

            $discharge_slip_no = Admission::whereDate('created_at', \Carbon\Carbon::now()->toDateString())->latest()->value('discharge_slip_no') + 1;
            $request->merge(['discharge_slip_no' => $discharge_slip_no]);
        }

        $formData = $request->only($admission->getFillable());
        $formData['updated_by'] = Auth::id();
        $voucher_audit = new VoucherAuditService();
        $voucher_audit = $voucher_audit->storeVoucher('ipd', $request, 'update', $admission->id);
        // if(!$voucher_audit){
        //     return redirect()->route('admissions.index')->with('message', 'Admission Already Sent to Accounts.');
        // }
        if(!file_exists(base_path('config/donation.php'))) {
            unset($formData['careoff_id'], $formData['zf_id'], $formData['donor_fee'], $formData['zf_fee']);
        }
        $admission->update($formData);

        if (!empty($request->details) && is_array($request->details)) {
            $details = [];
            $detailData = [];
            AdmissionDetail::where('admission_id', $admission->id)->delete();
            foreach ($request->details as $details) {
                $detailData['admission_id'] = $admission->id;
                $detailData['service_id'] = $details['service']['id'] ?? null;
                $detailData['doctor_id'] = $details['doctor']['id'] ?? null;
                $detailData['date'] = $details['date'] ?? null;
                $detailData['is_doctor'] = $details['is_doctor'] ?? null;
                $detailData['amount'] = $details['amount'] ?? null;
                $detailData['service_amount'] = $details['service_amount'] ?? null;
                $detailData['no_of_days'] = $details['no_of_days'] ?? null;
                $detailData['day_charges'] = $details['day_charges'] ?? null;
                $detailData['desc'] = $details['desc'] ?? null;
                $detailData['is_posted'] = 1;
                AdmissionDetail::create($detailData);
            }
        }
        // dd($request->mr_number['_value']);
        $patientData = [
            'name' => $request->name,
            'cnic' => $request->cnic,
            'dob' => $request->dob,
            'gender' => $request->gender,
            'relation_name' => $request->relation_name,
            'address' => $request->address,
            'phone' => $request->phone,
            'mr_number' => is_array($request->mr_number) ? ($request->mr_number['_value'] ?? null) : $request->mr_number,
        ];

        if (Patient::where('phone', $request->phone)->exists()) {
            Patient::where('phone', $request->phone)->update($patientData);
        }

        DB::commit();

        return redirect()->route('admissions.index')->with('message', 'Admission bed updated successfully.');
    }


    public function destroy($id)
    {
        $admission = Admission::findOrFail($id);
        $admission->delete();

        return redirect()->route('admissions.index')->with('success', 'Admission bed deleted successfully.');
    }

    public function print(Admission $admission, $discharge = null)
    {
        $user = \Auth::user();
        $role = UserRole::where('user_id', $user->id)->where('role_id', 1)->first();

        if (
            $role ||
            (
                ($admission->is_printed == 1 && $admission->is_discharged != 1 && $admission->status === 'Complete') ||
                ($admission->is_printed == 2 && $admission->is_discharged != 2) ||
                (is_null($admission->is_printed) && is_null($admission->is_discharged))
            )
        ) {

        } else {
            return redirect()->route('admissions.index')->with('error', 'Admission print is not allowed.');
        }

        $admission->load([
            'patient',
            'room',
            'roomBed',
            'creator:id,name',
            'updator:id,name',
            'details.service',
            'details.doctor',
            'department',
            'room_type'
        ]);

        $days = $admission->details->whereNull('service_id')->whereNull('doctor_id')->sum('no_of_days');
        $service_amount = 0;
        $advance_charges = 0;

        foreach ($admission->details as $details) {
            if (isset($details->service->id) && $details->service_amount > 0) {
                if ($details->service->is_advance == '1') {
                    $advance_charges += $details->day_charges;
                } else {
                    $service_amount += $details->day_charges;
                }
            } elseif (isset($details->doctor->id) && $details->amount > 0) {
                $service_amount += $details->day_charges > 0 ? $details->day_charges : $details->amount;
            } else {
                $service_amount += $details->day_charges;
            }
        }

        if(file_exists(base_path('config/donation.php'))) {
            $careoffs = Donor::with('funds')->select('id', 'name')->where('type', 'care_off')->orderBy('name')->get();
        }
        $user = \Auth::user();

        $mr_number = null;
        if ($admission->phone) {
            $patient = Patient::where('phone', $admission->phone)
                              ->latest('id')
                              ->first();

            $mr_number = $patient?->mr_number;
        }
        if ($admission->status == 'Complete' || $discharge != null) {
            // sleep(6);
            $newPrintedStatus = $admission->is_discharged == 0 ? 1 : 2;
            $admission->update(['is_discharged' => $newPrintedStatus]);
            // $admission->update(['is_discharged' => 1]);
            return Inertia::render('IPD/Admissions/DischargePrint', [
                'admission' => $admission,
                'days' => $days,
                'service_amount' => $service_amount,
                'advance_charges' => $advance_charges,
                'user' => $user,
                'careoffs' => $careoffs ?? null,
                'mr_number' => $mr_number,

            ]);
        } else {
            // sleep(3);
            $newPrintedStatus = $admission->is_printed == 0 ? 1 : 2;
            $admission->update(['is_printed' => $newPrintedStatus]);
            // $admission->update(['is_printed' => 1]);
            return Inertia::render('IPD/Admissions/AdmissionPrint', [
                'admission' => $admission,
                'days' => $days,
                'service_amount' => $service_amount,
                'advance_charges' => $advance_charges,
                'user' => $user,
                'careoffs' => $careoffs ?? null,
                'mr_number' => $mr_number,
            ]);
        }
    }


    public function cancelReceipt(Admission $admission)
    {
        $admission = $admission->loadMissing(['details', 'details.service', 'details.doctor'])->toArray();
        $advance_amount = $admission['advance_amount'];
        $voucher_audit = new VoucherAuditService();
        $voucher_audit->storeVoucher('ipd', $admission, 'store', $admission['id'], 'reverse');
        Admission::whereId($admission['id'])->update([
            'cancel' => '1',
            'refund_amount' => $advance_amount,
            'advance_amount' => null
        ]);
        return redirect()->route('admissions.index')->with('message', 'Admission bed updated successfully.');
    }
}
