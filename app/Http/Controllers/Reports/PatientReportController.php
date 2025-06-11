<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\OPD\Patient;
use Inertia\Inertia;

class PatientReportController extends Controller
{
    public function index()
    {
        $patients = Patient::with(['country:id,name', 'state:id,name', 'city:id,name'])->orderBy('created_at', 'desc');
        if (request('date') != '') {
            $patients = $patients->whereRaw('DATE(created_at) = ?', [request('date')]);
        }
        $patients = $patients->get();
        return Inertia::render('Reports/PatientReports/Index', [
            'patients' => $patients,
        ]);
    }
}
