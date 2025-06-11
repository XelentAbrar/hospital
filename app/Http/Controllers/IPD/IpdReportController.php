<?php

namespace App\Http\Controllers\IPD;

use App\Http\Controllers\Controller;
use App\Models\HRMS\Department;
use App\Models\HRMS\Employee;
use App\Models\IPD\Admission;
use App\Models\OPD\Service;
use DateTime;
use Inertia\Inertia;

class IpdReportController extends Controller
{
    public function PatientList()
{
    $reports = Admission::orderBy('admission_date');
    $from_date = date('Y-m-d H:i', strtotime('today 00:00'));
    $to_date = date('Y-m-d H:i', strtotime('today 23:59'));

    if (isset($_GET['from_date']) && $_GET['from_date'] != '') {
        $from_date = DateTime::createFromFormat('d-m-Y H:i', $_GET['from_date']);
        if (!$from_date) {
            throw new \Exception('Invalid from_date format. Expected d-m-Y H:i.');
        }
        $from_date = $from_date->format('Y-m-d H:i');
    } else {
        $from_date = date('Y-m-d H:i', strtotime('today 00:00'));
    }

    if (isset($_GET['to_date']) && $_GET['to_date'] != '') {
        $to_date = DateTime::createFromFormat('d-m-Y H:i', $_GET['to_date']);
        if (!$to_date) {
            throw new \Exception('Invalid to_date format. Expected d-m-Y H:i.');
        }
        $to_date = $to_date->format('Y-m-d H:i');
    } else {
        $to_date = date('Y-m-d H:i', strtotime('today 23:59'));
    }

    $from_date_only = date('Y-m-d', strtotime($from_date));
    $from_time_only = date('H:i', strtotime($from_date));
    $to_date_only = date('Y-m-d', strtotime($to_date));
    $to_time_only = date('H:i', strtotime($to_date));

    $reports = $reports->with('department','careoff')
    ->where('cancel', '!=', '1')
    ->where(function($q) use ($from_date_only, $to_date_only, $from_time_only, $to_time_only) {
        $q->whereBetween('admission_date', [$from_date_only, $to_date_only])
          ->whereBetween('admission_time', [$from_time_only, $to_time_only]);
    })->orWhere(function($q) use ($from_date_only, $to_date_only, $from_time_only, $to_time_only) {
        $q->whereBetween('discharge_date', [$from_date_only, $to_date_only])
          ->whereBetween('discharge_time', [$from_time_only, $to_time_only]);
    });

    $reports = $reports->get();

    return Inertia::render('IPD/Reports/PatientList', [
        'reports' => $reports,
        'from_date' => date('d-m-Y H:i', strtotime($from_date)),
        'to_date' => date('d-m-Y H:i', strtotime($to_date)),
    ]);
}
 public function admitPatientList()
{
    $reports = Admission::orderBy('admission_date');
    $from_date = date('Y-m-d H:i', strtotime('today 00:00'));
    $to_date = date('Y-m-d H:i', strtotime('today 23:59'));

    if (isset($_GET['from_date']) && $_GET['from_date'] != '') {
        $from_date = DateTime::createFromFormat('d-m-Y H:i', $_GET['from_date']);
        if (!$from_date) {
            throw new \Exception('Invalid from_date format. Expected d-m-Y H:i.');
        }
        $from_date = $from_date->format('Y-m-d H:i');
    } else {
        $from_date = date('Y-m-d H:i', strtotime('today 00:00'));
    }

    if (isset($_GET['to_date']) && $_GET['to_date'] != '') {
        $to_date = DateTime::createFromFormat('d-m-Y H:i', $_GET['to_date']);
        if (!$to_date) {
            throw new \Exception('Invalid to_date format. Expected d-m-Y H:i.');
        }
        $to_date = $to_date->format('Y-m-d H:i');
    } else {
        $to_date = date('Y-m-d H:i', strtotime('today 23:59'));
    }

    $from_date_only = date('Y-m-d', strtotime($from_date));
    $from_time_only = date('H:i', strtotime($from_date));
    $to_date_only = date('Y-m-d', strtotime($to_date));
    $to_time_only = date('H:i', strtotime($to_date));

    $reports = $reports->with('department')
    ->where('status', '!=', 'Complete')
    ->where('cancel', '!=', '1')
    ->where(function($q) use ($from_date_only, $to_date_only, $from_time_only, $to_time_only) {
        $q->whereBetween('admission_date', [$from_date_only, $to_date_only])
          ->whereBetween('admission_time', [$from_time_only, $to_time_only]);
    });

    $reports = $reports->get();

    return Inertia::render('IPD/Reports/AdmitPatientList', [
        'reports' => $reports,
        'from_date' => date('d-m-Y H:i', strtotime($from_date)),
        'to_date' => date('d-m-Y H:i', strtotime($to_date)),
    ]);
}



