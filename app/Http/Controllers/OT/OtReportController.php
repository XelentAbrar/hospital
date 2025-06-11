<?php

namespace App\Http\Controllers\OT;

use App\Http\Controllers\Controller;
use App\Models\IPD\Admission;
use App\Models\OT\Operation;
use Illuminate\Http\Request;
use Inertia\Inertia;
class OtReportController extends Controller
{
    public function AnesthesiaDoctorReport(Request $request)
    {
        $from_date = date('Y-m-d');
        $to_date = date('Y-m-d');

        if (isset($_GET['from_date']) && $_GET['from_date'] != '') {
            $from_date = $_GET['from_date'];
        }

        if (isset($_GET['to_date']) && $_GET['to_date'] != '') {
            $to_date = $_GET['to_date'];
        }

        $query = Operation::whereBetween('operation_date', [
            $from_date,
            date('Y-m-d H:i', strtotime($to_date . ' +1 day'))
        ])
        ->whereNotNull('anasthetic_doc_name')
        ->orderBy('operation_date', 'desc');
        if ($request->has('search_term') && $request->search_term != '') {
            $search_term = '%' . $request->search_term . '%';
            $query->where(function ($query) use ($search_term) {
                $query->where('anasthetic_doc_name', 'like', $search_term)
                      ->orWhere('anasthesia_type', 'like', $search_term)
                      ->orWhere('patient_name', 'like', $search_term);
            });
        }

        $operations = $query->get();

        return Inertia::render('OT/Reports/AnesthesiaDoctorReport', [
            'reports' => $operations,
            'from_date' => $from_date,
            'to_date' => $to_date,
        ]);
    }

    public function ProcedureReport(Request $request)
    {
        $from_date = date('Y-m-d');
        $to_date = date('Y-m-d');

        if ($request->has('from_date') && $request->from_date != '') {
            $from_date = $request->from_date;
        }

        if ($request->has('to_date') && $request->to_date != '') {
            $to_date = $request->to_date;
        }

        $query = Operation::query();

        $query->whereBetween('operation_date', [
            $from_date,
            date('Y-m-d H:i', strtotime($to_date . ' +1 day'))
        ]);

        if ($request->has('search_term') && $request->search_term != '') {
            $search_term = '%' . $request->search_term . '%';

            $query->where(function ($query) use ($search_term) {
                $query->where('procedure_type', 'like', $search_term)
                      ->orWhere('patient_name', 'like', $search_term)
                      ->orWhere('surgeon_name', 'like', $search_term)
                      ->orWhere('file_no', 'like', $search_term)
                      ->orWhere('ot_number', 'like', $search_term)
                      ->orWhere('advance_amount', 'like', $search_term)
                      ->orWhere('received_amount', 'like', $search_term);
            });
        }

        $operations = $query
        ->orderBy('operation_date', 'desc')
        ->get();

        return Inertia::render('OT/Reports/ProcedureReport', [
            'reports' => $operations,
            'from_date' => $from_date,
            'to_date' => $to_date,
        ]);
    }
    public function PatientList()
{
    $reports = Admission::with(['department', 'careoff', 'operation'])
        ->whereHas('operation')
        ->orderBy('admission_date', 'desc');

    $from_date = date('Y-m-d H:i', strtotime('today 00:00'));
    $to_date = date('Y-m-d H:i', strtotime('today 23:59'));

    if (!empty($_GET['from_date'])) {
        $from_date = date('Y-m-d H:i', strtotime($_GET['from_date']));
    }
    if (!empty($_GET['to_date'])) {
        $to_date = date('Y-m-d H:i', strtotime($_GET['to_date']));
    }

    $from_date_only = date('Y-m-d', strtotime($from_date));
    $from_time_only = date('H:i', strtotime($from_date));
    $to_date_only = date('Y-m-d', strtotime($to_date));
    $to_time_only = date('H:i', strtotime($to_date));

    $reports = $reports->whereBetween('admission_date', [$from_date_only, $to_date_only])
        ->whereBetween('admission_time', [$from_time_only, $to_time_only])
        ->get();

    return Inertia::render('OT/Reports/PatientList', [
        'reports' => $reports,
        'from_date' => $from_date,
        'to_date' => $to_date,
    ]);
}

}
