<?php

namespace App\Http\Controllers\IPD;

use Inertia\Inertia;
use App\Models\UserRole;
use App\Models\OPD\Patient;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\IPD\PatientCaseStudy;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\IPD\PatientCaseStudyRequest;

class PatientCaseStudyController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkPermission:patient-case-studies.index')->only('index');
        $this->middleware('checkPermission:patient-case-studies.create')->only('create', 'store');
        $this->middleware('checkPermission:patient-case-studies.show')->only('show');
        $this->middleware('checkPermission:patient-case-studies.edit')->only('edit', 'update');
        $this->middleware('checkPermission:patient-case-studies.destroy')->only('destroy');
    }
    public function index()
    {
        $patient_case_studies = PatientCaseStudy::with('patient:id,name')->orderBy('created_at', 'desc')->paginate(100);
        $user = Auth::user();
        $role = UserRole::where('user_id', $user->id)->where('role_id', 1)->first();
        return Inertia::render('IPD/PatientCaseStudies/Index', [
            'patient_case_studies' => $patient_case_studies,
            'role' => $role,
        ]);
    }

    public function create()
    {
        $patients = Patient::select('id', 'name')->orderBy('name')->get();
        return Inertia::render('IPD/PatientCaseStudies/Create', [
            'patients' => $patients
        ]);
    }

    public function store(PatientCaseStudyRequest $request)
    {
        DB::beginTransaction();

        $patient_case_study = new PatientCaseStudy();
        $formData = $request->only($patient_case_study->getFillable());

        PatientCaseStudy::create($formData);

        DB::commit();

        return redirect()->route('patient-case-studies.index');
    }


    public function edit(PatientCaseStudy $patient_case_study)
    {
        $patients = Patient::select('id', 'name')->orderBy('name')->get();
        return Inertia::render('IPD/PatientCaseStudies/Create', [
            'patient_case_study' => $patient_case_study,
            'patients' => $patients,
        ]);
    }


    public function update(PatientCaseStudyRequest $request, PatientCaseStudy $patient_case_study)
    {
        DB::beginTransaction();

        $formData = $request->only($patient_case_study->getFillable());
        $patient_case_study->update($formData);

        DB::commit();

        return redirect()->route('patient-case-studies.index')->with('message', 'Patient case study updated successfully.');
    }


    public function destroy($id)
    {
        $patient_case_study = PatientCaseStudy::findOrFail($id);
        $patient_case_study->delete();

        return redirect()->route('patient-case-studies.index')->with('success', 'Patient case study deleted successfully.');
    }
}
