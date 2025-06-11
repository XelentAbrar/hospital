<?php

namespace App\Http\Controllers\HRMS;

use Inertia\Inertia;
use App\Models\UserRole;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\HRMS\AttendanceMachine;
use App\Http\Requests\HRMS\AttendanceMachineRequest;

class AttendanceMachineController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkPermission:attendance-machines.index')->only('index');
        $this->middleware('checkPermission:attendance-machines.create')->only('create', 'store');
        $this->middleware('checkPermission:attendance-machines.show')->only('show');
        $this->middleware('checkPermission:attendance-machines.edit')->only('edit', 'update');
        $this->middleware('checkPermission:attendance-machines.destroy')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $attendance_machines = AttendanceMachine::orderBy('created_at', 'desc')->paginate(100);
        $user = Auth::user();
        $role = UserRole::where('user_id', $user->id)->where('role_id', 1)->first();
        return Inertia::render('HRMS/AttendanceMachines/Index', [
            'attendance_machines' => $attendance_machines,
            'role' => $role,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('HRMS/AttendanceMachines/Create', [
            'csrf_token' => csrf_token()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AttendanceMachineRequest $request)
    {
        DB::beginTransaction();

        $attendance_machine = new AttendanceMachine();
        $formData = $request->only($attendance_machine->getFillable());

        AttendanceMachine::create($formData);

        DB::commit();

        return redirect()->route('attendance-machines.index');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AttendanceMachine $attendance_machine)
    {
        return Inertia::render('HRMS/AttendanceMachines/Create', [
            'attendance_machine' => $attendance_machine,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AttendanceMachineRequest $request, AttendanceMachine $attendance_machine)
    {
        DB::beginTransaction();

        $formData = $request->only($attendance_machine->getFillable());
        $attendance_machine->update($formData);

        DB::commit();

        return redirect()->route('attendance-machines.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AttendanceMachine $attendance_machine)
    {
        $attendance_machine->delete();
        return Inertia::location(route('attendance-machines.index'));
    }
}