    public function admissionAdvanceReferedBy()
    {
        $refered_bys = Admission::whereNotNull('refered_by')->groupBy('refered_by')->select('refered_by')->get();

        $reports = Admission::orderBy('name');
        $from_date = date('Y-m-d H:i',strtotime('today 00:00'));
        $to_date = date('Y-m-d H:i',strtotime('today 23:59'));
        if (isset($_GET['from_date']) && $_GET['from_date'] != '') {
            $from_date = DateTime::createFromFormat('d-m-Y H:i', $_GET['from_date']);
            if (!$from_date) {
                throw new \Exception('Invalid from_date format. Expected d-m-Y H:i.');
            }
            $from_date = $from_date->format('Y-m-d H:i');
        } else {
            $from_date = date('Y-m-d H:i', strtotime('today 00:00'));
        }

        if (isset($_GET['to_date']) && $_GET['to_date'] != '') {
            $to_date = DateTime::createFromFormat('d-m-Y H:i', $_GET['to_date']);
            if (!$to_date) {
                throw new \Exception('Invalid to_date format. Expected d-m-Y H:i.');
            }
            $to_date = $to_date->format('Y-m-d H:i');
        } else {
            $to_date = date('Y-m-d H:i', strtotime('today 23:59'));
        }
        $refered_by = null;

        if(isset($_GET['refered_by']) && $_GET['refered_by'] != ''){
            $refered_by = $_GET['refered_by'];
            $reports = $reports->where('refered_by', $_GET['refered_by']);
        }
        $from_date_only = date('Y-m-d', strtotime($from_date));
        $from_time_only = date('H:i', strtotime($from_date));
        $to_date_only = date('Y-m-d', strtotime($to_date));
        $to_time_only = date('H:i', strtotime($to_date));
        // $reports = $reports->where(function($q){
        //     $q->whereNotNull('advance_amount')->orWhere('advance_amount','>','0');
        // })->where('status', 'Pending')->with('department')->whereBetween('admission_date', [$from_date, date('Y-m-d H:i', strtotime($to_date . ' +1 day'))]);
        $reports = $reports ->where('status', 'Pending')
        ->with('department')->where(function($q) {
            $q->whereNotNull('advance_amount')
                ->orWhere('advance_amount', '>', 0);
        })

        ->where(function ($query) use ($from_date_only, $to_date_only, $from_time_only, $to_time_only) {
            $query->whereBetween('admission_date', [$from_date_only, $to_date_only])
                ->whereBetween('admission_time', [$from_time_only, $to_time_only]);
        });
        $reports = $reports->get();



        return Inertia::render('IPD/Reports/AdmissionAdvanceReferedBy', [
            'reports' => $reports,
            'from_date' => date('d-m-Y H:i', strtotime($from_date)),
            'to_date' => date('d-m-Y H:i', strtotime($to_date)),
            'refered_bys' => $refered_bys,
            'refered_by' => $refered_by,
        ]);
    }


