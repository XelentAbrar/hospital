<?php

namespace App\Http\Controllers\OPD;

use App\Models\City;
use Inertia\Inertia;
use App\Models\State;
use App\Models\Country;
use App\Models\UserRole;
use App\Models\OPD\Patient;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\OPD\PatientRequest;

class PatientController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkPermission:patients.index')->only('index');
        $this->middleware('checkPermission:patients.create')->only('create', 'store');
        $this->middleware('checkPermission:patients.show')->only('show');
        $this->middleware('checkPermission:patients.edit')->only('edit', 'update');
        $this->middleware('checkPermission:patients.destroy')->only('destroy');
    }
    public function index()
    {
        $patients = Patient::with(['country:id,name', 'state:id,name', 'city:id,name'])->orderBy('created_at', 'desc');
        if(isset($_GET['search']) && $_GET['search'] != ''){
            $patients = $patients->where('name', 'LIKE', '%'.$_GET['search'].'%')->orWhere('cnic', 'LIKE', '%'.$_GET['search'].'%')->orWhere('gender', 'LIKE', '%'.$_GET['search'].'%')->orWhere('relation_name', 'LIKE', '%'.$_GET['search'].'%')->orWhere('phone', 'LIKE', '%'.$_GET['search'].'%')->orWhere('email', 'LIKE', '%'.$_GET['search'].'%')->orWhere('address', 'LIKE', '%'.$_GET['search'].'%');
        }
        $patients = $patients->paginate(100);
        $patients = $patients->appends(['search' => request('search')]);
        $user = Auth::user();
        $role = UserRole::where('user_id', $user->id)->where('role_id', 1)->first();
        return Inertia::render('OPD/Patients/Index', [
            'patients' => $patients,
            'role' => $role,
            'filters' => request()->only(['search']),
        ]);
    }

    public function create()
    {
        $countries = Country::orderBy('name')->select('id', 'name')->get();
        $last_mr_number = Patient::latest()->value('mr_number');
        $new_mr_number = intval($last_mr_number) ? intval($last_mr_number) + 1 : 1;
        $new_mr_number = str_pad($new_mr_number, 5, '0', STR_PAD_LEFT);
        return Inertia::render('OPD/Patients/Create',[
            'countries' => $countries,
            'mr_number' => $new_mr_number,
        ]);
    }

    public function store(PatientRequest $request)
    {

        $patient = new Patient();
        $employeeDeductionData = $request->only($patient->getFillable());

        if (isset($request->mr_number['_value'])) {
            $employeeDeductionData['mr_number'] = $request->mr_number['_value'];
        }

        Patient::create($employeeDeductionData);

        return redirect()->route('patients.index');
    }



    public function getPatientByPhone($phone)
    {
        $patient = Patient::where('phone', $phone)->orWhere('mr_number' ,$phone)->orWhere('cnic' ,$phone)->first();
        return response()->json(['patient' => $patient]);
    }

    public function show(Patient $patient)
    {
        $patient->loadMissing(['country', 'state', 'city']);
        return response()->json(['patient' => $patient]);
    }


    public function edit(Patient $patient)
    {
        $countries = Country::orderBy('name')->select('id', 'name')->get();
        $states = [];
        if ($patient->state_id) {
            $states = State::where('country_id', $patient->country_id)->select('id', 'name', 'country_id')->orderBy('name')->get();
        }
        $cities = [];
        if ($patient->city_id) {
            $cities = City::where('state_id', $patient->state_id)->select('id', 'name', 'state_id')->orderBy('name')->get();
        }

        return Inertia::render('OPD/Patients/Create', [
            'patient' => $patient,
            'countries' => $countries,
            'states' => $states,
            'cities' => $cities,
        ]);
    }


    public function update(PatientRequest $request, Patient $patient)
    {
        $employeeDeductionData = $request->only($patient->getFillable());
        $patient->update($employeeDeductionData);

        return redirect()->route('patients.index')->with('message', 'Patient updated successfully.');
    }


    public function destroy($id)
    {
        $patient = Patient::findOrFail($id);
        $patient->delete();

        return redirect()->route('patients.index')->with('success', 'Patient deleted successfully.');
    }
}
