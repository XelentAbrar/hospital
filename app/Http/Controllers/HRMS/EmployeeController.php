<?php

namespace App\Http\Controllers\HRMS;

use App\Models\City;
use App\Models\Role;
use App\Models\User;
use Inertia\Inertia;
use App\Models\State;
use App\Models\Country;
use App\Models\UserRole;
use App\Models\HRMS\Grade;
use Illuminate\Http\Request;
use App\Models\HRMS\Employee;
use App\Models\HRMS\Education;
use App\Models\HRMS\Department;
use App\Models\HRMS\Experience;
use App\Models\HRMS\Designation;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use XelentAbrar\HospitalAccounts\Models\Accounts\ChartOfAccount;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\HRMS\EmployeeRequest;


class EmployeeController extends Controller
{
    private $indexFilePath;
    private $createFilePath;

    public function __construct()
    {
        $this->indexFilePath = 'HRMS/Employees/Index';
        $this->createFilePath = 'HRMS/Employees/Create';

        $this->middleware('checkPermission:employees.index')->only('index');
        $this->middleware('checkPermission:employees.create')->only('create', 'store');
        $this->middleware('checkPermission:employees.show')->only('show');
        $this->middleware('checkPermission:employees.edit')->only('edit', 'update');
        $this->middleware('checkPermission:employees.destroy')->only('destroy');
    }