    public function admissionAdvanceDeptBy()
    {
        $departments = Department::whereType('payroll')->select('id', 'name')->orderBy('name')->get();

        $reports = Admission::orderBy('name');
        $department_id = null;
        $from_date = date('Y-m-d H:i',strtotime('today 00:00'));
        $to_date = date('Y-m-d H:i',strtotime('today 23:59'));
        if (isset($_GET['from_date']) && $_GET['from_date'] != '') {
            $from_date = DateTime::createFromFormat('d-m-Y H:i', $_GET['from_date']);
            if (!$from_date) {
                throw new \Exception('Invalid from_date format. Expected d-m-Y H:i.');
            }
            $from_date = $from_date->format('Y-m-d H:i');
        } else {
            $from_date = date('Y-m-d H:i', strtotime('today 00:00'));
        }

        if (isset($_GET['to_date']) && $_GET['to_date'] != '') {
            $to_date = DateTime::createFromFormat('d-m-Y H:i', $_GET['to_date']);
            if (!$to_date) {
                throw new \Exception('Invalid to_date format. Expected d-m-Y H:i.');
            }
            $to_date = $to_date->format('Y-m-d H:i');
        } else {
            $to_date = date('Y-m-d H:i', strtotime('today 23:59'));
        }
        if(isset($_GET['department_id']) && $_GET['department_id'] != ''){
            $department_id = $_GET['department_id'];
            $reports = $reports->where('department_id', $_GET['department_id']);
        }
        $from_date_only = date('Y-m-d', strtotime($from_date));
        $from_time_only = date('H:i', strtotime($from_date));
        $to_date_only = date('Y-m-d', strtotime($to_date));
        $to_time_only = date('H:i', strtotime($to_date));
        // $reports = $reports->where(function($q){
        //     $q->whereNotNull('advance_amount')->orWhere('advance_amount','>','0');
        // })->where('status', 'Pending')->with('department')->whereBetween('admission_date', [$from_date, date('Y-m-d H:i', strtotime($to_date . ' +1 day'))]);
        $reports = $reports ->where('status', 'Pending')
        ->with('department')->where(function($q) {
            $q->whereNotNull('advance_amount')
                ->orWhere('advance_amount', '>', 0);
        })

        ->where(function ($query) use ($from_date_only, $to_date_only, $from_time_only, $to_time_only) {
            $query->whereBetween('admission_date', [$from_date_only, $to_date_only])
                ->whereBetween('admission_time', [$from_time_only, $to_time_only]);
        });
        $reports = $reports->get();



        return Inertia::render('IPD/Reports/AdmissionAdvanceDeptBy', [
            'reports' => $reports,
            'from_date' => date('d-m-Y H:i', strtotime($from_date)),
            'to_date' => date('d-m-Y H:i', strtotime($to_date)),
            'departments' => $departments,
            'department_id' => $department_id,
        ]);
    }

