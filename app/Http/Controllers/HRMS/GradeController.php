<?php

namespace App\Http\Controllers\HRMS;

use Inertia\Inertia;
use App\Models\UserRole;
use App\Models\HRMS\Grade;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\HRMS\GradeRequest;

class GradeController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkPermission:grades.index')->only('index');
        $this->middleware('checkPermission:grades.create')->only('create', 'store');
        $this->middleware('checkPermission:grades.show')->only('show');
        $this->middleware('checkPermission:grades.edit')->only('edit', 'update');
        $this->middleware('checkPermission:grades.destroy')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $grades = Grade::orderBy('created_at', 'desc')->paginate(100);
        $user = Auth::user();
        $role = UserRole::where('user_id', $user->id)->where('role_id', 1)->first();
        return Inertia::render('HRMS/Grades/Index', [
            'grades' => $grades,
            'role' => $role,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('HRMS/Grades/Create', [
            'csrf_token' => csrf_token()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(GradeRequest $request)
    {
        DB::beginTransaction();

        $grade = new Grade();
        $formData = $request->only($grade->getFillable());

        Grade::create($formData);

        DB::commit();

        return redirect()->route('grades.index');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Grade $grade)
    {
        return Inertia::render('HRMS/Grades/Create', [
            'grade' => $grade,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(GradeRequest $request, Grade $grade)
    {
        DB::beginTransaction();

        $formData = $request->only($grade->getFillable());
        $grade->update($formData);

        DB::commit();

        return redirect()->route('grades.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Grade $grade)
    {
        $grade->delete();
        return Inertia::location(route('grades.index'));
    }
}
