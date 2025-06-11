<?php

namespace App\Http\Controllers\HRMS;

use Inertia\Inertia;
use App\Models\UserRole;
use App\Models\HRMS\Shift;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\HRMS\ShiftRequest;

class ShiftController extends Controller
{

    public function __construct()
    {
        $this->middleware('checkPermission:shifts.index')->only('index');
        $this->middleware('checkPermission:shifts.create')->only('create', 'store');
        $this->middleware('checkPermission:shifts.show')->only('show');
        $this->middleware('checkPermission:shifts.edit')->only('edit', 'update');
        $this->middleware('checkPermission:shifts.destroy')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $shifts = Shift::orderBy('created_at', 'desc')->paginate(100);
        $user = Auth::user();
        $role = UserRole::where('user_id', $user->id)->where('role_id', 1)->first();
        return Inertia::render('HRMS/Shifts/Index', [
            'shifts' => $shifts,
            'role' => $role,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('HRMS/Shifts/Create', [
            'csrf_token' => csrf_token()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ShiftRequest $request)
    {
        DB::beginTransaction();

        $shift = new Shift();
        $formData = $request->only($shift->getFillable());

        Shift::create($formData);

        DB::commit();

        return redirect()->route('shifts.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Shift $shift)
    {
        return Inertia::render('HRMS/Shifts/Create', [
            'shift' => $shift,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ShiftRequest $request, Shift $shift)
    {
        DB::beginTransaction();

        $formData = $request->only($shift->getFillable());
        $shift->update($formData);

        DB::commit();

        return redirect()->route('shifts.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Shift $shift)
    {
        $shift->delete();
        return Inertia::location(route('shifts.index'));
    }
}