    public function admissionAdvanceServiceBy()
    {
        $services = Service::select('id', 'name', 'charges')->whereType('IPD')->orderBy('name')->get();
        $doctors = Employee::select('id', 'name')->orderBy('name')->get();
        // return $doctors;
        $reports = Admission::orderBy('name');
        $service_id = null;
        $doctor_id = null;
        $from_date = date('Y-m-d H:i',strtotime('today 00:00'));
        $to_date = date('Y-m-d H:i',strtotime('today 23:59'));
        if (isset($_GET['from_date']) && $_GET['from_date'] != '') {
            $from_date = DateTime::createFromFormat('d-m-Y H:i', $_GET['from_date']);
            if (!$from_date) {
                throw new \Exception('Invalid from_date format. Expected d-m-Y H:i.');
            }
            $from_date = $from_date->format('Y-m-d H:i');
        } else {
            $from_date = date('Y-m-d H:i', strtotime('today 00:00'));
        }

        if (isset($_GET['to_date']) && $_GET['to_date'] != '') {
            $to_date = DateTime::createFromFormat('d-m-Y H:i', $_GET['to_date']);
            if (!$to_date) {
                throw new \Exception('Invalid to_date format. Expected d-m-Y H:i.');
            }
            $to_date = $to_date->format('Y-m-d H:i');
        } else {
            $to_date = date('Y-m-d H:i', strtotime('today 23:59'));
        }
        $from_date_only = date('Y-m-d', strtotime($from_date));
        $from_time_only = date('H:i', strtotime($from_date));
        $to_date_only = date('Y-m-d', strtotime($to_date));
        $to_time_only = date('H:i', strtotime($to_date));
        if(isset($_GET['service_id']) && $_GET['service_id'] != ''){
            $service_id = $_GET['service_id'];
            $reports = $reports->whereHas('details',  function($q) use($service_id){
                $q->where('service_id', $service_id);
            });
        }
        if(isset($_GET['doctor_id']) && $_GET['doctor_id'] != '') {
            $doctor_id = $_GET['doctor_id'];
            $reports = $reports->whereHas('details', function($q) use($doctor_id) {
                $q->where('doctor_id', $doctor_id);
            });
        }
        // $reports = $reports->where(function($q){
        //     $q->whereNotNull('advance_amount')->orWhere('advance_amount','>','0');
        // })->where('status', 'Pending')->with(['department','details.service'])->whereBetween('admission_date', [$from_date, date('Y-m-d H:i', strtotime($to_date . ' +1 day'))]);
        //
        $reports = $reports ->where('status', 'Pending')
        ->where(function($q) {
            $q->whereNotNull('advance_amount')
                ->orWhere('advance_amount', '>', 0);
        })

        ->where(function ($query) use ($from_date_only, $to_date_only, $from_time_only, $to_time_only) {
            $query->whereBetween('admission_date', [$from_date_only, $to_date_only])
                ->whereBetween('admission_time', [$from_time_only, $to_time_only]);
        });
        $reports = $reports->with([
            'department',
            'details.service',
            'details.doctor' => function($query) {
               $query->select('id', 'name');
            }
        ]);
        $reports = $reports->get();

        return Inertia::render('IPD/Reports/AdmissionAdvanceServiceBy', [
            'reports' => $reports,
            'from_date' => date('d-m-Y H:i', strtotime($from_date)),
            'to_date' => date('d-m-Y H:i', strtotime($to_date)),
            'services' => $services,
            'service_id' => $service_id,
            'doctor_id' => $doctor_id,
            'doctors' => $doctors,
        ]);
    }



    public function indoorBillingDetail()
    {
        $reports = Admission::orderBy('name')->where('status', 'Complete');
        $from_date = date('Y-m-d H:i',strtotime('today 00:00'));
        $to_date = date('Y-m-d H:i',strtotime('today 23:59'));
        if (isset($_GET['from_date']) && $_GET['from_date'] != '') {
            $from_date = DateTime::createFromFormat('d-m-Y H:i', $_GET['from_date']);
            if (!$from_date) {
                throw new \Exception('Invalid from_date format. Expected d-m-Y H:i.');
            }
            $from_date = $from_date->format('Y-m-d H:i');
        } else {
            $from_date = date('Y-m-d H:i', strtotime('today 00:00'));
        }

        if (isset($_GET['to_date']) && $_GET['to_date'] != '') {
            $to_date = DateTime::createFromFormat('d-m-Y H:i', $_GET['to_date']);
            if (!$to_date) {
                throw new \Exception('Invalid to_date format. Expected d-m-Y H:i.');
            }
            $to_date = $to_date->format('Y-m-d H:i');
        } else {
            $to_date = date('Y-m-d H:i', strtotime('today 23:59'));
        }
        $from_date_only = date('Y-m-d', strtotime($from_date));
        $from_time_only = date('H:i', strtotime($from_date));
        $to_date_only = date('Y-m-d', strtotime($to_date));
        $to_time_only = date('H:i', strtotime($to_date));
        // $reports = $reports->with(['department','careoff'])->whereBetween('admission_date', [$from_date, date('Y-m-d H:i', strtotime($to_date . ' +1 day'))]);
        $reports = $reports->with(['department', 'careoff','details.doctor'])->where(function($q) use ($from_date_only, $to_date_only, $from_time_only, $to_time_only) {
            $q->whereBetween('discharge_date', [$from_date_only, $to_date_only])
              ->whereBetween('discharge_time', [$from_time_only, $to_time_only]);
        });

        $reports = $reports->get();

        return Inertia::render('IPD/Reports/IndoorBillingDetail', [
            'reports' => $reports,
            'from_date' => date('d-m-Y H:i', strtotime($from_date)),
            'to_date' => date('d-m-Y H:i', strtotime($to_date)),
        ]);
    }

