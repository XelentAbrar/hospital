<?php

namespace App\Http\Controllers\HRMS;

use Inertia\Inertia;
use App\Models\UserRole;
use App\Models\HRMS\Designation;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\HRMS\DesignationRequest;

class DesignationController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkPermission:designations.index')->only('index');
        $this->middleware('checkPermission:designations.create')->only('create', 'store');
        $this->middleware('checkPermission:designations.show')->only('show');
        $this->middleware('checkPermission:designations.edit')->only('edit', 'update');
        $this->middleware('checkPermission:designations.destroy')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $designations = Designation::orderBy('created_at', 'desc')->paginate(100);
        $user = Auth::user();
        $role = UserRole::where('user_id', $user->id)->where('role_id', 1)->first();
        return Inertia::render('HRMS/Designation/Index', [
            'designations' => $designations,
            'role' => $role,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('HRMS/Designation/Create', [
            'csrf_token' => csrf_token()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DesignationRequest $request)
    {
        DB::beginTransaction();

        $designation = new Designation();
        $formData = $request->only($designation->getFillable());

        Designation::create($formData);

        DB::commit();

        return redirect()->route('designations.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Designation $designation)
    {
        return Inertia::render('HRMS/Designation/Create', [
            'designation' => $designation,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DesignationRequest $request, Designation $designation)
    {
        DB::beginTransaction();

        $formData = $request->only($designation->getFillable());
        $designation->update($formData);

        DB::commit();

        return redirect()->route('designations.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Designation $designation)
    {
        $designation->delete();
        return Inertia::location(route('designations.index'));
    }
}
