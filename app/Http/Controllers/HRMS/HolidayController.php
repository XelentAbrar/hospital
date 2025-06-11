<?php

namespace App\Http\Controllers\HRMS;

use Inertia\Inertia;
use App\Models\UserRole;
use App\Models\HRMS\Holiday;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\HRMS\HolidayRequest;

class HolidayController extends Controller
{

    public function __construct()
    {
        $this->middleware('checkPermission:holidays.index')->only('index');
        $this->middleware('checkPermission:holidays.create')->only('create', 'store');
        $this->middleware('checkPermission:holidays.show')->only('show');
        $this->middleware('checkPermission:holidays.edit')->only('edit', 'update');
        $this->middleware('checkPermission:holidays.destroy')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $holidays = Holiday::orderBy('created_at', 'desc')->paginate(100);
        $user = Auth::user();
        $role = UserRole::where('user_id', $user->id)->where('role_id', 1)->first();
        return Inertia::render('HRMS/Holidays/Index', [
            'holidays' => $holidays,
            'role' => $role,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('HRMS/Holidays/Create', [
            'csrf_token' => csrf_token()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(HolidayRequest $request)
    {
        DB::beginTransaction();

        $holiday = new Holiday();
        $formData = $request->only($holiday->getFillable());

        Holiday::create($formData);

        DB::commit();

        return redirect()->route('holidays.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Holiday $holiday)
    {
        return Inertia::render('HRMS/Holidays/Create', [
            'holiday' => $holiday,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(HolidayRequest $request, Holiday $holiday)
    {
        DB::beginTransaction();

        $formData = $request->only($holiday->getFillable());
        $holiday->update($formData);

        DB::commit();

        return redirect()->route('holidays.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Holiday $holiday)
    {
        $holiday->delete();
        return Inertia::location(route('holidays.index'));
    }
}
