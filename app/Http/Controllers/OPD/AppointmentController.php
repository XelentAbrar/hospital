<?php

namespace App\Http\Controllers\OPD;

use stdClass;
use Carbon\Carbon;
use App\Models\City;
use Inertia\Inertia;
use App\Models\State;
use App\Models\Country;
use App\Models\UserRole;
use XelentAbrar\HospitalDonation\Models\Donation\Donor;
use App\Models\OPD\Patient;
use App\Models\OPD\Service;
use App\Services\ZfService;
use App\Models\HRMS\Employee;
use App\Models\OPD\Appointment;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\OPD\AppointmentDetail;
use App\Services\VoucherAuditService;
// use App\Services\BackupsService;
use App\Http\Requests\OPD\AppointmentRequest;
use App\Models\HRMS\UserCashSummary;

class AppointmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkPermission:appointments.index')->only('index');
        $this->middleware('checkPermission:appointments.create')->only('create', 'store');
        $this->middleware('checkPermission:appointments.show')->only('show');
        $this->middleware('checkPermission:appointments.edit')->only('edit', 'update');
        $this->middleware('checkPermission:appointments.destroy')->only('destroy');
    }
    public function index()
    {
        $appointments = Appointment::with(['doctor:id,name' ,'creator:id,name'])->orderBy('created_at', 'desc');
        if(isset($_GET['search']) && $_GET['search'] != ''){
            $appointments = $appointments->where('patient_name', 'LIKE', '%'.$_GET['search'].'%')->orWhere('patient_cnic', 'LIKE', '%'.$_GET['search'].'%')->orWhere('patient_relation_name', 'LIKE', '%'.$_GET['search'].'%')->orWhere('patient_address', 'LIKE', '%'.$_GET['search'].'%')->orWhere('patient_phone', 'LIKE', '%'.$_GET['search'].'%')->orWhere('patient_email', 'LIKE', '%'.$_GET['search'].'%')->orWhere('slip_no', 'LIKE', '%'.$_GET['search'].'%')->orWhere('file_no', 'LIKE', '%'.$_GET['search'].'%')->orWhere('type', 'LIKE', '%'.$_GET['search'].'%')->orWhere('appointment_date', 'LIKE', '%'.$_GET['search'].'%');
            $appointments = $appointments->orWhereHas('doctor', function($q){
                $q = $q->where('name', 'LIKE', '%'.$_GET['search'].'%');
            });
        }
        $appointments = $appointments->paginate(250);
        $user = Auth::user();
        $role = UserRole::where('user_id', $user->id)->whereIn('role_id', [1, 7])->first();
        $appointments = $appointments->appends(['search' => request('search')]);
        return Inertia::render('OPD/Appointments/Index', [
            'appointments' => $appointments,
            'role' => $role,
            'filters' => request()->only(['search']),
        ]);
    }

    public function create()
    {
        $doctors = Employee::with('doctorCharges')->select('id', 'name','doctor_type')->whereHas('designation', function ($q) {
            $q->where('is_doctor', 1);
        })->orderBy('name')->get();
        $patients = Patient::select('id', 'name')->orderBy('name')->get();
        $services = Service::select('id', 'name')->whereType('OPD')->orderBy('name')->get();
        $countries = Country::select('id', 'name')->orderBy('name')->get();

        if(file_exists(base_path('config/donation.php'))) {
            $careoffs = Donor::with('funds')->select('id', 'name')->where('type','care_off')->orderBy('name')->get();
        }

        $zf_fees = new ZfService();
        $zf_fees = $zf_fees->getZfFees();
        $last_mr_number = Patient::max('mr_number');
        $new_mr_number = intval($last_mr_number) ? intval($last_mr_number) + 1 : 1;
        $new_mr_number = str_pad($new_mr_number, 5, '0', STR_PAD_LEFT);


        return Inertia::render('OPD/Appointments/Create', [
            'doctors' => $doctors,
            'patients' => $patients,
            'services' => $services,
            'countries' => $countries,
            'careoffs' => $careoffs ?? null,
            'zf_fees' => $zf_fees,
            'mr_number' => $new_mr_number,
        ]);
    }

    public function store(AppointmentRequest $request)
    {
        $appointment = new Appointment();
        $appointmentData = $request->only($appointment->getFillable());
        $check_appointment = Appointment::query();
        $fillableFields = (new Appointment())->getFillable();
        $filteredData = array_filter(
            $appointmentData,
            function ($key) use ($fillableFields) {
                return in_array($key, $fillableFields) && $key !== 'appointment_time';
            },
            ARRAY_FILTER_USE_KEY
        );

        // Apply the filtered data to the query
        foreach ($filteredData as $key => $value) {
            if($key != 'careoff_id' && $key != 'zf_id' && $key != 'donor_fee' && $key != 'zf_fee'){
                $check_appointment->where($key, $value);
            }
        }

        $last_appointment_id = $check_appointment->value('id');

        if ($last_appointment_id) {
            if (isset($request->source) && $request->source == 'dashboard') {
                return redirect()->route('dashboard', ['appointment_id' => $last_appointment_id]);
            }
            return redirect()->route('appointments.index');
        }

        $doctorId = $request->input('consulting_doctor_id');
        $latestSlipNo = Appointment::where('consulting_doctor_id', $doctorId)
            ->whereDate('created_at', Carbon::now()->toDateString())
            ->latest()
            ->value('slip_no');
        $slip_no = intval($latestSlipNo) ? intval($latestSlipNo) + 1 : 1;
        $request->merge(['slip_no' => $slip_no]);
        DB::beginTransaction();

        $patient = Patient::where('phone', $request->patient_phone)->first();

        $patientData = [
            'name' => $request->patient_name,
            'cnic' => $request->patient_cnic,
            'dob' => $request->patient_dob,
            'gender' => $request->patient_gender,
            'relation_name' => $request->patient_relation_name,
            'relation_type' => $request->patient_relation_type,
            'address' => $request->patient_address,
            'city_id' => $request->patient_city_id,
            'state_id' => $request->patient_state_id,
            'country_id' => $request->patient_country_id,
            'phone' => $request->patient_phone,
            'email' => $request->patient_email,
            'blood_group' => $request->patient_blood_group,
            'rh_factor' => $request->patient_rh_factor,
            'age' => $request->patient_age
        ];

        if (!$patient) {
            $latestMrNumber = Patient::max('mr_number');
            $mr_number = $latestMrNumber ? str_pad($latestMrNumber + 1, 5, '0', STR_PAD_LEFT) : '00001';
            $patientData['mr_number'] = $mr_number;
            $patient = Patient::create($patientData);
        } else {
            $patientData['mr_number'] = $patient->mr_number;
        }

        $appointment = new Appointment();
        $appointmentData = $request->only($appointment->getFillable());
        if (isset($mr_number)) {
            $appointmentData['mr_number'] = $mr_number;
        } elseif (isset($patient->mr_number)) {
            $appointmentData['mr_number'] = $patient->mr_number;
        } else {
            $appointmentData['mr_number'] = '';
        }
        // $appointmentData['patient_id'] = $patient->id;
        $appointmentData['created_by'] = auth()->id();
        $docSerial = 0;
        if ($request->has('consulting_doctor_id')) {
            $doctor = Employee::find($request->consulting_doctor_id);

            if ($doctor) {

                $lastAppointment = Appointment::where('consulting_doctor_id', $doctor->id)
                                              ->orderBy('created_at', 'desc')
                                              ->first();

                if ($lastAppointment) {
                    $docSerial = $lastAppointment->doc_serial + 1;
                } else {
                    if (is_null($doctor->serial_no) || $doctor->serial_no == 0) {
                        $doctor->serial_no = 1;
                    }
                    $docSerial = $doctor->serial_no;
                }

                $doctor->serial_no += 1;
                $doctor->save();
            }
        }

        $appointmentData['doc_serial'] = $docSerial;

        if(!file_exists(base_path('config/donation.php'))) {
            unset($appointmentData['careoff_id'], $appointmentData['zf_id'], $appointmentData['donor_fee'], $appointmentData['zf_fee']);
        }
        $appointmentData['appointment_date'] = date('Y-m-d', strtotime($appointmentData['appointment_date']));
        $appointment = Appointment::create($appointmentData);

        if (!empty($request->appointment_details) && is_array($request->appointment_details)) {
            $appointment_details = [];
            $appointmentDetailData = [];
            foreach ($request->appointment_details as $appointment_details) {
                $appointmentDetailData['appointment_id'] = $appointment->id;
                $appointmentDetailData['service_id'] = $appointment_details['service']['id'] ?? null;
                $appointmentDetailData['fee'] = $appointment_details['fee'] ?? null;
                $appointmentDetailData['fee_status'] = $appointment_details['fee_status']['value'] ?? null;
                AppointmentDetail::create($appointmentDetailData);
            }
        }

        $voucher_audit = new VoucherAuditService();
        $voucher_audit->storeVoucher('opd', $request, 'store', $appointment->id);
        // $totalAmount = 0;

        // foreach ($request->appointment_details as $detail) {
        //     $fee = $detail['fee'] ?? 0;
        //     $totalAmount += $fee;
        // }
        // $donorFee = $request->donor_fee ?? null;

        // if (!is_null($donorFee)) {
        //     if ($donorFee < $totalAmount) {
        //         $totalAmount = $totalAmount - $donorFee;
        //     }
        //     else {
        //         $totalAmount = 0;
        //     }
        // }
        // $userCashSummary = UserCashSummary::where('user_id', $appointment->created_by)
        //                                   ->latest()
        //                                   ->first();

        // if (!$userCashSummary || $userCashSummary->status == 'complete') {
        //     UserCashSummary::create([
        //         'user_id' => $appointment->created_by,
        //         'receipt_count' => 1,
        //         'total_amount' => $totalAmount,
        //         'status' => 'pending',
        //         'activity_time' => $request->appointment_date . ' ' . $request->appointment_time,
        //     ]);
        // } else {

        //     $userCashSummary->increment('receipt_count', 1);
        //     $userCashSummary->increment('total_amount', $totalAmount);
        // }

        DB::commit();
        if(isset($request->source) && $request->source == 'dashboard'){
            return redirect()->route('dashboard', ['appointment_id' => $appointment->id]);
        }
        return redirect()->route('appointments.index');
    }


    public function edit(Appointment $appointment)
    {
        $user = Auth::user();
        $role = UserRole::where('user_id', $user->id)->where('role_id', 1)->first();
        if ((!$role || $role->role_id != '1')) {
            return redirect()->route('appointments.index');
        }
        $appointment->loadMissing(['appointmentDetails', 'appointmentDetails.service']);
        if (isset($appointment->appointmentDetails)) {
            foreach ($appointment->appointmentDetails as $key => $appointmentDetails) {
                if (isset($appointmentDetails->fee_status)) {
                    $object = new stdClass();
                    $object->label = $appointmentDetails->fee_status;
                    $object->value = $appointmentDetails->fee_status;
                    $appointmentDetails->fee_status = $object;
                }
            }
        }
        $doctors = Employee::with('doctorCharges')->select('id', 'name','doctor_type')->whereHas('designation', function ($q) {
            $q->where('is_doctor', 1);
        })->orderBy('name')->get();
        $patient = null;
        if ($appointment->patient_cnic) {
            $patient = Patient::where('cnic', $appointment->patient_cnic)->first();
        }
        if (!$patient && $appointment->patient_phone) {
            $patient = Patient::where('phone', $appointment->patient_phone)->first();
        }

        $mr_number = $patient ? $patient->mr_number : null;
        $patients = Patient::select('id', 'name')->orderBy('name')->get();
        $services = Service::select('id', 'name')->whereType('OPD')->orderBy('name')->get();
        $countries = Country::orderBy('name')->select('id', 'name')->get();
        $states = [];
        if ($appointment->patient_state_id) {
            $states = State::where('country_id', $appointment->patient_country_id)->select('id', 'name', 'country_id')->orderBy('name')->get();
        }
        $cities = [];
        if ($appointment->patient_city_id) {
            $cities = City::where('state_id', $appointment->patient_state_id)->select('id', 'name', 'state_id')->orderBy('name')->get();
        }


        if(file_exists(base_path('config/donation.php'))) {
            $careoffs = Donor::with('funds')->select('id', 'name')->where('type','care_off')->orderBy('name')->get();
        }

        $zf_fees = new ZfService();
        $zf_fees = $zf_fees->getZfFees();

        return Inertia::render('OPD/Appointments/Create', [
            'appointment' => $appointment,
            'doctors' => $doctors,
            'patients' => $patients,
            'services' => $services,
            'countries' => $countries,
            'states' => $states,
            'cities' => $cities,
            'careoffs' => $careoffs ?? null,
            'zf_fees' => $zf_fees,
            'mr_number' => $mr_number,
        ]);

    }


    public function update(AppointmentRequest $request, Appointment $appointment)
    {
        // if(isset($request->careoff_id) && $request->careoff_id != null){
        //     $donorFee = $request->input('donor_fee', 0);
        //     foreach($request->appointment_details as $appointment_details){
        //         $donorFee += $appointment_details['fee'];
        //     }
        //     $request->merge(['donor_fee' => $donorFee]);
        //     $request->merge(['zf_fee' => null]);
        // }

        $employeeDeductionData = $request->only($appointment->getFillable());
        $employeeDeductionData['updated_by'] = Auth::id();
        if ($appointment->patient_id != null && $request->patient_id == null) {
            $employeeDeductionData['patient_id'] = $appointment->patient_id;
        }

        $voucher_audit = new VoucherAuditService();
        $voucher_audit = $voucher_audit->storeVoucher('opd', $request, 'update', $appointment->id);
        if(!$voucher_audit){
            return redirect()->route('appointments.index')->with('message', 'Appointment Already Sent to Accounts.');
        }


        if(!file_exists(base_path('config/donation.php'))) {
            unset($employeeDeductionData['careoff_id'], $employeeDeductionData['zf_id'], $employeeDeductionData['donor_fee'], $employeeDeductionData['zf_fee']);
        }
        $appointment->update($employeeDeductionData);

        if (!empty($request->appointment_details) && is_array($request->appointment_details)) {
            $appointment_details = [];
            $appointmentDetailData = [];
            AppointmentDetail::where('appointment_id', $appointment->id)->delete();
            foreach ($request->appointment_details as $appointment_details) {
                $appointmentDetailData['appointment_id'] = $appointment->id;
                $appointmentDetailData['service_id'] = $appointment_details['service']['id'] ?? null;
                $appointmentDetailData['fee'] = $appointment_details['fee'] ?? null;
                $appointmentDetailData['fee_status'] = $appointment_details['fee_status']['value'] ?? null;
                AppointmentDetail::create($appointmentDetailData);
            }
        }
        if (Patient::where('phone', $request->patient_phone)->exists()) {
            $patient = Patient::where('phone', $request->patient_phone)->first();
            $mr_number = str_pad($patient->mr_number + 1, 5, '0', STR_PAD_LEFT);
            $patient->update([
                'name' => $request->patient_name,
                'cnic' => $request->patient_cnic,
                'mr_number' => $mr_number,
                'dob' => $request->patient_dob,
                'gender' => $request->patient_gender,
                'relation_name' => $request->patient_relation_name,
                'relation_type' => $request->patient_relation_type,
                'address' => $request->patient_address,
                'city_id' => $request->patient_city_id,
                'state_id' => $request->patient_state_id,
                'country_id' => $request->patient_country_id,
                'phone' => $request->patient_phone,
                'email' => $request->patient_email,
                'blood_group' => $request->patient_blood_group,
                'rh_factor' => $request->patient_rh_factor,
                'age' => $request->patient_age,
            ]);
        } else {
            $latestMrNumber = Patient::max('mr_number');
            $mr_number = str_pad($latestMrNumber + 1, 5, '0', STR_PAD_LEFT);
            $patientData = [
                'name' => $request->patient_name,
                'cnic' => $request->patient_cnic,
                'mr_number' => $mr_number,
                'dob' => $request->patient_dob,
                'gender' => $request->patient_gender,
                'relation_name' => $request->patient_relation_name,
                'relation_type' => $request->patient_relation_type,
                'address' => $request->patient_address,
                'city_id' => $request->patient_city_id,
                'state_id' => $request->patient_state_id,
                'country_id' => $request->patient_country_id,
                'phone' => $request->patient_phone,
                'email' => $request->patient_email,
                'blood_group' => $request->patient_blood_group,
                'rh_factor' => $request->patient_rh_factor,
                'age' => $request->patient_age,
            ];

            $patient = Patient::create($patientData);
        }
        // if(Patient::where('phone', $request->patient_phone)->exists()){
        //     Patient::where('phone', $request->patient_phone)->update([
        //         'name' => $request->patient_name,
        //         'cnic' => $request->patient_cnic,
        //         'mr_number' => $request->mr_number,
        //         'dob' => $request->patient_dob,
        //         'gender' => $request->patient_gender,
        //         'relation_name' => $request->patient_relation_name,
        //         'relation_type' => $request->patient_relation_type,
        //         'address' => $request->patient_address,
        //         'city_id' => $request->patient_city_id,
        //         'state_id' => $request->patient_state_id,
        //         'country_id' => $request->patient_country_id,
        //         'phone' => $request->patient_phone,
        //         'email' => $request->patient_email,
        //         'blood_group' => $request->patient_blood_group,
        //         'rh_factor' => $request->patient_rh_factor,
        //         'age' => $request->patient_age,
        //     ]);
        // }

        return redirect()->route('appointments.index')->with('message', 'Appointment updated successfully.');
    }


    public function destroy($id)
    {
        $appointment = Appointment::findOrFail($id);

        $appointment = $appointment->loadMissing(['appointmentDetails', 'appointmentDetails.service'])->toArray();
        $voucher_audit = new VoucherAuditService();
        $voucher_audit->storeVoucher('opd', $appointment, 'store', $id, 'reverse');
        Appointment::whereId($id)->update([
            'cancel' => '1'
        ]);
        Appointment::findOrFail($id)->delete();

        return redirect()->route('appointments.index')->with('success', 'Appointment deleted successfully.');
    }

    // public function print(Appointment $appointment)
    // {
    //     $appointment->load(['doctor.education', 'patient','creator:id,name', 'appointmentDetails','careoff']);
    //     $user = \Auth::user()->name;
    //     $print_date_time = date('d-m-Y H:i:s');

    //     $print = $appointment?->doctor?->doctor_template ?? 'Print1';
    //         return Inertia::render('OPD/Appointments/'.$print, [
    //             'appointment' => $appointment,
    //             'user' => $user,
    //             'print_date_time' => $print_date_time,
    //         ]);

    // }
        public function print(Appointment $appointment)
        {
            $user = Auth::user();
            $role = UserRole::where('user_id', $user->id)->whereIn('role_id', [1,7])->first();
            if (!$role && $appointment->is_printed == 1 && $appointment->is_printed == 2) {
                return redirect()->route('appointments.index')->with('error', 'This appointment has already been printed.');
            }
            if(file_exists(base_path('config/donation.php'))) {
                $appointment->load([
                    'doctor.education',
                    'patient',
                    'creator:id,name',
                    'appointmentDetails',
                    'careoff'
                ]);
            }
            else{
                $appointment->load([
                    'doctor.education',
                    'patient',
                    'creator:id,name',
                    'appointmentDetails'
                ]);
            }
            $mr_number = null;
            if ($appointment->patient_phone) {
                $patient = Patient::where('phone', $appointment->patient_phone)
                                  ->latest('id')
                                  ->first();

                $mr_number = $patient?->mr_number;
            }
            $newPrintedStatus = $appointment->is_printed == 0 ? 1 : 2;
            $appointment->update(['is_printed' => $newPrintedStatus]);
            // $appointment->update(['is_printed' => 1]);
            $user = \Auth::user()->name;
            $print_date_time = date('d-m-Y H:i:s');
            $print = $appointment?->doctor?->doctor_template ?? 'Print1';
            return Inertia::render('OPD/Appointments/' . $print, [
                'appointment' => $appointment,
                'user' => $user,
                'print_date_time' => $print_date_time,
                'mr_number' => $mr_number,
            ]);
        }

    public function cancelReceipt(Appointment $appointment)
    {
        $appointment = $appointment->loadMissing(['appointmentDetails', 'appointmentDetails.service'])->toArray();
        $voucher_audit = new VoucherAuditService();
        $voucher_audit->storeVoucher('opd', $appointment, 'store', $appointment['id'], 'reverse');
        Appointment::whereId($appointment['id'])->update([
            'cancel' => '1'
        ]);

        return redirect()->route('appointments.index')->with('message', 'Appointment updated successfully.');
    }


}
