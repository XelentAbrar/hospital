<?php

namespace App\Http\Controllers\HRMS;

use Inertia\Inertia;
use App\Models\UserRole;
use App\Models\HRMS\Allowance;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\HRMS\AllowanceRequest;

class AllowanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkPermission:allowances.index')->only('index');
        $this->middleware('checkPermission:allowances.create')->only('create', 'store');
        $this->middleware('checkPermission:allowances.show')->only('show');
        $this->middleware('checkPermission:allowances.edit')->only('edit', 'update');
        $this->middleware('checkPermission:allowances.destroy')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $allowances = Allowance::orderBy('created_at', 'desc')->paginate(100);
        $user = Auth::user();
        $role = UserRole::where('user_id', $user->id)->where('role_id', 1)->first();
        return Inertia::render('HRMS/Allowances/Index', [
            'allowances' => $allowances,
            'role' => $role,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('HRMS/Allowances/Create', [
            'csrf_token' => csrf_token()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AllowanceRequest $request)
    {
        DB::beginTransaction();

        $allowance = new Allowance();
        $formData = $request->only($allowance->getFillable());

        Allowance::create($formData);

        DB::commit();

        return redirect()->route('allowances.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Allowance $allowance)
    {
        return Inertia::render('HRMS/Allowances/Create', [
            'allowance' => $allowance,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AllowanceRequest $request, Allowance $allowance)
    {
        DB::beginTransaction();

        $formData = $request->only($allowance->getFillable());
        $allowance->update($formData);

        DB::commit();

        return redirect()->route('allowances.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Allowance $allowance)
    {
        $allowance->delete();
        return Inertia::location(route('allowances.index'));
    }
}