    public function indoorBillingByCareoff()
    {
        $reports = Admission::orderBy('name');
        $from_date = date('Y-m-d H:i',strtotime('today 00:00'));
        $to_date = date('Y-m-d H:i',strtotime('today 23:59'));
        if (isset($_GET['from_date']) && $_GET['from_date'] != '') {
            $from_date = DateTime::createFromFormat('d-m-Y H:i', $_GET['from_date']);
            if (!$from_date) {
                throw new \Exception('Invalid from_date format. Expected d-m-Y H:i.');
            }
            $from_date = $from_date->format('Y-m-d H:i');
        } else {
            $from_date = date('Y-m-d H:i', strtotime('today 00:00'));
        }

        if (isset($_GET['to_date']) && $_GET['to_date'] != '') {
            $to_date = DateTime::createFromFormat('d-m-Y H:i', $_GET['to_date']);
            if (!$to_date) {
                throw new \Exception('Invalid to_date format. Expected d-m-Y H:i.');
            }
            $to_date = $to_date->format('Y-m-d H:i');
        } else {
            $to_date = date('Y-m-d H:i', strtotime('today 23:59'));
        }
        $from_date_only = date('Y-m-d', strtotime($from_date));
        $from_time_only = date('H:i', strtotime($from_date));
        $to_date_only = date('Y-m-d', strtotime($to_date));
        $to_time_only = date('H:i', strtotime($to_date));
        // $reports = $reports->whereNotNull('careoff_id')->with(['department','careoff'])->whereBetween('admission_date', [$from_date, date('Y-m-d H:i', strtotime($to_date . ' +1 day'))]);
        $reports = $reports->whereNotNull('careoff_id')
        ->with(['department', 'careoff'])
        ->where(function($q) use ($from_date_only, $to_date_only, $from_time_only, $to_time_only, $from_date, $to_date) {
            $q->whereBetween('discharge_date', [$from_date_only, $to_date_only])
              ->whereBetween('discharge_time', [$from_time_only, $to_time_only]);
        });
        $reports = $reports->get();



        return Inertia::render('IPD/Reports/IndoorBillingByCareoff', [
            'reports' => $reports,
            'from_date' => date('d-m-Y H:i', strtotime($from_date)),
            'to_date' => date('d-m-Y H:i', strtotime($to_date)),
        ]);
    }
    public function indoorBillingByCareoffAuthority()
    {
        $reports = Admission::orderBy('name');
        $from_date = date('Y-m-d H:i',strtotime('today 00:00'));
        $to_date = date('Y-m-d H:i',strtotime('today 23:59'));
        if (isset($_GET['from_date']) && $_GET['from_date'] != '') {
            $from_date = DateTime::createFromFormat('d-m-Y H:i', $_GET['from_date']);
            if (!$from_date) {
                throw new \Exception('Invalid from_date format. Expected d-m-Y H:i.');
            }
            $from_date = $from_date->format('Y-m-d H:i');
        } else {
            $from_date = date('Y-m-d H:i', strtotime('today 00:00'));
        }

        if (isset($_GET['to_date']) && $_GET['to_date'] != '') {
            $to_date = DateTime::createFromFormat('d-m-Y H:i', $_GET['to_date']);
            if (!$to_date) {
                throw new \Exception('Invalid to_date format. Expected d-m-Y H:i.');
            }
            $to_date = $to_date->format('Y-m-d H:i');
        } else {
            $to_date = date('Y-m-d H:i', strtotime('today 23:59'));
        }
        $from_date_only = date('Y-m-d', strtotime($from_date));
        $from_time_only = date('H:i', strtotime($from_date));
        $to_date_only = date('Y-m-d', strtotime($to_date));
        $to_time_only = date('H:i', strtotime($to_date));
        // $reports = $reports->whereNotNull('welfare')->whereNotNull('careoff_id')->with(['department','careoff'])->whereBetween('admission_date', [$from_date, date('Y-m-d H:i', strtotime($to_date . ' +1 day'))]);
        $reports = $reports->whereNotNull('welfare')->whereNotNull('careoff_id')->with(['department','careoff']) ->where(function($q) use ($from_date_only, $to_date_only, $from_time_only, $to_time_only, $from_date, $to_date) {
            $q->whereBetween('admission_date', [$from_date_only, $to_date_only])
              ->whereBetween('admission_time', [$from_time_only, $to_time_only]);
        });
        $reports = $reports->get();

        return Inertia::render('IPD/Reports/IndoorBillingByCareoffAuthority', [
            'reports' => $reports,
            'from_date' => date('d-m-Y H:i', strtotime($from_date)),
            'to_date' => date('d-m-Y H:i', strtotime($to_date)),
        ]);
    }


