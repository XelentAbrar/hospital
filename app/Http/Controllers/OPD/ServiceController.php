<?php

namespace App\Http\Controllers\OPD;

use Inertia\Inertia;
use App\Models\UserRole;
use App\Models\OPD\Service;
use App\Models\OPD\Category;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\OPD\ServiceRequest;
use XelentAbrar\HospitalAccounts\Models\Accounts\ChartOfAccount;

class ServiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkPermission:services.index')->only('index');
        $this->middleware('checkPermission:services.create')->only('create', 'store');
        $this->middleware('checkPermission:services.show')->only('show');
        $this->middleware('checkPermission:services.edit')->only('edit', 'update');
        $this->middleware('checkPermission:services.destroy')->only('destroy');
    }
    public function index()
    {
        $services = Service::with('category')->orderBy('created_at', 'desc');
        if(isset($_GET['search']) && $_GET['search'] != ''){
            $services = $services->where('name', 'LIKE', '%'.$_GET['search'].'%')->orWhere('type', 'LIKE', '%'.$_GET['search'].'%');
            $services = $services->orWhereHas('category', function($q){
                $q = $q->where('name', 'LIKE', '%'.$_GET['search'].'%');
            });
        }
        $services = $services->paginate(100);
        $user = Auth::user();
        $role = UserRole::where('user_id', $user->id)->whereIn('role_id', [1, 7])->first();
        $services = $services->appends(['search' => request('search')]);
        return Inertia::render('OPD/Services/Index', [
            'services' => $services,
            'role' => $role,
            'filters' => request()->only(['search']),
        ]);
    }

    public function create()
    {
        if(file_exists(base_path('config/accounts.php'))){
            $coa = ChartOfAccount::select('id','acc_desc')->get();
        }
        $categories = Category::whereStatus('active')->orderBy('name')->get();
        return Inertia::render('OPD/Services/Create', [
            'categories' => $categories,
            'coa' => $coa ?? null,
        ]);
    }

    public function store(ServiceRequest $request)
    {
        $service = new Service();
        $employeeDeductionData = $request->only($service->getFillable());

        Service::create($employeeDeductionData);

        return redirect()->route('services.index');
    }


    public function edit(Service $service)
    {
        if(file_exists(base_path('config/accounts.php'))){
            $coa = ChartOfAccount::select('id','acc_desc')->get();
        }
        $categories = Category::whereStatus('active')->orderBy('name')->get();
        return Inertia::render('OPD/Services/Create', [
            'service' => $service,
            'categories' => $categories,
            'coa' => $coa ?? null,
        ]);
    }


    public function update(ServiceRequest $request, Service $service)
    {
    // return $request;
        $employeeDeductionData = $request->only($service->getFillable());
        $service->update($employeeDeductionData);

        return redirect()->route('services.index')->with('message', 'Service updated successfully.');
    }


    // public function destroy($id)
    // {
    //     $service = Service::findOrFail($id);
    //     $service->delete();

    //     return redirect()->route('services.index')->with('success', 'Service deleted successfully.');
    // }
    public function destroy(Service $service)
    {
        // Collect related admission and appointment record IDs
        $admissionIds = \DB::table('admission_details')
            ->where('service_id', $service->id)
            ->pluck('admission_id')
            ->unique()
            ->values();

        $appointmentIds = \DB::table('appointment_details')
            ->where('service_id', $service->id)
            ->pluck('appointment_id')
            ->unique()
            ->values();

        // If service is used in either, prevent deletion
        if ($admissionIds->isNotEmpty() || $appointmentIds->isNotEmpty()) {
            $message = "Cannot delete Service ID #{$service->id}. It is being used in ";

            if ($admissionIds->isNotEmpty() && $appointmentIds->isNotEmpty()) {
                $message .= 'admission(s): ' . $admissionIds->join(', ') . ' and appointment(s): ' . $appointmentIds->join(', ');
            } elseif ($admissionIds->isNotEmpty()) {
                $message .= 'admission(s): ' . $admissionIds->join(', ');
            } else {
                $message .= 'appointment(s): ' . $appointmentIds->join(', ');
            }

            return redirect()->back()->withErrors(['message' => $message]);
        }

        // No links found — delete service
        $service->delete();

        return redirect()->route('services.index')->with('success', "Service ID #{$service->id} deleted successfully.");
    }

}
