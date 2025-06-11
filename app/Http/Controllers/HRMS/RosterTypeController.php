<?php

namespace App\Http\Controllers\HRMS;

use Inertia\Inertia;
use App\Models\UserRole;
use App\Models\HRMS\RosterType;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\HRMS\RosterTypeRequest;

class RosterTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkPermission:roster-types.index')->only('index');
        $this->middleware('checkPermission:roster-types.create')->only('create', 'store');
        $this->middleware('checkPermission:roster-types.show')->only('show');
        $this->middleware('checkPermission:roster-types.edit')->only('edit', 'update');
        $this->middleware('checkPermission:roster-types.destroy')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roster_types = RosterType::orderBy('created_at', 'desc')->paginate(100);
        $user = Auth::user();
        $role = UserRole::where('user_id', $user->id)->where('role_id', 1)->first();
        return Inertia::render('HRMS/RosterTypes/Index', [
            'roster_types' => $roster_types,
            'role' => $role,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('HRMS/RosterTypes/Create', [
            'csrf_token' => csrf_token()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RosterTypeRequest $request)
    {
        DB::beginTransaction();

        $roster_type = new RosterType();
        $formData = $request->only($roster_type->getFillable());

        RosterType::create($formData);

        DB::commit();

        return redirect()->route('roster-types.index');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RosterType $roster_type)
    {
        return Inertia::render('HRMS/RosterTypes/Create', [
            'roster_type' => $roster_type,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RosterTypeRequest $request, RosterType $roster_type)
    {
        DB::beginTransaction();

        $formData = $request->only($roster_type->getFillable());
        $roster_type->update($formData);

        DB::commit();

        return redirect()->route('roster-types.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RosterType $roster_type)
    {
        $roster_type->delete();
        return Inertia::location(route('roster-types.index'));
    }
}