    // public function IndoorBillingDetailByDoctor()
    // {
    //     $doctors = Employee::with('doctorCharges')->select('id', 'name')->whereHas('designation', function ($q) {
    //         $q->where('is_doctor', 1);
    //     })->orderBy('name')->get();

    //     $reports = Admission::orderBy('name');
    //     $doctor_id = null;
    //     $from_date = date('Y-m-d H:i',strtotime('today 00:00'));
    //     $to_date = date('Y-m-d H:i',strtotime('today 23:59'));
    //     if (isset($_GET['from_date']) && $_GET['from_date'] != '') {
    //         $from_date = date('Y-m-d H:i', strtotime($_GET['from_date']));
    //     }
    //     if (isset($_GET['to_date']) && $_GET['to_date'] != '') {
    //         $to_date = date('Y-m-d H:i', strtotime($_GET['to_date']));
    //     }
    //     $from_date_only = date('Y-m-d', strtotime($from_date));
    //     $from_time_only = date('H:i', strtotime($from_date));
    //     $to_date_only = date('Y-m-d', strtotime($to_date));
    //     $to_time_only = date('H:i', strtotime($to_date));
    //     if(isset($_GET['doctor_id']) && $_GET['doctor_id'] != ''){
    //         $doctor_id = $_GET['doctor_id'];
    //         $reports = $reports->whereHas('details',  function($q) use($doctor_id){
    //             $q->where('doctor_id', $doctor_id);
    //         });
    //     }
    //     // $reports = $reports->whereStatus('Complete')->with(['department','details'])->whereBetween('admission_date', [$from_date, date('Y-m-d H:i', strtotime($to_date . ' +1 day'))]);
    //     $reports = $reports->whereStatus('Complete')->with(['department','details']) ->where(function($q) use ($from_date_only, $to_date_only, $from_time_only, $to_time_only, $from_date, $to_date) {
    //         $q->whereBetween('admission_date', [$from_date_only, $to_date_only])
    //           ->whereBetween('admission_time', [$from_time_only, $to_time_only]);
    //     });
    //     $reports = $reports->get();



