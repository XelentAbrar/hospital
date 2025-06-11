<?php

namespace App\Http\Controllers\OPD;

use Inertia\Inertia;
use App\Models\UserRole;
use App\Models\OPD\Appointment;
use App\Models\OPD\PatientFeedback;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\OPD\PatientFeedbackRequest;

class PatientFeedbackController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkPermission:patient-feedbacks.index')->only('index');
        $this->middleware('checkPermission:patient-feedbacks.create')->only('create', 'store');
        $this->middleware('checkPermission:patient-feedbacks.show')->only('show');
        $this->middleware('checkPermission:patient-feedbacks.edit')->only('edit', 'update');
        $this->middleware('checkPermission:patient-feedbacks.destroy')->only('destroy');
    }
    public function index()
    {
        $patient_feedbacks = PatientFeedback::orderBy('created_at', 'desc');
        if(isset($_GET['search']) && $_GET['search'] != ''){
            $patient_feedbacks = $patient_feedbacks->where('name', 'LIKE', '%'.$_GET['search'].'%');
        }
        $patient_feedbacks = $patient_feedbacks->paginate(100);
        $user = Auth::user();
        $role = UserRole::where('user_id', $user->id)->where('role_id', 1)->first();
        return Inertia::render('OPD/PatientFeedbacks/Index', [
            'patient_feedbacks' => $patient_feedbacks,
            'role' => $role,
            'filters' => request()->only(['search']),
        ]);
    }

    public function create()
    {
        $appointments = Appointment::orderBy('id')->select('id')->get();
        return Inertia::render('OPD/PatientFeedbacks/Create', [
            'appointments' => $appointments,
        ]);
    }

    public function store(PatientFeedbackRequest $request)
    {
        $patient_feedback = new PatientFeedback();
        $patientFeedbackData = $request->only($patient_feedback->getFillable());

        PatientFeedback::create($patientFeedbackData);

        return redirect()->route('patient-feedbacks.index');
    }


    public function edit(PatientFeedback $patient_feedback)
    {
        $appointments = Appointment::orderBy('id')->select('id')->get();
        return Inertia::render('OPD/PatientFeedbacks/Create', [
            'patient_feedback' => $patient_feedback,
            'appointments' => $appointments,
        ]);
    }


    public function update(PatientFeedbackRequest $request, PatientFeedback $patient_feedback)
    {
        $patientFeedbackData = $request->only($patient_feedback->getFillable());
        $patient_feedback->update($patientFeedbackData);

        return redirect()->route('patient-feedbacks.index')->with('message', 'Patient feedback updated successfully.');
    }


    public function destroy($id)
    {
        $patient_feedback = PatientFeedback::findOrFail($id);
        $patient_feedback->delete();

        return redirect()->route('patient-feedbacks.index')->with('success', 'Patient feedback deleted successfully.');
    }
}
