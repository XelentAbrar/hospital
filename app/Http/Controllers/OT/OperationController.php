<?php

namespace App\Http\Controllers\OT;

use App\Http\Controllers\Controller;
use App\Http\Requests\OT\OperationRequest;
use App\Models\HRMS\Department;
use App\Models\HRMS\Employee;
use App\Models\IPD\Admission;
use App\Models\IPD\Room;
use App\Models\IPD\RoomBed;
use App\Models\IPD\RoomType;
use XelentAbrar\HospitalDonation\Models\Donation\Donor;
use App\Models\OPD\Patient;
use App\Models\OPD\Service;
use App\Models\OT\Operation;
use App\Models\OT\OperationDetail;
use App\Models\UserRole;
use App\Services\ZfService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use PHPUnit\Framework\Constraint\Operator;

class OperationController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkPermission:operations.index')->only('index');
        $this->middleware('checkPermission:operations.create')->only('create', 'store');
        $this->middleware('checkPermission:operations.show')->only('show');
        $this->middleware('checkPermission:operations.edit')->only('edit', 'update');
        $this->middleware('checkPermission:operations.destroy')->only('destroy');
    }

    public function index()
    {
        $operations = Operation::with(['careoff:id,name', 'creator:id,name'])->orderBy('created_at', 'desc');
        if (isset($_GET['search']) && $_GET['search'] != '') {
            $operations = $operations->where('patient_name', 'LIKE', '%' . $_GET['search'] . '%')->orWhere('patient_cnic', 'LIKE', '%' . $_GET['search'] . '%')->orWhere('patient_relation_name', 'LIKE', '%' . $_GET['search'] . '%')->orWhere('patient_address', 'LIKE', '%' . $_GET['search'] . '%')->orWhere('patient_phone', 'LIKE', '%' . $_GET['search'] . '%')->orWhere('file_no', 'LIKE', '%' . $_GET['search'] . '%')->orWhere('operation_date', 'LIKE', '%' . $_GET['search'] . '%');
            $operations = $operations->orWhereHas('careoff', function ($q) {
                $q = $q->where('name', 'LIKE', '%' . $_GET['search'] . '%');
            });
        }
        $operations = $operations->paginate(250);
        $user = Auth::user();
        $role = UserRole::where('user_id', $user->id)->where('role_id', 1)->first();
        $operations = $operations->appends(['search' => request('search')]);
        return Inertia::render('OT/Operations/Index', [
            'operations' => $operations,
            'role' => $role,
            'filters' => request()->only(['search']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // dd($request->all());
        $patients = Patient::select('id', 'name')->orderBy('name')->get();
        $departments = Department::whereType('payroll')->select('id', 'name')->orderBy('name')->get();
        if(file_exists(base_path('config/donation.php'))) {
            $careoffs = Donor::with('funds')->select('id', 'name')->where('type', 'care_off')->orderBy('name')->get();
        }

        $zf_fees = new ZfService();
        $zf_fees = $zf_fees->getZfFees();
        // $file_no = Admission::latest()->value('file_no') + 1;
        $user = Auth::user();
        $role = UserRole::where('user_id', $user->id)->where('role_id', 1)->first();

        $last_mr_number = Patient::max('mr_number');
        $new_mr_number = intval($last_mr_number) ? intval($last_mr_number) + 1 : 1;
        $new_mr_number = str_pad($new_mr_number, 5, '0', STR_PAD_LEFT);


        $admission = null;
        $matched_patient = null;

        if ($request->has('file_no') || $request->has('patient_phone')) {
            $admission = Admission::when($request->file_no, function ($query, $file_no) {
                return $query->where('file_no', $file_no);
            })->when($request->patient_phone, function ($query, $patient_phone) {
                return $query->where('patient_phone', $patient_phone);
            })->first();

            if ($admission) {
                $matched_patient = Patient::where('phone', $admission->patient_phone)->first();
            }
        }

        return Inertia::render('OT/Operations/Create', [
            'patients' => $patients,
            'departments' => $departments,
            'careoffs' => $careoffs ?? null,
            'zf_fees' => $zf_fees,
            'mr_number' => $new_mr_number,
            // 'file_no' => $file_no,
            'role' => $role,
            'admission' => $admission,
            'matched_patient' => $matched_patient,
        ]);
    }
    public function getAdmissions(Request $request)
    {
        // dd($request->all());
        $matched_patient = null;
        $admission = null;

        if ($request->has('file_no') || $request->has('patient_phone')) {
            $admission = Admission::when($request->file_no, function ($query, $file_no) {
                return $query->where('file_no', $file_no);
            })->when($request->patient_phone, function ($query, $patient_phone) {
                return $query->where('patient_phone', $patient_phone);
            })->first();

            if ($admission) {
                $matched_patient = Patient::where('phone', $admission->patient_phone)->first();
            }
        }

        return response()->json([
            'admission' => $admission ? $admission->only(['age' ,'gender' , 'total_amount', 'relation_name' ,'advance_amount', 'received_amount', 'file_no', 'name', 'address', 'phone', 'cnic']) : null,
            'matched_patient' => $matched_patient,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */

    // public function store(OperationRequest $request)
    // {

    //     $patient = Patient::where('phone', $request->patient_phone)->first();

    //     $patientData = [
    //         'name' => $request->patient_name,
    //         'cnic' => $request->patient_cnic,
    //         'gender' => $request->patient_gender,
    //         'relation_name' => $request->patient_relation_name,
    //         'address' => $request->patient_address,
    //         'age' => $request->patient_age,
    //         'phone' => $request->patient_phone,
    //     ];

    //     DB::beginTransaction();

    //     try {
    //         if (!$patient) {
    //             $latestMrNumber = Patient::max('mr_number');
    //             $mr_number = $latestMrNumber ? str_pad($latestMrNumber + 1, 5, '0', STR_PAD_LEFT) : '00001';
    //             $patientData['mr_number'] = $mr_number;
    //             $patient = Patient::create($patientData);
    //         } else {
    //             $patient->update($patientData);
    //             $mr_number = $patient->mr_number;
    //         }

    //         $operation = new Operation();
    //         $operationData = $request->only($operation->getFillable());
    //         $operationData['mr_number'] = $mr_number;
    //         $operationData['created_by'] = Auth::id();

    //         $operation = Operation::create($operationData);

    //         DB::commit();

    //         if (isset($request->source) && $request->source == 'dashboard') {
    //             return redirect()->route('dashboard', ['operation_id' => $operation->id]);
    //         }

    //         return redirect()->route('operations.index')->with('success', 'Operation created successfully.');
    //     } catch (\Exception $e) {
    //         DB::rollback();
    //         \Log::error($e);
    //         return redirect()->back()->withInput()->with('error', 'Failed to create operation. Please check the logs.');
    //     }
    // }
    public function store(OperationRequest $request)
    {
        // dd($request->all());
        $patient = Patient::where('phone', $request->patient_phone)->first();

        $patientData = [
            'name' => $request->patient_name,
            'cnic' => $request->patient_cnic,
            'gender' => $request->patient_gender,
            'relation_name' => $request->patient_relation_name,
            'address' => $request->patient_address,
            'age' => $request->patient_age,
            'phone' => $request->patient_phone,
        ];

        DB::beginTransaction();

        try {
            if (!$patient) {
                $latestMrNumber = Patient::max('mr_number');
                $mr_number = $latestMrNumber ? str_pad($latestMrNumber + 1, 5, '0', STR_PAD_LEFT) : '00001';
                $patientData['mr_number'] = $mr_number;
                $patient = Patient::create($patientData);
            } else {
                $patient->update($patientData);
                $mr_number = $patient->mr_number;
            }

            $operation = new Operation();
            $operationData = $request->only($operation->getFillable());
            $operationData['mr_number'] = $mr_number;
            $operationData['created_by'] = Auth::id();
            $operation = Operation::create($operationData);

            if (!empty($request->details) && is_array($request->details)) {
                foreach ($request->details as $detail) {
                    $detailData = [
                        'operation_id' => $operation->id,
                        'service_id' => $detail['service_id'] ?? null,
                        'doctor_id' => $detail['doctor_id'] ?? null,
                        'date' => $detail['date'] ?? null,
                        'is_doctor' => $detail['is_doctor'] ?? null,
                        'service_amount' => $detail['service_amount'] ?? null,
                        'doctor_amount' => $detail['doctor_amount'] ?? null,
                        'is_posted' => $detail['is_posted'] ?? 1,
                        'description' => $detail['description'] ?? null,
                        'no_of_days' => $detail['no_of_days'] ?? null,
                    ];
                    OperationDetail::create($detailData);
                }
            }

            DB::commit();

            if (isset($request->source) && $request->source == 'dashboard') {
                return redirect()->route('dashboard', ['operation_id' => $operation->id]);
            }

            return redirect()->route('operations.index')->with('success', 'Operation and details created successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error($e);
            return redirect()->back()->withInput()->with('error', 'Failed to create operation. Please check the logs.');
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $operation = Operation::where('id', $id)->first();

        if (!$operation || $operation->status == 'Complete') {
            return redirect()->route('operations.index');
        }
        $patient = null;
        if ($operation->patient_cnic) {
            $patient = Patient::where('cnic', $operation->patient_cnic)->first();
        }
        if (!$patient && $operation->patient_phone) {
            $patient = Patient::where('phone', $operation->patient_phone)->first();
        }

        $mr_number = $patient ? $patient->mr_number : null;
        $operation->loadMissing(['details', 'details.service', 'details.doctor']);
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
        foreach ($operation->details as $details) {
            $details['is_nursing'] = $details?->service?->is_nursing ?? 0;
        }

        $zf_fees = new ZfService();
        $zf_fees = $zf_fees->getZfFees();

        $user = Auth::user();
        $role = UserRole::where('user_id', $user->id)->first();
        return Inertia::render('OT/Operations/Create', [
            'operation' => $operation,
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

    /**
     * Update the specified resource in storage.
     */
    public function update(OperationRequest $request, Operation $operation)
    {
        DB::beginTransaction();

        try {
            $operationData = $request->only($operation->getFillable());
            $operationData['updated_by'] = Auth::id();
            $operation->update($operationData);

            if (!empty($request->details) && is_array($request->details)) {
                OperationDetail::where('operation_id', $operation->id)->delete();

                foreach ($request->details as $detail) {
                    $detailData = [
                        'operation_id' => $operation->id,
                        'service_id' => $detail['service_id'] ?? null,
                        'doctor_id' => $detail['doctor_id'] ?? null,
                        'date' => $detail['date'] ?? null,
                        'is_doctor' => $detail['is_doctor'] ?? null,
                        'service_amount' => $detail['service_amount'] ?? null,
                        'doctor_amount' => $detail['doctor_amount'] ?? null,
                        'is_posted' => $detail['is_posted'] ?? 1,
                        'description' => $detail['description'] ?? null,
                        'no_of_days' => $detail['no_of_days'] ?? null,
                    ];
                    OperationDetail::create($detailData);
                }
            }

            $patientData = [
                'name' => $request->patient_name,
                'cnic' => $request->patient_cnic,
                'gender' => $request->patient_gender,
                'relation_name' => $request->patient_relation_name,
                'address' => $request->patient_address,
                'age' => $request->patient_age,
                'phone' => $request->patient_phone,
                'mr_number' => is_array($request->mr_number) ? ($request->mr_number['_value'] ?? null) : $request->mr_number,
            ];

            if (Patient::where('phone', $request->patient_phone)->exists()) {
                Patient::where('phone', $request->patient_phone)->update($patientData);
            }

            DB::commit();

            return redirect()->route('operations.index')->with('success', 'Operation updated successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error($e);
            return redirect()->back()->withInput()->with('error', 'Failed to update operation. Please check the logs.');
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $operation = Operation::findOrFail($id);
        $operation->delete();

        return redirect()->route('operations.index')->with('success', 'Admission bed deleted successfully.');
    }
}