    //     return Inertia::render('IPD/Reports/IndoorBillingDetailByDoctor', [
    //         'reports' => $reports,
    //         'from_date' => $from_date,
    //         'to_date' => $to_date,
    //         'doctor_id' => $doctor_id,
    //         'doctors' => $doctors,
    //     ]);
    // }
    public function IndoorBillingDetailByDoctor()
    {
        $doctors = Employee::with('doctorCharges')->select('id', 'name')->whereHas('designation', function ($q) {
            $q->where('is_doctor', 1);
        })->orderBy('name')->get();

        $reports = Admission::orderBy('discharge_date');
        $doctor_id = null;
        $from_date = date('Y-m-d H:i', strtotime('today 00:00'));
        $to_date = date('Y-m-d H:i', strtotime('today 23:59'));

        if (isset($_GET['from_date']) && $_GET['from_date'] != '') {
            $from_date = DateTime::createFromFormat('d-m-Y H:i', $_GET['from_date']);
            if (!$from_date) {
                throw new \Exception('Invalid from_date format. Expected d-m-Y H:i.');
            }
            $from_date = $from_date->format('Y-m-d H:i');
        } else {
            $from_date = date('Y-m-d H:i', strtotime('today 00:00'));
        }

        if (isset($_GET['to_date']) && $_GET['to_date'] != '') {
            $to_date = DateTime::createFromFormat('d-m-Y H:i', $_GET['to_date']);
            if (!$to_date) {
                throw new \Exception('Invalid to_date format. Expected d-m-Y H:i.');
            }
            $to_date = $to_date->format('Y-m-d H:i');
        } else {
            $to_date = date('Y-m-d H:i', strtotime('today 23:59'));
        }

        $from_date_only = date('Y-m-d', strtotime($from_date));
        $from_time_only = date('H:i', strtotime($from_date));
        $to_date_only = date('Y-m-d', strtotime($to_date));
        $to_time_only = date('H:i', strtotime($to_date));

        // Check if doctor is selected
        if (isset($_GET['doctor_id']) && $_GET['doctor_id'] != '') {
            $doctor_id = $_GET['doctor_id'];

            // Apply filtering by doctor only if doctor_id is present
            $reports = $reports->whereHas('details', function ($q) use ($doctor_id) {
                $q->where('doctor_id', $doctor_id);
            });
        }

        // Apply status Complete, admission, and discharge date/time filters
        $reports = $reports->where('status','Complete')
            ->with(['department', 'details'])
            ->where(function ($q) use ($from_date_only, $to_date_only, $from_time_only, $to_time_only) {
                $q->whereBetween('discharge_date', [$from_date_only, $to_date_only])
                  ->whereBetween('discharge_time', [$from_time_only, $to_time_only]);
            });
            // dd($reports);
            // ->where(function ($q) use ($from_date_only, $to_date_only, $from_time_only, $to_time_only) {
            //     $q->whereBetween('discharge_date', [$from_date_only, $to_date_only])
            //       ->whereBetween('discharge_time', [$from_time_only, $to_time_only]);
            // });

        $reports = $reports->get();

        return Inertia::render('IPD/Reports/IndoorBillingDetailByDoctor', [
            'reports' => $reports,
            'from_date' => date('d-m-Y H:i', strtotime($from_date)),
            'to_date' => date('d-m-Y H:i', strtotime($to_date)),
            'doctor_id' => $doctor_id,
            'doctors' => $doctors,
        ]);
    }


    public function BillingFileNo()
    {
        $file_no = null;
        if(isset($_GET['file_no']) && $_GET['file_no'] != ''){
            $file_no = Admission::where('file_no', $_GET['file_no']);
            $file_no = $file_no->value('id');
        }
        return Inertia::render('IPD/Reports/BillingFileNo', [
            'file_nos' => $file_no,
        ]);
    }


