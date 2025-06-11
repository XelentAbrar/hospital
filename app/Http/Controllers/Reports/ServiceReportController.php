<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\HRMS\Department;
use App\Models\OPD\Service;
use Inertia\Inertia;

class ServiceReportController extends Controller
{
    public function indexByType()
    {
        $services = Service::orderBy('created_at', 'desc');
        if (request('type') != '') {
            $services = $services->where('type', request('type'));
        }
        $services = $services->get();
        $departments = Department::select('id', 'name')->orderBy('name')->get();
        return Inertia::render('Reports/ServiceReports/Index', [
            'services' => $services,
            'departments' => $departments,
            'type' => request('type'),
        ]);
    }

    public function serviceBillingIndex()
    {
        $services = Service::orderBy('created_at', 'desc');
        if (request('date') != '') {
            $services = $services->whereRaw('DATE(created_at) = ?', [request('date')]);
        }
        $services = $services->get();
        $departments = Department::select('id', 'name')->orderBy('name')->get();
        return Inertia::render('Reports/ServiceReports/BillingIndex', [
            'services' => $services,
            'departments' => $departments,
            'type' => request('type'),
        ]);
    }
}
