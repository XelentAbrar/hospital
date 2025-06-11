<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\IPD\Admission;
use Inertia\Inertia;

class IndoorPatientReportController extends Controller
{
    public function indexByAdmissionDate()
    {
        $admissions = Admission::with(['patient:id,name', 'roomBed:id,bed_number'])->orderBy('created_at', 'desc');
        if (request('date') != '') {
            $admissions = $admissions->where('admission_date', request('date'));
        }
        $admissions = $admissions->get();
        return Inertia::render('Reports/IndoorPatientReports/IndexByAdmissionDate', [
            'admissions' => $admissions,
        ]);
    }

    public function indexByDischargeDate()
    {
        $admissions = Admission::with(['patient:id,name', 'roomBed:id,bed_number'])->orderBy('created_at', 'desc');
        if (request('date') != '') {
            $admissions = $admissions->where('discharge_date', request('date'));
        }
        $admissions = $admissions->get();
        return Inertia::render('Reports/IndoorPatientReports/IndexByDischargeDate', [
            'admissions' => $admissions,
        ]);
    }

    public function indexByFileNo()
    {
        $admissions = Admission::with(['patient:id,name', 'roomBed:id,bed_number'])->orderBy('created_at', 'desc');
        if (request('file_no') != '') {
            $admissions = $admissions->where('file_no', request('file_no'));
        }
        $admissions = $admissions->get();
        return Inertia::render('Reports/IndoorPatientReports/IndexByFileNo', [
            'admissions' => $admissions,
        ]);
    }

    public function indoorbillingIndex()
    {
        $admissions = Admission::with(['patient:id,name', 'roomBed:id,bed_number'])->orderBy('created_at', 'desc');
        if (request('file_no') != '') {
            $admissions = $admissions->where('file_no', request('file_no'));
        }
        $admissions = $admissions->get();
        return Inertia::render('Reports/IndoorPatientReports/IndoorBillingIndex', [
            'admissions' => $admissions,
        ]);
    }
}