    public function IndoorSummaryDetail()
    {
        $reports = Admission::orderBy('name');
        $from_date = date('Y-m-d H:i',strtotime('today 00:00'));
        $to_date = date('Y-m-d H:i',strtotime('today 23:59'));
        if (isset($_GET['from_date']) && $_GET['from_date'] != '') {
            $from_date = DateTime::createFromFormat('d-m-Y H:i', $_GET['from_date']);
            if (!$from_date) {
                throw new \Exception('Invalid from_date format. Expected d-m-Y H:i.');
            }
            $from_date = $from_date->format('Y-m-d H:i');
        } else {
            $from_date = date('Y-m-d H:i', strtotime('today 00:00'));
        }

        if (isset($_GET['to_date']) && $_GET['to_date'] != '') {
            $to_date = DateTime::createFromFormat('d-m-Y H:i', $_GET['to_date']);
            if (!$to_date) {
                throw new \Exception('Invalid to_date format. Expected d-m-Y H:i.');
            }
            $to_date = $to_date->format('Y-m-d H:i');
        } else {
            $to_date = date('Y-m-d H:i', strtotime('today 23:59'));
        }
        $from_date_only = date('Y-m-d', strtotime($from_date));
        $from_time_only = date('H:i', strtotime($from_date));
        $to_date_only = date('Y-m-d', strtotime($to_date));
        $to_time_only = date('H:i', strtotime($to_date));
        // $reports = $reports->with(['department','details.service','details.doctor','room'])->whereBetween('admission_date', [$from_date, date('Y-m-d H:i', strtotime($to_date . ' +1 day'))]);
        $reports = $reports->with(['department','details.service','details.doctor','room']) ->where(function($q) use ($from_date_only, $to_date_only, $from_time_only, $to_time_only, $from_date, $to_date) {
            $q->whereBetween('admission_date', [$from_date_only, $to_date_only])
              ->whereBetween('admission_time', [$from_time_only, $to_time_only]);
        });
        $reports = $reports->get();



        return Inertia::render('IPD/Reports/IndoorSummaryDetail', [
            'reports' => $reports,
            'from_date' => date('d-m-Y H:i', strtotime($from_date)),
            'to_date' => date('d-m-Y H:i', strtotime($to_date)),
        ]);
    }

    public function IndoorSummaryDetailByFileNo()
    {
        $reports = Admission::orderBy('name');
        $file_no = null;
        $from_date = date('Y-m-d');
        $to_date = date('Y-m-d');
        if (isset($_GET['from_date']) && $_GET['from_date'] != '') {
            $from_date = DateTime::createFromFormat('d-m-Y H:i', $_GET['from_date']);
            if (!$from_date) {
                throw new \Exception('Invalid from_date format. Expected d-m-Y H:i.');
            }
            $from_date = $from_date->format('Y-m-d H:i');
        } else {
            $from_date = date('Y-m-d H:i', strtotime('today 00:00'));
        }

        if (isset($_GET['to_date']) && $_GET['to_date'] != '') {
            $to_date = DateTime::createFromFormat('d-m-Y H:i', $_GET['to_date']);
            if (!$to_date) {
                throw new \Exception('Invalid to_date format. Expected d-m-Y H:i.');
            }
            $to_date = $to_date->format('Y-m-d H:i');
        } else {
            $to_date = date('Y-m-d H:i', strtotime('today 23:59'));
        }
        if(isset($_GET['file_no']) && $_GET['file_no'] != ''){
            $file_no = $_GET['file_no'];
            $reports = $reports->where('file_no', $file_no);
        }
        $reports = $reports->with(['department','details.service','details.doctor','room']);
        $reports = $reports->get();



        return Inertia::render('IPD/Reports/IndoorSummaryDetailByFileNo', [
            'reports' => $reports,
            'from_date' => date('d-m-Y H:i', strtotime($from_date)),
            'to_date' => date('d-m-Y H:i', strtotime($to_date)),
            'file_no' => $file_no,
        ]);
    }

}
