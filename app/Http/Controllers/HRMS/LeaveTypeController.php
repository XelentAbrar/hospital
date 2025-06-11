<?php

namespace App\Http\Controllers\HRMS;

use Inertia\Inertia;
use App\Models\UserRole;
use App\Models\HRMS\LeaveType;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\HRMS\LeaveTypeRequest;

class LeaveTypeController extends Controller
{

    public function __construct()
    {
        $this->middleware('checkPermission:leave-types.index')->only('index');
        $this->middleware('checkPermission:leave-types.create')->only('create', 'store');
        $this->middleware('checkPermission:leave-types.show')->only('show');
        $this->middleware('checkPermission:leave-types.edit')->only('edit', 'update');
        $this->middleware('checkPermission:leave-types.destroy')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $leaveTypes = LeaveType::orderBy('created_at', 'desc')->paginate(100);
        $user = Auth::user();
        $role = UserRole::where('user_id', $user->id)->where('role_id', 1)->first();
        return Inertia::render('HRMS/LeaveType/Index', [
            'leaveTypes' => $leaveTypes,
            'role' => $role,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('HRMS/LeaveType/Create', [
            'csrf_token' => csrf_token()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LeaveTypeRequest $request)
    {
        DB::beginTransaction();

        $leaveType = new LeaveType();
        $formData = $request->only($leaveType->getFillable());

        LeaveType::create($formData);

        DB::commit();


        return redirect()->route('leave-types.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LeaveType $leaveType)
    {
        return Inertia::render('HRMS/LeaveType/Create', [
            'leaveType' => $leaveType,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(LeaveTypeRequest $request, LeaveType $leaveType)
    {
        DB::beginTransaction();

        $formData = $request->only($leaveType->getFillable());
        $leaveType->update($formData);

        DB::commit();

        return redirect()->route('leave-types.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LeaveType $leaveType)
    {
        $leaveType->delete();
        return Inertia::location(route('leave-types.index'));
    }
}