    public function index()
    {
        $employees = Employee::with(['department:id,name', 'designation:id,name'])->orderBy('created_at', 'desc');
        if(isset($_GET['search']) && $_GET['search'] != ''){
            $employees = $employees->where('name', 'LIKE', '%'.$_GET['search'].'%')->orWhere('email', 'LIKE', '%'.$_GET['search'].'%')->orWhere('mobile', 'LIKE', '%'.$_GET['search'].'%');
            $employees = $employees->orWhereHas('department', function($q){
                $q = $q->where('name', 'LIKE', '%'.$_GET['search'].'%');
            });
            $employees = $employees->orWhereHas('designation', function($q){
                $q = $q->where('name', 'LIKE', '%'.$_GET['search'].'%');
            });
        }
        $employees = $employees->paginate(100);
        $employees = $employees->appends(['search' => request('search')]);
        $user = Auth::user();
        $role = UserRole::where('user_id', $user->id)->whereIn('role_id', [1, 7])->first();
        return Inertia::render($this->indexFilePath, [
            'employees' => $employees,
            'role' => $role,
            'filters' => request()->only(['search']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $countries = Country::orderBy('name')->get();
        $departments = Department::orderBy('name')->get();
        $designations = Designation::orderBy('name')->get();
        $employees = Employee::orderBy('name')->get();
        $roles = Role::orderBy('name')->where('name','!=','Super Admin')->get();
        $grades = Grade::orderBy('name')->get();
        if(file_exists(base_path('config/accounts.php'))){
            $coa = ChartOfAccount::select('id','acc_desc')->get();
        }

        return Inertia::render($this->createFilePath, [
            'csrf_token' => csrf_token(),
            'roles' => $roles,
            'countries' => $countries,
            'employees' => $employees,
            'departments' => $departments,
            'designations' => $designations,
            'grades' => $grades,
            'coa' => $coa ?? null,
        ]);
    }

    public function upload(Request $request)
    {
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $originalName = $file->getClientOriginalName();
            $randomChars = substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 3);
            $uniqueFilename = 'emp-' . $randomChars . '-' . $originalName;
            $path = 'uploads/HRMS/employees/temp/' . $uniqueFilename;
            Storage::disk('public')->putFileAs('uploads/HRMS/employees/temp', $file, $uniqueFilename);
            return response()->json(['image' => $uniqueFilename]);
        }
        return '';
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(EmployeeRequest $request)
    {
        $employee = new Employee();
        $employeeData = $request->only($employee->getFillable());
        DB::beginTransaction();
        $employee = Employee::create($employeeData);
        if ($request->login_allowed && !empty($request->password)) {
            // $employeeData['password'] = Hash::make($request->password);
            $user = User::create([
                'email' => $request->email ?? null,
                'name' => $request->name ?? null,
                'password' => Hash::make($request->password),
                'employee_id' => $employee->id ?? null,
            ]);
            if ($user) {
                foreach ($request->roles as $key => $role) {
                    UserRole::create([
                        'user_id' => $user->id,
                        'role_id' => $role,
                    ]);
                }
            }
        }

        if ($request->is_doctor) {
            $employee->doctorCharges()->create($request->only('visiting_charges', 'consulting_charges'));
        }

        $this->saveRelatedEducation($request->education, $employee);
        $this->saveRelatedExperience($request->experience, $employee);

        $this->moveUploadedFile($request->image);

        DB::commit();

        return redirect()->route('employees.index');
    }

    private function saveRelatedEducation($educationData, $employee)
    {
        if (!empty($educationData)) {
            $educations = [];

            foreach ($educationData as $education) {
                if($education['institute']){
                    $educations[] = new Education($education);
                }
            }

            $employee->education()->saveMany($educations);
        }
    }

    private function saveRelatedExperience($experienceData, $employee)
    {
        if (!empty($experienceData)) {
            $experiences = [];

            foreach ($experienceData as $experience) {
                if($experience['company']){
                    $experiences[] = new Experience($experience);
                }
            }

            $employee->experience()->saveMany($experiences);
        }
    }

    private function moveUploadedFile($filename)
    {
        $source = storage_path('app/public/uploads/HRMS/employees/temp/' . $filename);
        $destination = public_path('uploads/employees/' . $filename);

        if (file_exists($source) && $filename) {
            copy($source, $destination);
            unlink($source);
        }
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employee $employee)
    {
        $employee->loadMissing(['user.userRole', 'education', 'experience', 'designation', 'doctorCharges']);
        if (isset($employee->user->userRole)) {
            $employee->roles = $employee->user->userRole->pluck('role_id');
        }
        $countries = Country::orderBy('name')->select('id', 'name')->get();
        $departments = Department::orderBy('name')->select('id', 'name')->get();
        $designations = Designation::orderBy('name')->select('id', 'name', 'is_doctor')->get();
        $roles = Role::orderBy('name')->where('name','!=','Super Admin')->get();
        $grades = Grade::orderBy('name')->get();
        $selectedRoles = null;
        if ($employee->roles) {
            $selectedRoles = Role::whereIn('id', $employee->roles)->get();
        }
        $employees = Employee::orderBy('name')->where('id', '!=', $employee->id)->get();
        $states = [];
        if ($employee->state_id) {
            $states = State::where('country_id', $employee->country_id)->select('id', 'name', 'country_id')->orderBy('name')->get();
        }
        $cities = [];
        if ($employee->city_id) {
            $cities = City::where('state_id', $employee->state_id)->select('id', 'name', 'state_id')->orderBy('name')->get();
        }
        if(file_exists(base_path('config/accounts.php'))){
            $coa = ChartOfAccount::select('id','acc_desc')->get();
        }
        return Inertia::render($this->createFilePath, [
            'employee' => $employee,
            'employees' => $employees,
            'roles' => $roles,
            'grades' => $grades,
            'countries' => $countries,
            'departments' => $departments,
            'designations' => $designations,
            'states' => $states,
            'cities' => $cities,
            'selectedRoles' => $selectedRoles,
            'coa' => $coa ?? null,
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(EmployeeRequest $request, Employee $employee)
    {
        $oldImage = $employee->image;
        $newImage = $request->image;

        if ($oldImage !== $newImage) {
            // Move new image from temp folder to uploads/employees folder
            $this->moveUploadedFile($newImage);

            // Delete old image if it exists
            $oldImagePath = public_path('uploads' . DIRECTORY_SEPARATOR . 'employees' . DIRECTORY_SEPARATOR . $oldImage);
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }

            $employee->image = $newImage;
        }
        DB::beginTransaction();

        $user = User::where('employee_id', $employee->id)->first();
        if ($request->login_allowed) {
            if ($user) {
                $user->update([
                    'email' => $request->email ?? null,
                    'name' => $request->name ?? null,
                ]);
            } else {
                $user = User::create([
                    'email' => $request->email ?? null,
                    'name' => $request->name ?? null,
                    'password' => Hash::make($request->password),
                    'employee_id' => $employee->id ?? null,
                ]);
            }
            if (!empty($request->password)) {
                $user->update([
                    'password' => Hash::make($request->password),
                ]);
            }
            if ($user) {
                if ($user->userRole()) {
                    $user->userRole()->delete();
                }
            }
            foreach ($request->roles as $key => $role) {
                UserRole::create([
                    'user_id' => $user->id,
                    'role_id' => $role,
                ]);
            }
        } else {
            if ($user) {
                if ($user->userRole()) {
                    $user->userRole()->delete();
                }
                $user->delete();
            }
        }

        $employeeData = $request->only($employee->getFillable());
        $employee->update($employeeData);

        if ($request->is_doctor) {
            if (isset($employee->doctorCharges)) {
                $employee->doctorCharges()->update($request->only('visiting_charges', 'consulting_charges'));
            } else {
                $employee->doctorCharges()->create($request->only('visiting_charges', 'consulting_charges'));
            }
        } else {
            $employee->doctorCharges()->delete();
        }

        // Update the education information
        $employee->education()->delete();
        $this->saveRelatedEducation($request->education, $employee);

        // Update the experience information
        $employee->experience()->delete();
        $this->saveRelatedExperience($request->experience, $employee);

        DB::commit();

        return redirect()->route('employees.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        DB::beginTransaction();
        // dd( $employee->user);
        // if ($employee->user()->userRole()) {
        //     $employee->user()->userRole()->delete();
        // }
        // if ($employee->user()) {
        //     $employee->user()->delete();
        // }
        if ($employee->user) {
            if ($employee->user->userRole) {
                foreach ($employee->user->userRole as $userRole) {
                    $userRole->delete(); // Delete each userRole
                }
            }
            $employee->user->delete();
        }

        if ($employee->education()) {
            $employee->education()->delete();
        }
        if ($employee->experience()) {
            $employee->experience()->delete();
        }
        $oldImage = $employee->image;
        // Delete old image if it exists
        $oldImagePath = public_path('uploads' . DIRECTORY_SEPARATOR . 'employees' . DIRECTORY_SEPARATOR . $oldImage);
        if (file_exists($oldImagePath)) {
            unlink($oldImagePath);
        }
        $employee->delete();

        DB::commit();

        return Inertia::location(route('employees.index'));
    }
}
