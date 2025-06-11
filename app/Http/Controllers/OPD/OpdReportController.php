<?php

namespace App\Http\Controllers\OPD;

use Carbon\Carbon;
use Inertia\Inertia;
use App\Models\UserRole;
use App\Models\HRMS\Shift;
use App\Models\Lab\LabTest;
use App\Models\OPD\Patient;
use App\Models\OPD\Service;
use XelentAbrar\HospitalExpense\Models\Expenses\Expense;
use App\Models\HRMS\Employee;
use App\Models\IPD\Admission;
use XelentAbrar\HospitalDonation\Models\Donation\DonorFund;
use App\Models\Lab\PatientTest;
use Illuminate\Http\Request;
use App\Models\OPD\Appointment;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\OPD\Donation;
use DateTime;
use DateTimeZone;

use function Laravel\Prompts\error;

class OpdReportController extends Controller
{

    public function doctorDashboard(Request $request)
    {
        $user = Auth::user();
        $role = UserRole::where('user_id', $user->id)->where('role_id', 8)->first();
        $employee = Employee::where('id', $user->employee_id)->first();

        if (!$employee) {
            return redirect()->back()->with('error', 'Employee not found.');
        }

        $today = Carbon::today();
        $filter = $request->get('filterDate');

        $doctorAppointments = collect();

        switch ($filter) {
            case '3days':
                $startDate = $today->copy()->subDays(2);
                $endDate = $today;
                $doctorAppointmentsQuery = Appointment::where('consulting_doctor_id', $employee->id)
                    ->whereDate('appointment_date', '>=', $startDate)
                    ->whereDate('appointment_date', '<=', $endDate)
                    ->where('is_doctor_checked', 0);
                $doctorAppointments = $doctorAppointmentsQuery->get();
                break;

            case 'month':
                $startDate =$today->copy()->startOfMonth();
                $endDate = $today->endOfMonth();

                $doctorAppointmentsQuery = Appointment::where('consulting_doctor_id', $employee->id)
                    ->whereDate('appointment_date', '>=', $startDate)
                    ->whereDate('appointment_date', '<=', $endDate)
                    ->where('is_doctor_checked', 0);
                $doctorAppointments = $doctorAppointmentsQuery->get();
                break;

            case 'week':
                $startDate = $today->copy()->subDays(6);
                $endDate = $today;

                $doctorAppointmentsQuery = Appointment::where('consulting_doctor_id', $employee->id)
                    ->whereDate('appointment_date', '>=', $startDate)
                    ->whereDate('appointment_date', '<=', $endDate)
                    ->where('is_doctor_checked', 0);

                $doctorAppointments = $doctorAppointmentsQuery->get();
                break;

            case 'today':
            default:
                $doctorAppointmentsQuery = Appointment::where('consulting_doctor_id', $employee->id)
                    ->whereDate('appointment_date', $today)
                    ->where('is_doctor_checked', 0);
                $doctorAppointments = $doctorAppointmentsQuery->get();
                break;
        }

        return response()->json([
            'role' => $role,
            'employee' => $employee,
            'doctorAppointments' => $doctorAppointments,
        ]);
    }

    public function updateDoctorCheck(Request $request, $id)
    {
        $appointment = Appointment::find($id);
        // dd($request->all());

        if ($appointment) {
            $isDoctorChecked = $request->input('is_doctor_checked') ? 1 : 0;

            $appointment->is_doctor_checked = $isDoctorChecked;
            $appointment->save();

            return response()->json(['message' => 'Appointment updated successfully']);
        }

        return response()->json(['message' => 'Appointment not found'], 404);
    }

    public function updateBulkDoctorCheck(Request $request)
    {
        $appointmentIds = $request->input('appointmentIds');
        $isChecked = $request->input('is_doctor_checked') ? 1 : 0;

        $appointments = Appointment::whereIn('id', $appointmentIds)->get();

        foreach ($appointments as $appointment) {
            $appointment->is_doctor_checked = $isChecked;
            $appointment->save();
        }

        return response()->json(['message' => 'Appointments updated successfully']);
    }

    public function docDetailReportIndex()
    {
        $doctors = Employee::select('id', 'name')->whereHas('designation', function ($q) {
            $q->where('is_doctor', 1);
        })->orderBy('name')->get();
        $user = Auth::user();
        $role = UserRole::where('user_id', $user->id)->where('role_id', 1)->first();
        return Inertia::render('OPD/PatientFeedbacks/Index', [
            'doctors' => $doctors,
            'role' => $role,
        ]);
    }


    public function docDetailReport()
    {
        $doctors = Employee::select('id', 'name')->whereHas('designation', function ($q) {
            $q->where('is_doctor', 1);
        })->orderBy('name')->get();

        $appointment = new Appointment();
        $from_date = date('Y-m-d');
        $to_date = date('Y-m-d');
        $doctor_id = null;
        if (isset($_GET['from_date']) && $_GET['from_date'] != '') {
            $from_date = DateTime::createFromFormat('d-m-Y', $_GET['from_date']);
            if (!$from_date) {
                throw new \Exception('Invalid from_date format. Expected d-m-Y.');
            }
            $from_date = $from_date->format('Y-m-d');
        }

        if (isset($_GET['to_date']) && $_GET['to_date'] != '') {
            $to_date = DateTime::createFromFormat('d-m-Y', $_GET['to_date']);
            if (!$to_date) {
                throw new \Exception('Invalid to_date format. Expected d-m-Y.');
            }
            $to_date = $to_date->format('Y-m-d');
        }
        if (isset($_GET['doctor_id']) && $_GET['doctor_id'] != '') {
            $doctor_id = $_GET['doctor_id'];
        }

        if ($doctor_id) {
            $appointment = $appointment->where('consulting_doctor_id', $doctor_id);
        }
        $appointment = $appointment->with(['appointmentDetails.service', 'careoff', 'zf', 'doctor'])->whereBetween('appointment_date', [$from_date, date('Y-m-d H:i', strtotime($to_date . ' +1 day'))]);
        $appointment = $appointment->get();
        return Inertia::render('OPD/Reports/DocDetail', [
            'doctors' => $doctors,
            'reports' => $appointment,
            'doctor_id' => $doctor_id,
            // 'from_date' => $from_date,
            // 'to_date' => $to_date,
            'from_date' => date('d-m-Y', strtotime($from_date)),
            'to_date' => date('d-m-Y', strtotime($to_date)),
        ]);
    }

    // public function zfDetailReport()
    // {
    //     $appointment = new Appointment();
    //     $from_date = date('Y-m-d');
    //     $to_date = date('Y-m-d');
    //     if(isset($_GET['from_date']) && $_GET['from_date'] != ''){
    //         $from_date = $_GET['from_date'];
    //     }
    //     if(isset($_GET['to_date']) && $_GET['to_date'] != ''){
    //         $to_date = $_GET['to_date'];
    //     }
    //     $appointment = $appointment->with(['appointmentDetails.service','careoff','zf','doctor'])->where(function($q){
    //         $q->whereNotNull('zf_fee');
    //     })->whereBetween('appointment_date', [$from_date, date('Y-m-d H:i', strtotime($to_date . ' +1 day'))]);
    //     $appointment = $appointment->get();
    //     return Inertia::render('OPD/Reports/zf', [
    //         'reports' => $appointment,
    //         'from_date' => $from_date,
    //         'to_date' => $to_date,
    //     ]);
    // }
    

    public function summaryByDateReport()
    {
        $appointments = new Appointment();
        $search = '';
        $from_date = date('Y-m-d H:i', strtotime('today 00:00'));
        $to_date = date('Y-m-d H:i', strtotime('today 23:59'));
        // if (isset($_GET['from_date']) && $_GET['from_date'] != '') {
        //     $from_date = date('Y-m-d H:i', strtotime($_GET['from_date']));
        // }
        // if (isset($_GET['to_date']) && $_GET['to_date'] != '') {
        //     $to_date = date('Y-m-d H:i', strtotime($_GET['to_date']));
        // }
        if (isset($_GET['from_date']) && $_GET['from_date'] != '') {
            $from_date = DateTime::createFromFormat('d-m-Y H:i', $_GET['from_date']);
            $from_date = $from_date->format('Y-m-d H:i');
        } else {
            $from_date = date('Y-m-d H:i', strtotime('today 00:00'));
        }

        if (isset($_GET['to_date']) && $_GET['to_date'] != '') {
            $to_date = DateTime::createFromFormat('d-m-Y H:i', $_GET['to_date']);
            $to_date = $to_date->format('Y-m-d H:i');
        } else {
            $to_date = date('Y-m-d H:i', strtotime('today 23:59'));
        }
        if (isset($_GET['search']) && $_GET['search'] != '') {
            $search = $_GET['search'];
        }
        $from_date_only = date('Y-m-d', strtotime($from_date));
        $from_time_only = date('H:i', strtotime($from_date));
        $to_date_only = date('Y-m-d', strtotime($to_date));
        $to_time_only = date('H:i', strtotime($to_date));
        // $appointments = $appointments->with(['appointmentDetails.service.category','careoff','zf','doctor'])->whereBetween('appointment_date', [$from_date, date('Y-m-d H:i', strtotime($to_date . ' +1 day'))]);
        $appointments = $appointments->with(['appointmentDetails.service.category', 'careoff', 'zf', 'doctor'])
            ->whereHas('doctor', function ($query) {
                $query->where('doctor_type', 'General');
            })
            ->where(function ($query) use ($from_date_only, $to_date_only, $from_time_only, $to_time_only, $search) {
                $query->whereBetween('appointment_date', [$from_date_only, $to_date_only])
                    ->whereBetween('appointment_time', [$from_time_only, $to_time_only]);

                if ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('appointment_date', 'like', '%' . $search . '%')
                            ->orWhereHas('appointmentDetails.service', function ($q) use ($search) {
                                $q->where('name', 'like', '%' . $search . '%');
                            });
                    });
                }
            });

        $appointments = $appointments->get();
        $data = [];
        $services = [];
        $doctorData = [];
        $doctorData = [];
        foreach ($appointments as $appointment) {
            foreach ($appointment->appointmentDetails as $appointmentDetail) {
                $doctorId = $appointment->consulting_doctor_id;
                $doc_serial = $appointment->doc_serial;
                $cancel = $appointment->cancel;
                $fee = $appointmentDetail->fee;
                $donorFee = $appointment->donor_fee;
                $zfFee = $appointment->zf_fee;

                if (!isset($doctorData[$doctorId])) {
                    $doctorData[$doctorId] = [
                        'doctor_name' => $appointment->doctor->name,
                        'doctor_fee' => $appointmentDetail->fee,
                        'total_patients' => 0,
                        'total_fee' => 0,
                        'total_donor_fee' => 0,
                        'total_zf_fee' => 0,
                        'from_serial' => null,
                        'to_serial' => null,
                        'total_canceled' => 0,
                        'amount' => 0,
                    ];
                }
                if ($cancel === 0) {
                    $doctorData[$doctorId]['total_patients'] += 1;
                    $doctorData[$doctorId]['total_fee'] += $fee;
                    $doctorData[$doctorId]['total_donor_fee'] += $donorFee;
                    $doctorData[$doctorId]['total_zf_fee'] += $zfFee;
                }
                if ($cancel) {
                    $doctorData[$doctorId]['total_canceled'] += 1;
                }
                $earliestAppointment = Appointment::where('consulting_doctor_id', $doctorId)
                    ->whereDate('appointment_date', '>=', $from_date_only)
                    ->orderBy('appointment_date', 'asc')
                    ->orderBy('id', 'asc')
                    ->first();
                if ($earliestAppointment) {
                    $doctorData[$doctorId]['from_serial'] = $earliestAppointment->doc_serial;
                }
                $latestAppointment = Appointment::where('consulting_doctor_id', $doctorId)
                    ->whereDate('appointment_date', '<=', $to_date_only)
                    ->orderBy('appointment_date', 'desc')
                    ->orderBy('id', 'desc')
                    ->first();
                if ($latestAppointment) {
                    $doctorData[$doctorId]['to_serial'] = $latestAppointment->doc_serial;
                }
                $doctorData[$doctorId]['amount'] = $doctorData[$doctorId]['total_canceled'] * $fee;
            }
        }
        foreach ($doctorData as $doctorId => $doctor) {
            // dd($doctor);
            $parms = [];
            $parms['doctor_id'] = $doctorId;
            $parms['service_name'] = $doctor['doctor_name'];
            $parms['patients'] = $doctor['total_patients'];
            $parms['fee'] = $doctor['total_fee'];
            $parms['doctor_fee'] = $doctor['doctor_fee'];
            $parms['careOff'] = $doctor['total_donor_fee'];
            $parms['zf'] = $doctor['total_zf_fee'];
            $parms['from'] = $doctor['from_serial'];
            $parms['to'] = $doctor['to_serial'];
            $parms['amount'] = $doctor['amount'];
            $data['MO EYE OPD RECEIPT'][] = $parms;
        }
        $totalLabTestsAmount = 0;
        $totalLabTestsCount = 0;
        $totalLabTestsZFFee = 0;
        $totalLabTestsDiscountValue = 0;
        $totalLabTestsDonorFee = 0;
        $totalOtherReceiptsAmount = 0;
        $totalOtherReceiptsZFFee = 0;
        $totalOtherReceiptsDiscountValue = 0;
        $totalOtherReceiptsDonorFee = 0;

        $patientTests = PatientTest::with(['testGeneralDetails.labTest'])
            ->whereBetween('test_date', [$from_date_only, $to_date_only])
            ->whereHas('testGeneralDetails.labTest', function ($query) {
                $query->where('lab_type', 'lab_test')->whereNotNull('lab_type');
            })
            ->get();

        $earliestLabTest = $patientTests->filter(function ($patientTest) {
            return $patientTest->testGeneralDetails?->labTest?->lab_type === 'lab_test'
                && $patientTest->lab_serial !== null;
        })->sortBy('lab_serial')->first();

        $latestLabTest = $patientTests->filter(function ($patientTest) {
            return $patientTest->testGeneralDetails?->labTest?->lab_type === 'lab_test'
                && $patientTest->lab_serial !== null;
        })->sortByDesc('lab_serial')->first();


        foreach ($patientTests as $patientTest) {
            $testGeneralDetail = $patientTest->testGeneralDetails;
            if ($testGeneralDetail) {

                $labTest = $testGeneralDetail->labTest;
                if ($labTest !== null && $labTest->lab_type === 'lab_test') {
                    $totalLabTestsAmount += (float) ($patientTest->total_amount ?? 0);
                    $totalLabTestsZFFee += (float) ($patientTest->zf_fee ?? 0);
                    $totalLabTestsDiscountValue += (float) ($patientTest->discount_value ?? 0);
                    $totalLabTestsDonorFee += (float) ($patientTest->donor_fee ?? 0);
                    $totalLabTestsCount += 1;
                }
            }
        }
        $canceledPatientTests = $patientTests->filter(function ($test) {
            return $test->cancel === 1;
        });

        $canceledTotalAmount = $canceledPatientTests->sum('total_amount');
        $labTestsSummary = [
            'service_name' => 'Lab Test',
            'fee' => $totalLabTestsAmount,
            'patients' => $totalLabTestsCount,
            'careOff' => $totalLabTestsDonorFee,
            'zf' => $totalLabTestsZFFee,
            'amount' => $canceledTotalAmount,
            'discount_amount' => $totalLabTestsDiscountValue,
            'from' => $earliestLabTest ? $earliestLabTest->lab_serial : null,
            'to' => $latestLabTest ? $latestLabTest->lab_serial : null
        ];

        if (!isset($data['LAB & OTHER RECEIPTS'])) {
            $data['LAB & OTHER RECEIPTS'] = [];
        }

        $data['LAB & OTHER RECEIPTS'][] = $labTestsSummary;
        $otherReceipts = LabTest::with(['testGeneralDetails.patientTest'])
            ->where('lab_type', 'other_receipts')->whereNotNull('lab_type')
            ->whereHas('testGeneralDetails.patientTest', function ($query) use ($from_date_only, $to_date_only) {
                $query->whereBetween('test_date', [$from_date_only, $to_date_only]);
            })
            ->get();

        $earliestOtherReceiptSerialNo = null;
        $latestOtherReceiptSerialNo = null;

        $sortedTestGeneralDetails = [];
        foreach ($otherReceipts as $labTest) {
            if (isset($labTest['testGeneralDetails'])) {
                foreach ($labTest['testGeneralDetails'] as $testGeneralDetail) {
                    $sortedTestGeneralDetails[] = $testGeneralDetail;
                }
            }
        }

        if (!empty($sortedTestGeneralDetails)) {
            usort($sortedTestGeneralDetails, function ($a, $b) {
                return $a['lab_serial'] <=> $b['lab_serial'];
            });

            $earliestOtherReceipt = $sortedTestGeneralDetails[0];
            $latestOtherReceipt = end($sortedTestGeneralDetails);

            $earliestOtherReceiptSerialNo = $earliestOtherReceipt['lab_serial'];
            $latestOtherReceiptSerialNo = $latestOtherReceipt['lab_serial'];
        }
        foreach ($otherReceipts as $labTest) {
            $totalOtherReceiptsAmount = 0;
            $totalOtherReceiptsZFFee = 0;
            $totalOtherReceiptsDiscountValue = 0;
            $totalOtherReceiptsDonorFee = 0;
            $cancelledPatientTestsCount = 0;
            $cancelledPatientTestsAmount = 0;
            if (isset($labTest['testGeneralDetails'])) {

                $labSerials = [];
                $labTestCounts = [];
                foreach ($labTest['testGeneralDetails'] as $testGeneralDetail) {
                    // return $labTest['id'];
                    if (isset($testGeneralDetail['patientTest'])) {

                        // $zfFeeShare = $patientTest['zf_fee'] ?? 0;
                        // $donorFeeShare = $patientTest['donor_fee'] ?? 0;
                        // $discountValueShare = $patientTest['discount_value'] ?? 0;
                        $patientTest = $testGeneralDetail['patientTest'] ?? 0;
                        $testDate = \Carbon\Carbon::parse($patientTest->test_date)->format('Y-m-d');
                        if ($testDate >= $from_date_only && $testDate <= $to_date_only) {
                            if ($testGeneralDetail['lab_test_id'] ==  $labTest['id']) {
                                $labSerials[] = $testGeneralDetail['lab_serial'];
                            }

                            $zfFeeShare = $patientTest['zf_fee'] ?? 0;
                            $donorFeeShare = $patientTest['donor_fee'] ?? 0;
                            $discountValueShare = $patientTest['discount_value'] ?? 0;
                            if ($patientTest->cancel == 1) {
                                $cancelledPatientTestsCount++;
                                $cancelledPatientTestsAmount += (float) ($patientTest->total_amount ?? 0);
                            }
                            if (!isset($labTestCounts[$testGeneralDetail['lab_test_id']])) {
                                $labTestCounts[$testGeneralDetail['lab_test_id']] = 0;
                            }
                            $labTestCounts[$testGeneralDetail['lab_test_id']]++;
                            $totalOtherReceiptsZFFee += $patientTest['zf_fee'];

                            // $testGeneralDetailsCount = count($labTest['testGeneralDetails']);
                            $totalOtherReceiptsAmount += (float) $labTest->price;
                            $totalOtherReceiptsDiscountValue += (float) $discountValueShare;
                            $totalOtherReceiptsDonorFee += (float) $donorFeeShare;
                            $earliestLabSerial = !empty($labSerials) ? min($labSerials) : null;
                            $latestLabSerial = !empty($labSerials) ? max($labSerials) : null;
                        }
                    }
                }
            }

            $parms = [
                'service_name' => $labTest['name'] ?? '',
                'fee' => $totalOtherReceiptsAmount,
                // 'patients' => $testGeneralDetailsCount,
                'patients' => $labTestCounts[$labTest['id']] ?? 0,
                'zf' => $totalOtherReceiptsZFFee,
                'discount_amount' => $totalOtherReceiptsDiscountValue,
                'careOff' => $totalOtherReceiptsDonorFee,
                'from' => $earliestLabSerial,
                'to' => $latestLabSerial,
                'amount' => $cancelledPatientTestsAmount,
            ];

            if (!isset($data['LAB & OTHER RECEIPTS'])) {
                $data['LAB & OTHER RECEIPTS'] = [];
            }

            $data['LAB & OTHER RECEIPTS'][] = $parms;
        }
        if(file_exists(base_path('config/donation.php'))) {
            $donorFunds = DonorFund::with('donor')
                ->where(function ($query) use ($from_date_only, $to_date_only, $from_time_only, $to_time_only) {
                    $fromDateTime = $from_date_only . ' ' . $from_time_only;
                    $toDateTime = $to_date_only . ' ' . $to_time_only;
                    $query->whereBetween('created_at', [$fromDateTime, $toDateTime]);
                })
                ->get();
        }

        if (!isset($data['LAB & OTHER RECEIPTS'])) {
            $data['LAB & OTHER RECEIPTS'] = [];
        }

        if(file_exists(base_path('config/donation.php'))) {
            foreach ($donorFunds as $donorFund) {
                $donorName = $donorFund->donor->name ?? null;
                $donorSerial = $donorFund->donor_serial;
                $totalFee = $donorFund->charges;

                $data['LAB & OTHER RECEIPTS'][] = [
                    'service_name' => $donorName,
                    'fee' => $totalFee,
                    'from' => $donorSerial,
                    'to' => $donorSerial,
                    'patients' => '1',
                ];
            }
        }
        $donations = Donation::where(function ($query) use ($from_date_only, $to_date_only, $from_time_only, $to_time_only) {
                $fromDateTime = $from_date_only . ' ' . $from_time_only;
                $toDateTime = $to_date_only . ' ' . $to_time_only;
                $query->whereBetween('created_at', [$fromDateTime, $toDateTime]);
            })
            ->get();

        if (!isset($data['LAB & OTHER RECEIPTS'])) {
            $data['LAB & OTHER RECEIPTS'] = [];
        }

        foreach ($donations as $donation) {
            $donorName = $donation->donor ?? null;
            $donorSerial = $donation->donor_serial;
            $totalFee = $donation->charges;

            $data['LAB & OTHER RECEIPTS'][] = [
                'service_name' => $donorName,
                'fee' => $totalFee,
                'from' => $donorSerial,
                'to' => $donorSerial,
                'patients' => '1',
            ];
        }
        $specialistAppointments = Appointment::with(['appointmentDetails.service.category', 'careoff', 'zf', 'doctor'])
            ->whereHas('doctor', function ($query) {
                $query->where('doctor_type', 'Specialist');
            })
            ->where(function ($query) use ($from_date_only, $to_date_only, $search) {
                $query->whereBetween('appointment_date', [$from_date_only, $to_date_only]);

                if ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('appointment_date', 'like', '%' . $search . '%')
                            ->orWhereHas('appointmentDetails.service', function ($q) use ($search) {
                                $q->where('name', 'like', '%' . $search . '%');
                            });
                    });
                }
            });

        $specialistAppointments = $specialistAppointments->get();
        $doctorData = [];

        foreach ($specialistAppointments as $appointment) {
            foreach ($appointment->appointmentDetails as $appointmentDetail) {
                $doctorId = $appointment->consulting_doctor_id;
                $cancel = $appointment->cancel;
                $fee = $appointmentDetail->fee;
                $donorFee = $appointment->donor_fee;
                $zfFee = $appointment->zf_fee;

                if (!isset($doctorData[$doctorId])) {
                    $doctorData[$doctorId] = [
                        'doctor_name' => $appointment->doctor->name,
                        'doctor_fee' => $appointmentDetail->fee,
                        'total_patients' => 0,
                        'total_fee' => 0,
                        'total_donor_fee' => 0,
                        'total_zf_fee' => 0,
                        'from_serial' => null,
                        'to_serial' => null,
                        'total_canceled' => 0,
                        'amount' => 0,
                    ];
                }

                if ($cancel === 0) {
                    $doctorData[$doctorId]['total_patients'] += 1;
                    $doctorData[$doctorId]['total_fee'] += $fee;
                    $doctorData[$doctorId]['total_donor_fee'] += $donorFee;
                    $doctorData[$doctorId]['total_zf_fee'] += $zfFee;
                }

                if ($cancel) {
                    $doctorData[$doctorId]['total_canceled'] += 1;
                }

                $earliestAppointment = Appointment::where('consulting_doctor_id', $doctorId)
                    ->whereDate('appointment_date', '>=', $from_date_only)
                    ->orderBy('appointment_date', 'asc')
                    ->orderBy('id', 'asc')
                    ->first();

                if ($earliestAppointment) {
                    $doctorData[$doctorId]['from_serial'] = $earliestAppointment->doc_serial;
                }

                $latestAppointment = Appointment::where('consulting_doctor_id', $doctorId)
                    ->whereDate('appointment_date', '<=', $to_date_only)
                    ->orderBy('appointment_date', 'desc')
                    ->orderBy('id', 'desc')
                    ->first();

                if ($latestAppointment) {
                    $doctorData[$doctorId]['to_serial'] = $latestAppointment->doc_serial;
                }

                $doctorData[$doctorId]['amount'] = $doctorData[$doctorId]['total_canceled'] * $fee;
            }
        }

        foreach ($doctorData as $doctorId => $doctor) {
            $parms = [];
            $parms['doctor_id'] = $doctorId;
            $parms['service_name'] = $doctor['doctor_name'];
            $parms['patients'] = $doctor['total_patients'];
            $parms['fee'] = $doctor['total_fee'];
            $parms['doctor_fee'] = $doctor['doctor_fee'];
            $parms['careOff'] = $doctor['total_donor_fee'];
            $parms['zf'] = $doctor['total_zf_fee'];
            $parms['from'] = $doctor['from_serial'];
            $parms['to'] = $doctor['to_serial'];
            $parms['amount'] = $doctor['amount'];
            $data['PVT CONSULTANT RECEIPT'][] = $parms;
        }

        $advance_admissions = new Admission;
        // $advance_admissions = $advance_admissions->whereBetween('admission_date', [$from_date, date('Y-m-d H:i', strtotime($to_date . ' +1 day'))])->where(function($q){
        $advance_admissions = $advance_admissions->whereBetween('admission_date', [$from_date, $to_date])->where(function ($q) {
            $q->whereNotNull('advance_amount')->where('advance_amount', '>=', '0');
        })->get();
        $earliestAdvanceAdmission = $advance_admissions->sortBy('id')->first();
        $latestAdvanceAdmission = $advance_admissions->sortByDesc('id')->first();
        $canceled_advance_admissions = Admission::whereBetween('admission_date', [$from_date, $to_date])
            ->where('cancel', 1)
            ->whereNotNull('advance_amount')
            ->where('advance_amount', '>=', '0')
            ->get();
        $canceled_count = $canceled_advance_admissions->count();
        $canceled_sum = $canceled_advance_admissions->sum('advance_amount');
        $parms = [];
        $parms['service_id'] = null;
        $parms['service_name'] = 'Advance';
        $parms['fee'] = $advance_admissions->sum('advance_amount');
        $parms['patients'] = $advance_admissions->count();
        $parms['careOff'] = 0;
        $parms['zf'] = 0;
        $parms['discount_amount'] = 0;
        $parms['amount'] = $canceled_sum;
        $parms['from'] = $earliestAdvanceAdmission ? $earliestAdvanceAdmission->Ipd_serial : 0;
        $parms['to'] = $latestAdvanceAdmission ? $latestAdvanceAdmission->Ipd_serial : 0;
        $data['Billing'][] = $parms;

        $received_admissions = Admission::whereBetween('discharge_date', [$from_date, $to_date])
            ->where('status', 'Complete')
            ->get();

        $earliestReceivedAdmission = $received_admissions->sortBy('discharge_date')->first();
        $latestReceivedAdmission = $received_admissions->sortByDesc('discharge_date')->first();
        $refund_sum = Admission::whereBetween('discharge_date', [$from_date, $to_date])
            ->where('status', 'Complete')
            ->sum('refund_amount');

        $received_sum = Admission::whereBetween('discharge_date', [$from_date, $to_date])
            ->where('status', 'Complete')
            ->sum('received_amount');
        $parms = [];
        $parms['service_id'] = null;
        $parms['service_name'] = 'Indoor Bill';
        // $parms['fee'] = ($received_admissions->sum('total_amount') < 0 ? $received_admissions->sum('total_amount') : -$received_admissions->sum('total_amount'));
        $parms['fee'] = $received_admissions->sum('donor_fee') + $received_admissions->sum('zf_fee') + $received_admissions->sum('discount_amount');
        $parms['patients'] = $received_admissions->count();
        $parms['careOff'] = $received_admissions->whereNotNull('careoff_id')->sum('donor_fee');
        $parms['zf'] = $received_admissions->sum('zf_fee');
        $parms['discount_amount'] = $received_admissions->sum('discount_amount');
        $parms['from'] = $earliestReceivedAdmission ? $earliestReceivedAdmission->Ipd_serial : 0;
        $parms['to'] = $latestReceivedAdmission ? $latestReceivedAdmission->Ipd_serial : 0;
        $parms['refund'] = $refund_sum;
        $parms['received'] = $received_sum;

        $data['Billing'][] = $parms;


        if(file_exists(base_path('config/expense.php'))) {
            $expenses = Expense::whereBetween('date', [$from_date, $to_date])->get();
            foreach ($expenses as $expense) {
                $parms = [];
                $parms['service_id'] = null;
                $parms['service_name'] = $expense->description;
                $parms['patients'] = 1;
                $parms['fee'] =
                    $parms['careOff'] = 0;
                $parms['zf'] = 0;
                $parms['discount_amount'] = 0;
                $parms['from'] = $expense->serial_no;
                $parms['to'] = $expense->serial_no;
                $parms['amount'] = $expense->amount;
                $parms['notes'] = $expense->notes;
                $data['Expenses'][] = $parms;
            }
        }

        $stats = [];

        foreach ($data as $index => $item) {
            $stats[$index]['patients'] = collect($item)->sum('patients');
            $stats[$index]['fee'] = collect($item)->sum('fee');
            $stats[$index]['careOff'] = collect($item)->sum('careOff');
            $stats[$index]['zf'] = collect($item)->sum('zf');
            $stats[$index]['discount_amount'] = collect($item)->sum('discount_amount');
            $stats[$index]['total_amount'] = collect($item)->sum('amount');
            $stats[$index]['refund'] = collect($item)->sum('refund');
            $stats[$index]['received'] = collect($item)->sum('received');
        }
        // return $data;
        return Inertia::render('OPD/Reports/SummaryByDate', [
            'stats' => $stats,
            'reports' => $data,
            // 'result' => $result,
            'from_date' => date('d-m-Y H:i', strtotime($from_date)),
            'to_date' => date('d-m-Y H:i', strtotime($to_date)),
            // 'from_date' => $from_date,
            // 'to_date' => $to_date,
            'search' => $search,
        ]);
    }
    //  public function summaryByDateReport()
    // {
    //     $appointments = new Appointment();
    //     $search = '';
    //     $from_date = date('Y-m-d');
    //     $to_date = date('Y-m-d');
    //     if (isset($_GET['from_date']) && $_GET['from_date'] != '') {
    //         $from_date = DateTime::createFromFormat('d-m-Y', $_GET['from_date']);
    //         if (!$from_date) {
    //             throw new \Exception('Invalid from_date format. Expected d-m-Y.');
    //         }
    //         $from_date = $from_date->format('Y-m-d');
    //     }

    //     if (isset($_GET['to_date']) && $_GET['to_date'] != '') {
    //         $to_date = DateTime::createFromFormat('d-m-Y', $_GET['to_date']);
    //         if (!$to_date) {
    //             throw new \Exception('Invalid to_date format. Expected d-m-Y.');
    //         }
    //         $to_date = $to_date->format('Y-m-d');
    //     }
    //     if (isset($_GET['search']) && $_GET['search'] != '') {
    //         $search = $_GET['search'];
    //     }
    //     $from_date_only = date('Y-m-d', strtotime($from_date));
    //     // $from_time_only = date('H:i', strtotime($from_date));
    //     $to_date_only = date('Y-m-d', strtotime($to_date));
    //     // $to_time_only = date('H:i', strtotime($to_date));
    //     // $appointments = $appointments->with(['appointmentDetails.service.category','careoff','zf','doctor'])->whereBetween('appointment_date', [$from_date, date('Y-m-d H:i', strtotime($to_date . ' +1 day'))]);
    //     $appointments = $appointments->with(['appointmentDetails.service.category', 'careoff', 'zf', 'doctor'])
    //         ->whereHas('doctor', function ($query) {
    //             $query->where('doctor_type', 'General');
    //         })
    //         ->where(function ($query) use ($from_date_only, $to_date_only,$search) {
    //             $query->whereBetween('appointment_date', [$from_date_only, $to_date_only]);
    //                 // ->whereBetween('appointment_time', [$from_time_only, $to_time_only]);

    //             if ($search) {
    //                 $query->where(function ($q) use ($search) {
    //                     $q->where('appointment_date', 'like', '%' . $search . '%')
    //                         ->orWhereHas('appointmentDetails.service', function ($q) use ($search) {
    //                             $q->where('name', 'like', '%' . $search . '%');
    //                         });
    //                 });
    //             }
    //         });

    //     $appointments = $appointments->get();
    //     $data = [];
    //     $services = [];
    //     $doctorData = [];
    //     $doctorData = [];
    //     foreach ($appointments as $appointment) {
    //         foreach ($appointment->appointmentDetails as $appointmentDetail) {
    //             $doctorId = $appointment->consulting_doctor_id;
    //             $doc_serial = $appointment->doc_serial;
    //             $cancel = $appointment->cancel;
    //             $fee = $appointmentDetail->fee;
    //             $donorFee = $appointment->donor_fee;
    //             $zfFee = $appointment->zf_fee;

    //             if (!isset($doctorData[$doctorId])) {
    //                 $doctorData[$doctorId] = [
    //                     'doctor_name' => $appointment->doctor->name,
    //                     'doctor_fee' => $appointmentDetail->fee,
    //                     'total_patients' => 0,
    //                     'total_fee' => 0,
    //                     'total_donor_fee' => 0,
    //                     'total_zf_fee' => 0,
    //                     'from_serial' => null,
    //                     'to_serial' => null,
    //                     'total_canceled' => 0,
    //                     'amount' => 0,
    //                 ];
    //             }
    //             if ($cancel === 0) {
    //                 $doctorData[$doctorId]['total_patients'] += 1;
    //                 $doctorData[$doctorId]['total_fee'] += $fee;
    //                 $doctorData[$doctorId]['total_donor_fee'] += $donorFee;
    //                 $doctorData[$doctorId]['total_zf_fee'] += $zfFee;
    //             }
    //             if ($cancel) {
    //                 $doctorData[$doctorId]['total_canceled'] += 1;
    //             }
    //             $earliestAppointment = Appointment::where('consulting_doctor_id', $doctorId)
    //                 ->whereDate('appointment_date', '>=', $from_date_only)
    //                 ->orderBy('appointment_date', 'asc')
    //                 ->orderBy('id', 'asc')
    //                 ->first();
    //             if ($earliestAppointment) {
    //                 $doctorData[$doctorId]['from_serial'] = $earliestAppointment->doc_serial;
    //             }
    //             $latestAppointment = Appointment::where('consulting_doctor_id', $doctorId)
    //                 ->whereDate('appointment_date', '<=', $to_date_only)
    //                 ->orderBy('appointment_date', 'desc')
    //                 ->orderBy('id', 'desc')
    //                 ->first();
    //             if ($latestAppointment) {
    //                 $doctorData[$doctorId]['to_serial'] = $latestAppointment->doc_serial;
    //             }
    //             $doctorData[$doctorId]['amount'] = $doctorData[$doctorId]['total_canceled'] * $fee;
    //         }
    //     }
    //     foreach ($doctorData as $doctorId => $doctor) {
    //         // dd($doctor);
    //         $parms = [];
    //         $parms['doctor_id'] = $doctorId;
    //         $parms['service_name'] = $doctor['doctor_name'];
    //         $parms['patients'] = $doctor['total_patients'];
    //         $parms['fee'] = $doctor['total_fee'];
    //         $parms['doctor_fee'] = $doctor['doctor_fee'];
    //         $parms['careOff'] = $doctor['total_donor_fee'];
    //         $parms['zf'] = $doctor['total_zf_fee'];
    //         $parms['from'] = $doctor['from_serial'];
    //         $parms['to'] = $doctor['to_serial'];
    //         $parms['amount'] = $doctor['amount'];
    //         $data['MO EYE OPD RECEIPT'][] = $parms;
    //     }
    //     $totalLabTestsAmount = 0;
    //     $totalLabTestsCount = 0;
    //     $totalLabTestsZFFee = 0;
    //     $totalLabTestsDiscountValue = 0;
    //     $totalLabTestsDonorFee = 0;
    //     $totalOtherReceiptsAmount = 0;
    //     $totalOtherReceiptsZFFee = 0;
    //     $totalOtherReceiptsDiscountValue = 0;
    //     $totalOtherReceiptsDonorFee = 0;

    //     $patientTests = PatientTest::with(['testGeneralDetails.labTest'])
    //         ->whereBetween('test_date', [$from_date_only, $to_date_only])
    //         ->whereHas('testGeneralDetails.labTest', function ($query) {
    //             $query->where('lab_type', 'lab_test')->whereNotNull('lab_type');
    //         })
    //         ->get();

    //     $earliestLabTest = $patientTests->filter(function ($patientTest) {
    //         return $patientTest->testGeneralDetails?->labTest?->lab_type === 'lab_test'
    //             && $patientTest->lab_serial !== null;
    //     })->sortBy('lab_serial')->first();

    //     $latestLabTest = $patientTests->filter(function ($patientTest) {
    //         return $patientTest->testGeneralDetails?->labTest?->lab_type === 'lab_test'
    //             && $patientTest->lab_serial !== null;
    //     })->sortByDesc('lab_serial')->first();


    //     foreach ($patientTests as $patientTest) {
    //         $testGeneralDetail = $patientTest->testGeneralDetails;
    //         if ($testGeneralDetail) {

    //             $labTest = $testGeneralDetail->labTest;
    //             if ($labTest !== null && $labTest->lab_type === 'lab_test') {
    //                 $totalLabTestsAmount += (float) ($patientTest->total_amount ?? 0);
    //                 $totalLabTestsZFFee += (float) ($patientTest->zf_fee ?? 0);
    //                 $totalLabTestsDiscountValue += (float) ($patientTest->discount_value ?? 0);
    //                 $totalLabTestsDonorFee += (float) ($patientTest->donor_fee ?? 0);
    //                 $totalLabTestsCount += 1;
    //             }
    //         }
    //     }
    //     $canceledPatientTests = $patientTests->filter(function ($test) {
    //         return $test->cancel === 1;
    //     });

    //     $canceledTotalAmount = $canceledPatientTests->sum('total_amount');
    //     $labTestsSummary = [
    //         'service_name' => 'Lab Test',
    //         'fee' => $totalLabTestsAmount,
    //         'patients' => $totalLabTestsCount,
    //         'careOff' => $totalLabTestsDonorFee,
    //         'zf' => $totalLabTestsZFFee,
    //         'amount' => $canceledTotalAmount,
    //         'discount_amount' => $totalLabTestsDiscountValue,
    //         'from' => $earliestLabTest ? $earliestLabTest->lab_serial : null,
    //         'to' => $latestLabTest ? $latestLabTest->lab_serial : null
    //     ];

    //     if (!isset($data['LAB & OTHER RECEIPTS'])) {
    //         $data['LAB & OTHER RECEIPTS'] = [];
    //     }

    //     $data['LAB & OTHER RECEIPTS'][] = $labTestsSummary;
    //     $otherReceipts = LabTest::with(['testGeneralDetails.patientTest'])
    //         ->where('lab_type', 'other_receipts')->whereNotNull('lab_type')
    //         ->whereHas('testGeneralDetails.patientTest', function ($query) use ($from_date_only, $to_date_only) {
    //             $query->whereBetween('test_date', [$from_date_only, $to_date_only]);
    //         })
    //         ->get();

    //     $earliestOtherReceiptSerialNo = null;
    //     $latestOtherReceiptSerialNo = null;

    //     $sortedTestGeneralDetails = [];
    //     foreach ($otherReceipts as $labTest) {
    //         if (isset($labTest['testGeneralDetails'])) {
    //             foreach ($labTest['testGeneralDetails'] as $testGeneralDetail) {
    //                 $sortedTestGeneralDetails[] = $testGeneralDetail;
    //             }
    //         }
    //     }

    //     if (!empty($sortedTestGeneralDetails)) {
    //         usort($sortedTestGeneralDetails, function ($a, $b) {
    //             return $a['lab_serial'] <=> $b['lab_serial'];
    //         });

    //         $earliestOtherReceipt = $sortedTestGeneralDetails[0];
    //         $latestOtherReceipt = end($sortedTestGeneralDetails);

    //         $earliestOtherReceiptSerialNo = $earliestOtherReceipt['lab_serial'];
    //         $latestOtherReceiptSerialNo = $latestOtherReceipt['lab_serial'];
    //     }
    //     foreach ($otherReceipts as $labTest) {
    //         $totalOtherReceiptsAmount = 0;
    //         $totalOtherReceiptsZFFee = 0;
    //         $totalOtherReceiptsDiscountValue = 0;
    //         $totalOtherReceiptsDonorFee = 0;
    //         $cancelledPatientTestsCount = 0;
    //         $cancelledPatientTestsAmount = 0;
    //         if (isset($labTest['testGeneralDetails'])) {

    //             $labSerials = [];
    //             $labTestCounts = [];
    //             foreach ($labTest['testGeneralDetails'] as $testGeneralDetail) {
    //                 // return $labTest['id'];
    //                 if (isset($testGeneralDetail['patientTest'])) {

    //                     // $zfFeeShare = $patientTest['zf_fee'] ?? 0;
    //                     // $donorFeeShare = $patientTest['donor_fee'] ?? 0;
    //                     // $discountValueShare = $patientTest['discount_value'] ?? 0;
    //                     $patientTest = $testGeneralDetail['patientTest'] ?? 0;
    //                     $testDate = \Carbon\Carbon::parse($patientTest->test_date)->format('Y-m-d');
    //                     if ($testDate >= $from_date_only && $testDate <= $to_date_only) {
    //                         if ($testGeneralDetail['lab_test_id'] ==  $labTest['id']) {
    //                             $labSerials[] = $testGeneralDetail['lab_serial'];
    //                         }

    //                         $zfFeeShare = $patientTest['zf_fee'] ?? 0;
    //                         $donorFeeShare = $patientTest['donor_fee'] ?? 0;
    //                         $discountValueShare = $patientTest['discount_value'] ?? 0;
    //                         if ($patientTest->cancel == 1) {
    //                             $cancelledPatientTestsCount++;
    //                             $cancelledPatientTestsAmount += (float) ($patientTest->total_amount ?? 0);
    //                         }
    //                         if (!isset($labTestCounts[$testGeneralDetail['lab_test_id']])) {
    //                             $labTestCounts[$testGeneralDetail['lab_test_id']] = 0;
    //                         }
    //                         $labTestCounts[$testGeneralDetail['lab_test_id']]++;
    //                         $totalOtherReceiptsZFFee += $patientTest['zf_fee'];

    //                         // $testGeneralDetailsCount = count($labTest['testGeneralDetails']);
    //                         $totalOtherReceiptsAmount += (float) $labTest->price;
    //                         $totalOtherReceiptsDiscountValue += (float) $discountValueShare;
    //                         $totalOtherReceiptsDonorFee += (float) $donorFeeShare;
    //                         $earliestLabSerial = !empty($labSerials) ? min($labSerials) : null;
    //                         $latestLabSerial = !empty($labSerials) ? max($labSerials) : null;
    //                     }
    //                 }
    //             }
    //         }

    //         $parms = [
    //             'service_name' => $labTest['name'] ?? '',
    //             'fee' => $totalOtherReceiptsAmount,
    //             // 'patients' => $testGeneralDetailsCount,
    //             'patients' => $labTestCounts[$labTest['id']] ?? 0,
    //             'zf' => $totalOtherReceiptsZFFee,
    //             'discount_amount' => $totalOtherReceiptsDiscountValue,
    //             'careOff' => $totalOtherReceiptsDonorFee,
    //             'from' => $earliestLabSerial,
    //             'to' => $latestLabSerial,
    //             'amount' => $cancelledPatientTestsAmount,
    //         ];

    //         if (!isset($data['LAB & OTHER RECEIPTS'])) {
    //             $data['LAB & OTHER RECEIPTS'] = [];
    //         }

    //         $data['LAB & OTHER RECEIPTS'][] = $parms;
    //     }
    //     $donorFunds = DonorFund::with('donor')
    //         ->where(function ($query) use ($from_date_only, $to_date_only) {
    //             $fromDateTime = $from_date_only;
    //             $toDateTime = $to_date_only;
    //             $query->whereBetween('donor_date', [$from_date_only, $to_date_only]);
    //         })
    //         ->get();
    //     if (!isset($data['LAB & OTHER RECEIPTS'])) {
    //         $data['LAB & OTHER RECEIPTS'] = [];
    //     }
    //     foreach ($donorFunds as $donorFund) {
    //         $donorName = $donorFund->donor->name ?? null;
    //         $donorSerial = $donorFund->donor_serial;
    //         $totalFee = $donorFund->charges;
    //         $data['LAB & OTHER RECEIPTS'][] = [
    //             'service_name' => $donorName,
    //             'fee' => $totalFee,
    //             'from' => $donorSerial,
    //             'to' => $donorSerial,
    //             'patients' => '1',
    //         ];
    //     }
    //     $donations = Donation::where(function ($query) use ($from_date_only, $to_date_only) {
    //             $fromDateTime = $from_date_only;
    //             $toDateTime = $to_date_only;
    //             $query->whereBetween('donor_date', [$from_date_only, $to_date_only]);
    //         })
    //         ->get();

    //     if (!isset($data['LAB & OTHER RECEIPTS'])) {
    //         $data['LAB & OTHER RECEIPTS'] = [];
    //     }

    //     foreach ($donations as $donation) {
    //         $donorName = $donation->donor ?? null;
    //         $donorSerial = $donation->donor_serial;
    //         $totalFee = $donation->charges;
    //         $data['LAB & OTHER RECEIPTS'][] = [
    //             'service_name' => $donorName,
    //             'fee' => $totalFee,
    //             'from' => $donorSerial,
    //             'to' => $donorSerial,
    //             'patients' => '1',
    //         ];
    //     }
    //     $specialistDoctorFees = Appointment::with(['appointmentDetails.service.category', 'careoff', 'zf', 'doctor'])
    //         ->whereHas('doctor', function ($query) {
    //             $query->where('doctor_type', 'Specialist');
    //         })
    //         ->where(function ($query) use ($from_date_only, $to_date_only, $search) {
    //             $query->whereBetween('appointment_date', [$from_date_only, $to_date_only]);
    //                 // ->whereBetween('appointment_time', [$from_time_only, $to_time_only]);

    //             if ($search) {
    //                 $query->where(function ($q) use ($search) {
    //                     $q->where('appointment_date', 'like', '%' . $search . '%')
    //                         ->orWhereHas('appointmentDetails.service', function ($q) use ($search) {
    //                             $q->where('name', 'like', '%' . $search . '%');
    //                         });
    //                 });
    //             }
    //         });

    //     $specialistDoctorFees = $specialistDoctorFees->get();
    //     $earliestAppointment = Appointment::whereHas('doctor', function ($query) {
    //         $query->where('doctor_type', 'Specialist');
    //     })
    //         ->whereBetween('appointment_date', [$from_date_only, $to_date_only])
    //         ->orderBy('appointment_date', 'asc')
    //         ->orderBy('id', 'asc')
    //         ->first();
    //         // ->whereBetween('appointment_time', [$from_time_only, $to_time_only])
    //     $latestAppointment = Appointment::whereHas('doctor', function ($query) {
    //         $query->where('doctor_type', 'Specialist');
    //     })
    //         ->whereBetween('appointment_date', [$from_date_only, $to_date_only])
    //         ->orderBy('appointment_date', 'desc')
    //         ->orderBy('id', 'desc')
    //         ->first();
    //         // ->whereBetween('appointment_time', [$from_time_only, $to_time_only])
    //         // dd($latestAppointment);
    //     $specialistFees = [];
    //     $totalFee = 0;
    //     $totalPatients = 0;
    //     $totalDonorFee = 0;
    //     $totalZfFee = 0;
    //     $totalCancelledAppointments = 0;
    //     $totalCancelledFee = 0;
    //     foreach ($specialistDoctorFees as $appointment) {
    //         $totalPatients++;
    //         $totalDonorFee += isset($appointment->donor_fee) ? (float) $appointment->donor_fee : 0;
    //         $totalZfFee += isset($appointment->zf_fee) ? (float) $appointment->zf_fee : 0;

    //         if (isset($appointment->cancel) && $appointment->cancel == 1) {
    //             $totalCancelledAppointments++;
    //             foreach ($appointment->appointmentDetails as $detail) {
    //                 $totalCancelledFee += isset($detail['fee']) ? (float) $detail['fee'] : 0;
    //             }
    //         } else {
    //             foreach ($appointment->appointmentDetails as $detail) {
    //                 $totalFee += isset($detail['fee']) ? (float) $detail['fee'] : 0;
    //             }
    //         }
    //     }

    //     $specialistFees = [
    //         'total_fee' => $totalFee,
    //         'total_patients' => $totalPatients,
    //         'total_donor_fee' => $totalDonorFee,
    //         'total_zf_fee' => $totalZfFee,
    //         // 'from' => $earliestAppointment->doc_serial ?? 1,
    //         'from' =>  1,
    //         // 'to' => $latestAppointment->doc_serial ?? 0,
    //         'to' => $totalPatients,
    //         'cancelled_patients_total_amount' => $totalCancelledFee,
    //     ];

    //     if (!isset($data['Others'])) {
    //         $data['Others'] = [];
    //     }

    //     $parms = [];
    //     $parms['service_name'] = 'PVT CONSULTANT RECEIPT';
    //     $parms['fee'] = $specialistFees['total_fee'];
    //     $parms['patients'] = $specialistFees['total_patients'];
    //     $parms['zf'] = $specialistFees['total_zf_fee'];
    //     $parms['careOff'] = $specialistFees['total_donor_fee'];
    //     $parms['from'] = $specialistFees['from'];
    //     $parms['to'] = $specialistFees['to'];
    //     $parms['discount_amount'] = 0;
    //     $parms['amount'] = $specialistFees['cancelled_patients_total_amount'];
    //     $data['Others'][] = $parms;

    //     $advance_admissions = new Admission;
    //     // $advance_admissions = $advance_admissions->whereBetween('admission_date', [$from_date, date('Y-m-d H:i', strtotime($to_date . ' +1 day'))])->where(function($q){
    //     $advance_admissions = $advance_admissions->whereBetween('admission_date', [$from_date, $to_date])->where(function ($q) {
    //         $q->whereNotNull('advance_amount')->where('advance_amount', '>=', '0');
    //     })->get();
    //     $earliestAdvanceAdmission = $advance_admissions->sortBy('id')->first();
    //     $latestAdvanceAdmission = $advance_admissions->sortByDesc('id')->first();
    //     $canceled_advance_admissions = Admission::whereBetween('admission_date', [$from_date, $to_date])
    //         ->where('cancel', 1)
    //         ->whereNotNull('advance_amount')
    //         ->where('advance_amount', '>=', '0')
    //         ->get();
    //     $canceled_count = $canceled_advance_admissions->count();
    //     $canceled_sum = $canceled_advance_admissions->sum('advance_amount');
    //     $parms = [];
    //     $parms['service_id'] = null;
    //     $parms['service_name'] = 'Advance';
    //     $parms['fee'] = $advance_admissions->sum('advance_amount');
    //     $parms['patients'] = $advance_admissions->count();
    //     $parms['careOff'] = 0;
    //     $parms['zf'] = 0;
    //     $parms['discount_amount'] = 0;
    //     $parms['amount'] = $canceled_sum;
    //     $parms['from'] = $earliestAdvanceAdmission ? $earliestAdvanceAdmission->Ipd_serial : 0;
    //     $parms['to'] = $latestAdvanceAdmission ? $latestAdvanceAdmission->Ipd_serial : 0;
    //     $data['Billing'][] = $parms;

    //     $received_admissions = Admission::whereBetween('discharge_date', [$from_date, $to_date])
    //         ->where('status', 'Complete')
    //         ->get();

    //     $earliestReceivedAdmission = $received_admissions->sortBy('discharge_date')->first();
    //     $latestReceivedAdmission = $received_admissions->sortByDesc('discharge_date')->first();
    //     $refund_sum = Admission::whereBetween('discharge_date', [$from_date, $to_date])
    //         ->where('status', 'Complete')
    //         ->sum('refund_amount');

    //     $received_sum = Admission::whereBetween('discharge_date', [$from_date, $to_date])
    //         ->where('status', 'Complete')
    //         ->sum('received_amount');
    //     $parms = [];
    //     $parms['service_id'] = null;
    //     $parms['service_name'] = 'Indoor Bill';
    //     // $parms['fee'] = ($received_admissions->sum('total_amount') < 0 ? $received_admissions->sum('total_amount') : -$received_admissions->sum('total_amount'));
    //     $parms['fee'] = $received_admissions->sum('donor_fee') + $received_admissions->sum('zf_fee') + $received_admissions->sum('discount_amount');
    //     $parms['patients'] = $received_admissions->count();
    //     $parms['careOff'] = $received_admissions->whereNotNull('careoff_id')->sum('donor_fee');
    //     $parms['zf'] = $received_admissions->sum('zf_fee');
    //     $parms['discount_amount'] = $received_admissions->sum('discount_amount');
    //     $parms['from'] = $earliestReceivedAdmission ? $earliestReceivedAdmission->Ipd_serial : 0;
    //     $parms['to'] = $latestReceivedAdmission ? $latestReceivedAdmission->Ipd_serial : 0;
    //     $parms['refund'] = $refund_sum;
    //     $parms['received'] = $received_sum;

    //     $data['Billing'][] = $parms;


    //     $expenses = Expense::whereBetween('date', [$from_date, $to_date])->get();
    //     foreach ($expenses as $expense) {
    //         $parms = [];
    //         $parms['service_id'] = null;
    //         $parms['service_name'] = $expense->description;
    //         $parms['patients'] = 1;
    //         $parms['fee'] =
    //             $parms['careOff'] = 0;
    //         $parms['zf'] = 0;
    //         $parms['discount_amount'] = 0;
    //         $parms['from'] = $expense->serial_no;
    //         $parms['to'] = $expense->serial_no;
    //         $parms['amount'] = $expense->amount;
    //         $parms['notes'] = $expense->notes;
    //         $data['Expenses'][] = $parms;
    //     }

    //     $stats = [];

    //     foreach ($data as $index => $item) {
    //         $stats[$index]['patients'] = collect($item)->sum('patients');
    //         $stats[$index]['fee'] = collect($item)->sum('fee');
    //         $stats[$index]['careOff'] = collect($item)->sum('careOff');
    //         $stats[$index]['zf'] = collect($item)->sum('zf');
    //         $stats[$index]['discount_amount'] = collect($item)->sum('discount_amount');
    //         $stats[$index]['total_amount'] = collect($item)->sum('amount');
    //         $stats[$index]['refund'] = collect($item)->sum('refund');
    //         $stats[$index]['received'] = collect($item)->sum('received');
    //     }
    //     // return $data;
    //     return Inertia::render('OPD/Reports/SummaryByDate', [
    //         'stats' => $stats,
    //         'reports' => $data,
    //         // 'result' => $result,
    //         'from_date' => date('d-m-Y', strtotime($from_date)),
    //         'to_date' => date('d-m-Y', strtotime($to_date)),
    //         // 'from_date' => $from_date,
    //         // 'to_date' => $to_date,
    //         'search' => $search,
    //     ]);
    // }
    // public function summaryByDateReport()
    // {
    //     $appointments = new Appointment();
    //     $search = '';
    //     $from_date = date('Y-m-d');
    //     $to_date = date('Y-m-d');

    //     if (isset($_GET['from_date']) && $_GET['from_date'] != '') {
    //         $from_date = DateTime::createFromFormat('d-m-Y', $_GET['from_date']);
    //         if (!$from_date) {
    //             throw new \Exception('Invalid from_date format. Expected d-m-Y.');
    //         }
    //         $from_date = $from_date->format('Y-m-d');
    //     }

    //     if (isset($_GET['to_date']) && $_GET['to_date'] != '') {
    //         $to_date = DateTime::createFromFormat('d-m-Y', $_GET['to_date']);
    //         if (!$to_date) {
    //             throw new \Exception('Invalid to_date format. Expected d-m-Y.');
    //         }
    //         $to_date = $to_date->format('Y-m-d');
    //     }

    //     if (isset($_GET['search']) && $_GET['search'] != '') {
    //         $search = $_GET['search'];
    //     }

    //     $from_date_only = date('Y-m-d', strtotime($from_date));
    //     $to_date_only = date('Y-m-d', strtotime($to_date));

    //     $appointments = $appointments->with(['appointmentDetails.service.category', 'careoff', 'zf', 'doctor'])
    //         ->whereHas('doctor', function ($query) {
    //             $query->where('doctor_type', 'General');
    //         })
    //         ->where(function ($query) use ($from_date_only, $to_date_only, $search) {
    //             $query->whereBetween('appointment_date', [$from_date_only, $to_date_only]);

    //             if ($search) {
    //                 $query->where(function ($q) use ($search) {
    //                     $q->where('appointment_date', 'like', '%' . $search . '%')
    //                         ->orWhereHas('appointmentDetails.service', function ($q) use ($search) {
    //                             $q->where('name', 'like', '%' . $search . '%');
    //                         });
    //                 });
    //             }
    //         });

    //     $appointments = $appointments->get();
    //     $data = [];
    //     $doctorData = [];

    //     foreach ($appointments as $appointment) {
    //         foreach ($appointment->appointmentDetails as $appointmentDetail) {
    //             $doctorId = $appointment->consulting_doctor_id;
    //             $cancel = $appointment->cancel;
    //             $fee = $appointmentDetail->fee;
    //             $donorFee = $appointment->donor_fee;
    //             $zfFee = $appointment->zf_fee;

    //             if (!isset($doctorData[$doctorId])) {
    //                 $doctorData[$doctorId] = [
    //                     'doctor_name' => $appointment->doctor->name,
    //                     'doctor_fee' => $appointmentDetail->fee,
    //                     'total_patients' => 0,
    //                     'total_fee' => 0,
    //                     'total_donor_fee' => 0,
    //                     'total_zf_fee' => 0,
    //                     'from_serial' => null,
    //                     'to_serial' => null,
    //                     'total_canceled' => 0,
    //                     'amount' => 0,
    //                 ];
    //             }

    //             if ($cancel === 0) {
    //                 $doctorData[$doctorId]['total_patients'] += 1;
    //                 $doctorData[$doctorId]['total_fee'] += $fee;
    //                 $doctorData[$doctorId]['total_donor_fee'] += $donorFee;
    //                 $doctorData[$doctorId]['total_zf_fee'] += $zfFee;
    //             }

    //             if ($cancel) {
    //                 $doctorData[$doctorId]['total_canceled'] += 1;
    //             }

    //             $earliestAppointment = Appointment::where('consulting_doctor_id', $doctorId)
    //                 ->whereDate('appointment_date', '>=', $from_date_only)
    //                 ->orderBy('appointment_date', 'asc')
    //                 ->orderBy('id', 'asc')
    //                 ->first();

    //             if ($earliestAppointment) {
    //                 $doctorData[$doctorId]['from_serial'] = $earliestAppointment->doc_serial;
    //             }

    //             $latestAppointment = Appointment::where('consulting_doctor_id', $doctorId)
    //                 ->whereDate('appointment_date', '<=', $to_date_only)
    //                 ->orderBy('appointment_date', 'desc')
    //                 ->orderBy('id', 'desc')
    //                 ->first();

    //             if ($latestAppointment) {
    //                 $doctorData[$doctorId]['to_serial'] = $latestAppointment->doc_serial;
    //             }

    //             $doctorData[$doctorId]['amount'] = $doctorData[$doctorId]['total_canceled'] * $fee;
    //         }
    //     }

    //     foreach ($doctorData as $doctorId => $doctor) {
    //         $parms = [];
    //         $parms['doctor_id'] = $doctorId;
    //         $parms['service_name'] = $doctor['doctor_name'];
    //         $parms['patients'] = $doctor['total_patients'];
    //         $parms['fee'] = $doctor['total_fee'];
    //         $parms['doctor_fee'] = $doctor['doctor_fee'];
    //         $parms['careOff'] = $doctor['total_donor_fee'];
    //         $parms['zf'] = $doctor['total_zf_fee'];
    //         $parms['from'] = $doctor['from_serial'];
    //         $parms['to'] = $doctor['to_serial'];
    //         $parms['amount'] = $doctor['amount'];
    //         $data['MO EYE OPD RECEIPT'][] = $parms;
    //     }

    //     $totalLabTestsAmount = 0;
    //     $totalLabTestsCount = 0;
    //     $totalLabTestsZFFee = 0;
    //     $totalLabTestsDiscountValue = 0;
    //     $totalLabTestsDonorFee = 0;
    //     $totalOtherReceiptsAmount = 0;
    //     $totalOtherReceiptsZFFee = 0;
    //     $totalOtherReceiptsDiscountValue = 0;
    //     $totalOtherReceiptsDonorFee = 0;

    //     $patientTests = PatientTest::with(['testGeneralDetails.labTest'])
    //         ->whereBetween('test_date', [$from_date_only, $to_date_only])
    //         ->whereHas('testGeneralDetails.labTest', function ($query) {
    //             $query->where('lab_type', 'lab_test')->whereNotNull('lab_type');
    //         })
    //         ->get();

    //     $earliestLabTest = $patientTests->filter(function ($patientTest) {
    //         return $patientTest->testGeneralDetails?->labTest?->lab_type === 'lab_test'
    //             && $patientTest->lab_serial !== null;
    //     })->sortBy('lab_serial')->first();

    //     $latestLabTest = $patientTests->filter(function ($patientTest) {
    //         return $patientTest->testGeneralDetails?->labTest?->lab_type === 'lab_test'
    //             && $patientTest->lab_serial !== null;
    //     })->sortByDesc('lab_serial')->first();

    //     foreach ($patientTests as $patientTest) {
    //         $testGeneralDetail = $patientTest->testGeneralDetails;
    //         if ($testGeneralDetail) {
    //             $labTest = $testGeneralDetail->labTest;
    //             if ($labTest !== null && $labTest->lab_type === 'lab_test') {
    //                 $totalLabTestsAmount += (float) ($patientTest->total_amount ?? 0);
    //                 $totalLabTestsZFFee += (float) ($patientTest->zf_fee ?? 0);
    //                 $totalLabTestsDiscountValue += (float) ($patientTest->discount_value ?? 0);
    //                 $totalLabTestsDonorFee += (float) ($patientTest->donor_fee ?? 0);
    //                 $totalLabTestsCount += 1;
    //             }
    //         }
    //     }

    //     $canceledPatientTests = $patientTests->filter(function ($test) {
    //         return $test->cancel === 1;
    //     });

    //     $canceledTotalAmount = $canceledPatientTests->sum('total_amount');
    //     $labTestsSummary = [
    //         'service_name' => 'Lab Test',
    //         'fee' => $totalLabTestsAmount,
    //         'patients' => $totalLabTestsCount,
    //         'careOff' => $totalLabTestsDonorFee,
    //         'zf' => $totalLabTestsZFFee,
    //         'amount' => $canceledTotalAmount,
    //         'discount_amount' => $totalLabTestsDiscountValue,
    //         'from' => $earliestLabTest ? $earliestLabTest->lab_serial : null,
    //         'to' => $latestLabTest ? $latestLabTest->lab_serial : null
    //     ];

    //     if (!isset($data['LAB & OTHER RECEIPTS'])) {
    //         $data['LAB & OTHER RECEIPTS'] = [];
    //     }

    //     $data['LAB & OTHER RECEIPTS'][] = $labTestsSummary;

    //     $otherReceipts = LabTest::with(['testGeneralDetails.patientTest'])
    //         ->where('lab_type', 'other_receipts')->whereNotNull('lab_type')
    //         ->whereHas('testGeneralDetails.patientTest', function ($query) use ($from_date_only, $to_date_only) {
    //             $query->whereBetween('test_date', [$from_date_only, $to_date_only]);
    //         })
    //         ->get();

    //     $earliestOtherReceiptSerialNo = null;
    //     $latestOtherReceiptSerialNo = null;

    //     $sortedTestGeneralDetails = [];
    //     foreach ($otherReceipts as $labTest) {
    //         if (isset($labTest['testGeneralDetails'])) {
    //             foreach ($labTest['testGeneralDetails'] as $testGeneralDetail) {
    //                 $sortedTestGeneralDetails[] = $testGeneralDetail;
    //             }
    //         }
    //     }

    //     if (!empty($sortedTestGeneralDetails)) {
    //         usort($sortedTestGeneralDetails, function ($a, $b) {
    //             return $a['lab_serial'] <=> $b['lab_serial'];
    //         });

    //         $earliestOtherReceipt = $sortedTestGeneralDetails[0];
    //         $latestOtherReceipt = end($sortedTestGeneralDetails);

    //         $earliestOtherReceiptSerialNo = $earliestOtherReceipt['lab_serial'];
    //         $latestOtherReceiptSerialNo = $latestOtherReceipt['lab_serial'];
    //     }

    //     foreach ($otherReceipts as $labTest) {
    //         $totalOtherReceiptsAmount = 0;
    //         $totalOtherReceiptsZFFee = 0;
    //         $totalOtherReceiptsDiscountValue = 0;
    //         $totalOtherReceiptsDonorFee = 0;
    //         $cancelledPatientTestsCount = 0;
    //         $cancelledPatientTestsAmount = 0;

    //         if (isset($labTest['testGeneralDetails'])) {
    //             $labSerials = [];
    //             $labTestCounts = [];

    //             foreach ($labTest['testGeneralDetails'] as $testGeneralDetail) {
    //                 if (isset($testGeneralDetail['patientTest'])) {
    //                     $patientTest = $testGeneralDetail['patientTest'] ?? 0;
    //                     $testDate = \Carbon\Carbon::parse($patientTest->test_date)->format('Y-m-d');

    //                     if ($testDate >= $from_date_only && $testDate <= $to_date_only) {
    //                         if ($testGeneralDetail['lab_test_id'] == $labTest['id']) {
    //                             $labSerials[] = $testGeneralDetail['lab_serial'];
    //                         }

    //                         $zfFeeShare = $patientTest['zf_fee'] ?? 0;
    //                         $donorFeeShare = $patientTest['donor_fee'] ?? 0;
    //                         $discountValueShare = $patientTest['discount_value'] ?? 0;

    //                         if ($patientTest->cancel == 1) {
    //                             $cancelledPatientTestsCount++;
    //                             $cancelledPatientTestsAmount += (float) ($patientTest->total_amount ?? 0);
    //                         }

    //                         if (!isset($labTestCounts[$testGeneralDetail['lab_test_id']])) {
    //                             $labTestCounts[$testGeneralDetail['lab_test_id']] = 0;
    //                         }

    //                         $labTestCounts[$testGeneralDetail['lab_test_id']]++;
    //                         $totalOtherReceiptsZFFee += $patientTest['zf_fee'];
    //                         $totalOtherReceiptsAmount += (float) $labTest->price;
    //                         $totalOtherReceiptsDiscountValue += (float) $discountValueShare;
    //                         $totalOtherReceiptsDonorFee += (float) $donorFeeShare;
    //                         $earliestLabSerial = !empty($labSerials) ? min($labSerials) : null;
    //                         $latestLabSerial = !empty($labSerials) ? max($labSerials) : null;
    //                     }
    //                 }
    //             }
    //         }

    //         $parms = [
    //             'service_name' => $labTest['name'] ?? '',
    //             'fee' => $totalOtherReceiptsAmount,
    //             'patients' => $labTestCounts[$labTest['id']] ?? 0,
    //             'zf' => $totalOtherReceiptsZFFee,
    //             'discount_amount' => $totalOtherReceiptsDiscountValue,
    //             'careOff' => $totalOtherReceiptsDonorFee,
    //             'from' => $earliestLabSerial,
    //             'to' => $latestLabSerial,
    //             'amount' => $cancelledPatientTestsAmount,
    //         ];

    //         if (!isset($data['LAB & OTHER RECEIPTS'])) {
    //             $data['LAB & OTHER RECEIPTS'] = [];
    //         }

    //         $data['LAB & OTHER RECEIPTS'][] = $parms;
    //     }

    //     $donorFunds = DonorFund::with('donor')
    //         ->where(function ($query) use ($from_date_only, $to_date_only) {
    //             $query->whereBetween('donor_date', [$from_date_only, $to_date_only]);
    //         })
    //         ->get();

    //     if (!isset($data['LAB & OTHER RECEIPTS'])) {
    //         $data['LAB & OTHER RECEIPTS'] = [];
    //     }

    //     foreach ($donorFunds as $donorFund) {
    //         $donorName = $donorFund->donor->name ?? null;
    //         $donorSerial = $donorFund->donor_serial;
    //         $totalFee = $donorFund->charges;
    //         $data['LAB & OTHER RECEIPTS'][] = [
    //             'service_name' => $donorName,
    //             'fee' => $totalFee,
    //             'from' => $donorSerial,
    //             'to' => $donorSerial,
    //             'patients' => '1',
    //         ];
    //     }

    //     $donations = Donation::where(function ($query) use ($from_date_only, $to_date_only) {
    //             $query->whereBetween('donor_date', [$from_date_only, $to_date_only]);
    //         })
    //         ->get();

    //     if (!isset($data['LAB & OTHER RECEIPTS'])) {
    //         $data['LAB & OTHER RECEIPTS'] = [];
    //     }

    //     foreach ($donations as $donation) {
    //         $donorName = $donation->donor ?? null;
    //         $donorSerial = $donation->donor_serial;
    //         $totalFee = $donation->charges;
    //         $data['LAB & OTHER RECEIPTS'][] = [
    //             'service_name' => $donorName,
    //             'fee' => $totalFee,
    //             'from' => $donorSerial,
    //             'to' => $donorSerial,
    //             'patients' => '1',
    //         ];
    //     }

    //     $specialistAppointments = Appointment::with(['appointmentDetails.service.category', 'careoff', 'zf', 'doctor'])
    //         ->whereHas('doctor', function ($query) {
    //             $query->where('doctor_type', 'Specialist');
    //         })
    //         ->where(function ($query) use ($from_date_only, $to_date_only, $search) {
    //             $query->whereBetween('appointment_date', [$from_date_only, $to_date_only]);

    //             if ($search) {
    //                 $query->where(function ($q) use ($search) {
    //                     $q->where('appointment_date', 'like', '%' . $search . '%')
    //                         ->orWhereHas('appointmentDetails.service', function ($q) use ($search) {
    //                             $q->where('name', 'like', '%' . $search . '%');
    //                         });
    //                 });
    //             }
    //         });

    //     $specialistAppointments = $specialistAppointments->get();
    //     $doctorData = [];

    //     foreach ($specialistAppointments as $appointment) {
    //         foreach ($appointment->appointmentDetails as $appointmentDetail) {
    //             $doctorId = $appointment->consulting_doctor_id;
    //             $cancel = $appointment->cancel;
    //             $fee = $appointmentDetail->fee;
    //             $donorFee = $appointment->donor_fee;
    //             $zfFee = $appointment->zf_fee;

    //             if (!isset($doctorData[$doctorId])) {
    //                 $doctorData[$doctorId] = [
    //                     'doctor_name' => $appointment->doctor->name,
    //                     'doctor_fee' => $appointmentDetail->fee,
    //                     'total_patients' => 0,
    //                     'total_fee' => 0,
    //                     'total_donor_fee' => 0,
    //                     'total_zf_fee' => 0,
    //                     'from_serial' => null,
    //                     'to_serial' => null,
    //                     'total_canceled' => 0,
    //                     'amount' => 0,
    //                 ];
    //             }

    //             if ($cancel === 0) {
    //                 $doctorData[$doctorId]['total_patients'] += 1;
    //                 $doctorData[$doctorId]['total_fee'] += $fee;
    //                 $doctorData[$doctorId]['total_donor_fee'] += $donorFee;
    //                 $doctorData[$doctorId]['total_zf_fee'] += $zfFee;
    //             }

    //             if ($cancel) {
    //                 $doctorData[$doctorId]['total_canceled'] += 1;
    //             }

    //             $earliestAppointment = Appointment::where('consulting_doctor_id', $doctorId)
    //                 ->whereDate('appointment_date', '>=', $from_date_only)
    //                 ->orderBy('appointment_date', 'asc')
    //                 ->orderBy('id', 'asc')
    //                 ->first();

    //             if ($earliestAppointment) {
    //                 $doctorData[$doctorId]['from_serial'] = $earliestAppointment->doc_serial;
    //             }

    //             $latestAppointment = Appointment::where('consulting_doctor_id', $doctorId)
    //                 ->whereDate('appointment_date', '<=', $to_date_only)
    //                 ->orderBy('appointment_date', 'desc')
    //                 ->orderBy('id', 'desc')
    //                 ->first();

    //             if ($latestAppointment) {
    //                 $doctorData[$doctorId]['to_serial'] = $latestAppointment->doc_serial;
    //             }

    //             $doctorData[$doctorId]['amount'] = $doctorData[$doctorId]['total_canceled'] * $fee;
    //         }
    //     }

    //     foreach ($doctorData as $doctorId => $doctor) {
    //         $parms = [];
    //         $parms['doctor_id'] = $doctorId;
    //         $parms['service_name'] = $doctor['doctor_name'];
    //         $parms['patients'] = $doctor['total_patients'];
    //         $parms['fee'] = $doctor['total_fee'];
    //         $parms['doctor_fee'] = $doctor['doctor_fee'];
    //         $parms['careOff'] = $doctor['total_donor_fee'];
    //         $parms['zf'] = $doctor['total_zf_fee'];
    //         $parms['from'] = $doctor['from_serial'];
    //         $parms['to'] = $doctor['to_serial'];
    //         $parms['amount'] = $doctor['amount'];
    //         $data['PVT CONSULTANT RECEIPT'][] = $parms;
    //     }

    //     $advance_admissions = new Admission;
    //     $advance_admissions = $advance_admissions->whereBetween('admission_date', [$from_date, $to_date])->where(function ($q) {
    //         $q->whereNotNull('advance_amount')->where('advance_amount', '>=', '0');
    //     })->get();

    //     $earliestAdvanceAdmission = $advance_admissions->sortBy('id')->first();
    //     $latestAdvanceAdmission = $advance_admissions->sortByDesc('id')->first();

    //     $canceled_advance_admissions = Admission::whereBetween('admission_date', [$from_date, $to_date])
    //         ->where('cancel', 1)
    //         ->whereNotNull('advance_amount')
    //         ->where('advance_amount', '>=', '0')
    //         ->get();

    //     $canceled_count = $canceled_advance_admissions->count();
    //     $canceled_sum = $canceled_advance_admissions->sum('advance_amount');

    //     $parms = [];
    //     $parms['service_id'] = null;
    //     $parms['service_name'] = 'Advance';
    //     $parms['fee'] = $advance_admissions->sum('advance_amount');
    //     $parms['patients'] = $advance_admissions->count();
    //     $parms['careOff'] = 0;
    //     $parms['zf'] = 0;
    //     $parms['discount_amount'] = 0;
    //     $parms['amount'] = $canceled_sum;
    //     $parms['from'] = $earliestAdvanceAdmission ? $earliestAdvanceAdmission->Ipd_serial : 0;
    //     $parms['to'] = $latestAdvanceAdmission ? $latestAdvanceAdmission->Ipd_serial : 0;
    //     $data['Billing'][] = $parms;

    //     $received_admissions = Admission::whereBetween('discharge_date', [$from_date, $to_date])
    //         ->where('status', 'Complete')
    //         ->get();

    //     $earliestReceivedAdmission = $received_admissions->sortBy('discharge_date')->first();
    //     $latestReceivedAdmission = $received_admissions->sortByDesc('discharge_date')->first();

    //     $refund_sum = Admission::whereBetween('discharge_date', [$from_date, $to_date])
    //         ->where('status', 'Complete')
    //         ->sum('refund_amount');

    //     $received_sum = Admission::whereBetween('discharge_date', [$from_date, $to_date])
    //         ->where('status', 'Complete')
    //         ->sum('received_amount');

    //     $parms = [];
    //     $parms['service_id'] = null;
    //     $parms['service_name'] = 'Indoor Bill';
    //     $parms['fee'] = $received_admissions->sum('donor_fee') + $received_admissions->sum('zf_fee') + $received_admissions->sum('discount_amount');
    //     $parms['patients'] = $received_admissions->count();
    //     $parms['careOff'] = $received_admissions->whereNotNull('careoff_id')->sum('donor_fee');
    //     $parms['zf'] = $received_admissions->sum('zf_fee');
    //     $parms['discount_amount'] = $received_admissions->sum('discount_amount');
    //     $parms['from'] = $earliestReceivedAdmission ? $earliestReceivedAdmission->Ipd_serial : 0;
    //     $parms['to'] = $latestReceivedAdmission ? $latestReceivedAdmission->Ipd_serial : 0;
    //     $parms['refund'] = $refund_sum;
    //     $parms['received'] = $received_sum;

    //     $data['Billing'][] = $parms;

    //     $expenses = Expense::whereBetween('date', [$from_date, $to_date])->get();
    //     foreach ($expenses as $expense) {
    //         $parms = [];
    //         $parms['service_id'] = null;
    //         $parms['service_name'] = $expense->description;
    //         $parms['patients'] = 1;
    //         $parms['fee'] = $expense->amount;
    //         $parms['careOff'] = 0;
    //         $parms['zf'] = 0;
    //         $parms['discount_amount'] = 0;
    //         $parms['from'] = $expense->serial_no;
    //         $parms['to'] = $expense->serial_no;
    //         $parms['amount'] = $expense->amount;
    //         $parms['notes'] = $expense->notes;
    //         $data['Expenses'][] = $parms;
    //     }

    //     $stats = [];

    //     foreach ($data as $index => $item) {
    //         $stats[$index]['patients'] = collect($item)->sum('patients');
    //         $stats[$index]['fee'] = collect($item)->sum('fee');
    //         $stats[$index]['careOff'] = collect($item)->sum('careOff');
    //         $stats[$index]['zf'] = collect($item)->sum('zf');
    //         $stats[$index]['discount_amount'] = collect($item)->sum('discount_amount');
    //         $stats[$index]['total_amount'] = collect($item)->sum('amount');
    //         $stats[$index]['refund'] = collect($item)->sum('refund');
    //         $stats[$index]['received'] = collect($item)->sum('received');
    //     }

    //     return Inertia::render('OPD/Reports/SummaryByDate', [
    //         'stats' => $stats,
    //         'reports' => $data,
    //         'from_date' => date('d-m-Y', strtotime($from_date)),
    //         'to_date' => date('d-m-Y', strtotime($to_date)),
    //         'search' => $search,
    //     ]);
    // }
    public function summaryByShiftReport()
    {
        $shifts = Shift::get();
        $shiftDatas = [];
        foreach ($shifts as $key => $shift) {
            $appointments = new Appointment();
            $search = '';
            if (isset($_GET['search']) && $_GET['search'] != '') {
                $search = $_GET['search'];
            }
            // $from_date = isset($_GET['from_date']) ? date('Y-m-d H:i', strtotime($_GET['from_date'])) : date('Y-m-d 00:00');
            // $to_date = isset($_GET['to_date']) ? date('Y-m-d H:i', strtotime($_GET['to_date'])) : date('Y-m-d 23:59');
            // Validate and convert dates
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

                // Extract date-only and time-only components
                $from_date_only = date('Y-m-d', strtotime($from_date));
                $from_time_only = date('H:i', strtotime($from_date));
                $to_date_only = date('Y-m-d', strtotime($to_date));
                $to_time_only = date('H:i', strtotime($to_date));
            $appointments = $appointments->with(['appointmentDetails.service.category', 'careoff', 'zf', 'doctor'])
                ->whereHas('doctor', function ($query) {
                    $query->where('doctor_type', 'General');
                })
                ->where(function ($query) use ($from_date, $to_date, $shift, $search) {
                    $query->whereBetween('appointment_date', [$from_date, $to_date]);

                    $query->where(function ($q) use ($shift) {
                        if ($shift->start_time < $shift->end_time) {
                            $q->whereBetween('appointment_time', [$shift->start_time, $shift->end_time]);
                        } else {
                            $q->where(function ($query) use ($shift) {
                                $query->where('appointment_time', '>=', $shift->start_time)
                                    ->orWhere('appointment_time', '<=', $shift->end_time);
                            });
                        }
                    });

                    if ($search) {
                        $query->where(function ($q) use ($search) {
                            $q->where('appointment_date', 'like', '%' . $search . '%')
                                ->orWhereHas('appointmentDetails.service', function ($q) use ($search) {
                                    $q->where('name', 'like', '%' . $search . '%');
                                });
                        });
                    }
                });

            $appointments = $appointments->get();
            // return  $appointments;
            $doctorData = [];
            $totalDonorFee = 0;
            $totalZfFee = 0;
            foreach ($appointments as $appointment) {
                foreach ($appointment->appointmentDetails as $appointmentDetail) {
                    $doctorId = $appointment->consulting_doctor_id;
                    $doc_serial = $appointment->doc_serial;
                    $cancel = $appointment->cancel;
                    // return $cancel;
                    $fee = $appointmentDetail->fee;
                    $donorFee = $appointment->donor_fee;
                    $zfFee = $appointment->zf_fee;
                    if (!isset($doctorData[$doctorId])) {
                        $doctorData[$doctorId] = [
                            'doctor_name' => $appointment->doctor->name,
                            'doctor_fee' => $appointmentDetail->fee,
                            'total_patients' => 0,
                            'total_fee' => 0,
                            'total_donor_fee' => 0,
                            'total_zf_fee' => 0,
                            'total_canceled' => 0,
                            'from_serial' => null,
                            'to_serial' => null,
                            'amount' => 0,
                        ];
                    }
                    if ($cancel === 0) {
                        $doctorData[$doctorId]['total_patients'] += 1;
                        $doctorData[$doctorId]['total_fee'] += $fee;
                        $doctorData[$doctorId]['total_donor_fee'] += $donorFee;
                        $doctorData[$doctorId]['total_zf_fee'] += $zfFee;
                    }
                    if ($cancel) {
                        $doctorData[$doctorId]['total_canceled'] += 1;
                    }

                    $earliestAppointment = Appointment::where('consulting_doctor_id', $doctorId)
                        ->whereDate('appointment_date', '>=', $from_date_only)
                        ->whereTime('appointment_time', '>=', '00:00')
                        ->orderBy('appointment_date', 'asc')
                        ->orderBy('appointment_time', 'asc')
                        ->orderBy('id', 'asc')
                        ->first();
                    if ($earliestAppointment) {
                        $doctorData[$doctorId]['from_serial'] = $earliestAppointment->doc_serial;
                    }

                    $latestAppointment = Appointment::where('consulting_doctor_id', $doctorId)
                        ->whereDate('appointment_date', '<=', $to_date_only)
                        ->whereTime('appointment_time', '<=', '23:59:59')
                        ->orderBy('appointment_date', 'desc')
                        ->orderBy('appointment_time', 'desc')
                        ->orderBy('id', 'desc')
                        ->first();

                    if ($latestAppointment) {
                        $doctorData[$doctorId]['to_serial'] = $latestAppointment->doc_serial;
                    }
                    $doctorData[$doctorId]['amount'] = $doctorData[$doctorId]['total_canceled'] * $fee;
                }
            }

            foreach ($doctorData as $doctorId => $doctor) {
                $parms = [];
                $doctor;
                $parms['doctor_id'] = $doctorId;
                $parms['service_name'] = $doctor['doctor_name'];
                $parms['patients'] = $doctor['total_patients'];
                $parms['fee'] = $doctor['total_fee'];
                $parms['doctor_fee'] = $doctor['doctor_fee'];
                $parms['careOff'] = $doctor['total_donor_fee'];
                $parms['zf'] = $doctor['total_zf_fee'];
                $parms['from'] = $doctor['from_serial'];
                $parms['to'] = $doctor['to_serial'];
                $parms['amount'] = $doctor['amount'];
                $shiftDatas[$shift->name]['MO EYE OPD RECEIPT'][] = $parms;
            }

            $totalLabTestsAmount = 0;
            $totalLabTestsCount = 0;
            $totalLabTestsZFFee = 0;
            $totalLabTestsDiscountValue = 0;
            $totalLabTestsDonorFee = 0;
            $totalOtherReceiptsAmount = 0;
            $totalOtherReceiptsZFFee = 0;
            $totalOtherReceiptsDiscountValue = 0;
            $totalOtherReceiptsDonorFee = 0;

            $shiftStartTime = Carbon::parse($shift->start_time, 'Asia/Karachi')->setTimezone('UTC')->format('H:i:s');
            $shiftEndTime = Carbon::parse($shift->end_time, 'Asia/Karachi')->setTimezone('UTC')->format('H:i:s');

            $patientTests = PatientTest::with(['testGeneralDetails.labTest'])
                ->whereBetween('created_at', [$from_date, $to_date])
                ->where(function ($query) use ($shift) {
                    if ($shift->start_time < $shift->end_time) {
                        $query->whereTime('created_at', '>=', $shift->start_time)
                            ->whereTime('created_at', '<=', $shift->end_time);
                    } else {
                        $query->where(function ($query) use ($shift) {
                            $query->whereTime('created_at', '>=', $shift->start_time)
                                ->orWhereTime('created_at', '<=', $shift->end_time);
                        });
                    }
                })
                ->whereHas('testGeneralDetails.labTest', function ($query) {
                    $query->where('lab_type', 'lab_test');
                })
                ->get();

            $earliestLabTest = $patientTests->filter(function ($patientTest) {
                return $patientTest?->testGeneralDetails?->labTest?->lab_type === 'lab_test';
            })->sortBy('lab_serial')->first();

            $latestLabTest = $patientTests->filter(function ($patientTest) {
                return $patientTest?->testGeneralDetails?->labTest?->lab_type === 'lab_test';
            })->sortByDesc('lab_serial')->first();


            foreach ($patientTests as $patientTest) {
                $testGeneralDetail = $patientTest->testGeneralDetails;
                if ($testGeneralDetail) {
                    $labTest = $testGeneralDetail?->labTest;
                    if ($labTest !== null && $labTest?->lab_type === 'lab_test') {
                        $totalLabTestsAmount += (float) $patientTest->total_amount;
                        if (isset($patientTest->zf_fee)) {
                            $totalLabTestsZFFee += (float) $patientTest->zf_fee;
                        }
                        if (isset($patientTest->discount_value)) {
                            $totalLabTestsDiscountValue += (float) $patientTest->discount_value;
                        }
                        if (isset($patientTest->donor_fee)) {
                            $totalLabTestsDonorFee += (float) $patientTest->donor_fee;
                        }
                        $totalLabTestsCount += 1;
                    }
                }
            }
            $canceledPatientTests = $patientTests->filter(function ($test) {
                return $test->cancel === 1;
            });
            $canceledTotalAmount = $canceledPatientTests->sum('total_amount');

            $labTestsSummary = [
                'service_name' => 'Lab Test',
                'fee' => $totalLabTestsAmount,
                'patients' => $totalLabTestsCount,
                'careOff' => $totalLabTestsDonorFee,
                'zf' => $totalLabTestsZFFee,
                'discount_amount' => $totalLabTestsDiscountValue,
                'amount' => $canceledTotalAmount,
                'from' => $earliestLabTest ? $earliestLabTest->lab_serial : null,
                'to' => $latestLabTest ? $latestLabTest->lab_serial : null
            ];

            $shiftDatas[$shift->name]['LAB & OTHER RECEIPTS'][] = $labTestsSummary;

            $otherReceipts = LabTest::with(['testGeneralDetails.patientTest'])
                ->where('lab_type', 'other_receipts')
                ->whereHas('testGeneralDetails.patientTest', function ($query) use ($from_date, $to_date, $shift) {
                    $query->whereBetween('test_date', [$from_date, $to_date])
                        ->where(function ($query) use ($shift) {
                            if ($shift->start_time < $shift->end_time) {
                                $query->whereBetween('test_time', [$shift->start_time, $shift->end_time]);
                            } else {
                                $query->where(function ($q) use ($shift) {
                                    $q->where('test_time', '>=', $shift->start_time)
                                        ->orWhere('test_time', '<=', $shift->end_time);
                                });
                            }
                        })
                        ->whereNotNull('test_time');
                })
                ->distinct()
                ->get();

            foreach ($otherReceipts as $labTest) {
                $totalOtherReceiptsAmount = 0;
                $totalOtherReceiptsZFFee = 0;
                $totalOtherReceiptsDiscountValue = 0;
                $totalOtherReceiptsDonorFee = 0;
                $cancelledPatientTestsCount = 0;
                $cancelledPatientTestsAmount = 0;

                if (isset($labTest['testGeneralDetails'])) {
                    $labSerials = [];
                    $labTestCounts = [];

                    foreach ($labTest['testGeneralDetails'] as $testGeneralDetail) {
                        // Ensure patientTest is defined
                        $patientTest = $testGeneralDetail['patientTest'] ?? null;

                        if ($patientTest) {
                            $testDate = date('Y-m-d', strtotime($patientTest->test_date));
                            $testTime = date('H:i', strtotime($patientTest->test_time));

                            if ($testDate >= $from_date_only && $testDate <= $to_date_only) {
                                if (($shift->start_time < $shift->end_time && $testTime >= $shift->start_time && $testTime <= $shift->end_time) ||
                                    ($shift->start_time >= $shift->end_time && ($testTime >= $shift->start_time || $testTime <= $shift->end_time))
                                ) {

                                    // Collect lab serials
                                    if ($testGeneralDetail['lab_test_id'] == $labTest['id']) {
                                        $labSerials[] = $testGeneralDetail['lab_serial'];
                                    }

                                    // Collect fees and discounts
                                    $zfFeeShare = $patientTest->zf_fee ?? 0;
                                    $donorFeeShare = $patientTest->donor_fee ?? 0;
                                    $discountValueShare = $patientTest->discount_value ?? 0;

                                    // Handle cancelled tests
                                    if ($patientTest->cancel == 1) {
                                        $cancelledPatientTestsCount++;
                                        $cancelledPatientTestsAmount += (float) ($patientTest->total_amount ?? 0);
                                    }

                                    // Count the lab test occurrences
                                    if (!isset($labTestCounts[$testGeneralDetail['lab_test_id']])) {
                                        $labTestCounts[$testGeneralDetail['lab_test_id']] = 0;
                                    }
                                    $labTestCounts[$testGeneralDetail['lab_test_id']]++;

                                    // Aggregate total amounts
                                    $totalOtherReceiptsAmount += $labTest->price;
                                    $totalOtherReceiptsZFFee += (float) $zfFeeShare;
                                    $totalOtherReceiptsDiscountValue += (float) $discountValueShare;
                                    $totalOtherReceiptsDonorFee += (float) $donorFeeShare;
                                }
                            }
                        }
                    }

                    $earliestLabSerial = !empty($labSerials) ? min($labSerials) : null;
                    $latestLabSerial = !empty($labSerials) ? max($labSerials) : null;

                    $parms = [
                        'service_name' => $labTest['name'] ?? '',
                        'fee' => $totalOtherReceiptsAmount,
                        'patients' => $labTestCounts[$labTest['id']] ?? 0,
                        'zf' => $totalOtherReceiptsZFFee,
                        'discount_amount' => $totalOtherReceiptsDiscountValue,
                        'careOff' => $totalOtherReceiptsDonorFee,
                        'from' => $earliestLabSerial,
                        'to' => $latestLabSerial,
                        'amount' => $cancelledPatientTestsAmount,
                    ];

                    if (!isset($shiftDatas[$shift->name]['LAB & OTHER RECEIPTS'])) {
                        $shiftDatas[$shift->name]['LAB & OTHER RECEIPTS'] = [];
                    }

                    $shiftDatas[$shift->name]['LAB & OTHER RECEIPTS'][] = $parms;
                }
            }

            if(file_exists(base_path('config/donation.php'))) {
                $donorFunds = DonorFund::with('donor')
                    ->whereBetween('created_at', [$from_date, $to_date])
                    ->where(function ($query) use ($shift) {
                        if ($shift->start_time < $shift->end_time) {
                            $query->whereBetween('donor_time', [$shift->start_time, $shift->end_time]);
                        } else {
                            $query->where(function ($q) use ($shift) {
                                $q->where('donor_time', '>=', $shift->start_time)
                                    ->orWhere('donor_time', '<=', $shift->end_time);
                            });
                        }
                    })
                    ->whereNotNull('donor_time')
                    ->get();
            

                if (!isset($shiftDatas[$shift->name]['LAB & OTHER RECEIPTS'])) {
                    $shiftDatas[$shift->name]['LAB & OTHER RECEIPTS'] = [];
                }

                foreach ($donorFunds as $donorFund) {
                    $donorName = $donorFund->donor->name;
                    $donorSerial = $donorFund->donor_serial;
                    $totalFee = $donorFund->charges;

                    $shiftDatas[$shift->name]['LAB & OTHER RECEIPTS'][] = [
                        'service_name' => $donorName,
                        'fee' => $totalFee,
                        'from' => $donorSerial,
                        'to' => $donorSerial,
                        'patients' => '1',
                    ];
                }
            }
            $donations = Donation::whereBetween('created_at', [$from_date, $to_date])
                ->where(function ($query) use ($shift) {
                    if ($shift->start_time < $shift->end_time) {
                        $query->whereBetween('donor_time', [$shift->start_time, $shift->end_time]);
                    } else {
                        $query->where(function ($q) use ($shift) {
                            $q->where('donor_time', '>=', $shift->start_time)
                                ->orWhere('donor_time', '<=', $shift->end_time);
                        });
                    }
                })
                ->whereNotNull('donor_time')
                ->get();

            if (!isset($shiftDatas[$shift->name]['LAB & OTHER RECEIPTS'])) {
                $shiftDatas[$shift->name]['LAB & OTHER RECEIPTS'] = [];
            }

            foreach ($donations as $donation) {
                $donorName = $donation->donor;
                $donorSerial = $donation->donor_serial;
                $totalFee = $donation->charges;

                $shiftDatas[$shift->name]['LAB & OTHER RECEIPTS'][] = [
                    'service_name' => $donorName,
                    'fee' => $totalFee,
                    'from' => $donorSerial,
                    'to' => $donorSerial,
                    'patients' => '1',
                ];
            }

            $specialistDoctorFees = Appointment::with(['appointmentDetails.service.category', 'careoff', 'zf', 'doctor'])
                ->whereHas('doctor', function ($query) {
                    $query->where('doctor_type', 'Specialist');
                })
                ->where(function ($query) use ($from_date, $to_date, $shift, $search) {
                    $query->whereBetween('appointment_date', [$from_date, $to_date])
                        ->where(function ($query) use ($shift) {
                            if ($shift->start_time < $shift->end_time) {
                                // Normal shift case: same day start and end
                                $query->whereBetween('appointment_time', [$shift->start_time, $shift->end_time]);
                            } else {
                                $query->where(function ($q) use ($shift) {
                                    $q->where('appointment_time', '>=', $shift->start_time)
                                        ->orWhere('appointment_time', '<=', $shift->end_time);
                                });
                            }
                        });

                    if ($search) {
                        $query->where(function ($q) use ($search) {
                            $q->where('appointment_date', 'like', '%' . $search . '%')
                                ->orWhereHas('appointmentDetails.service', function ($q) use ($search) {
                                    $q->where('name', 'like', '%' . $search . '%');
                                });
                        });
                    }
                });

            $specialistDoctorFees = $specialistDoctorFees->get();
            $doctorDa = [];

            foreach ($specialistDoctorFees as $appointment) {
                foreach ($appointment->appointmentDetails as $appointmentDetail) {
                    $doctorId = $appointment->consulting_doctor_id;
                    $doc_serial = $appointment->doc_serial;
                    $cancel = $appointment->cancel;
                    $fee = $appointmentDetail->fee;
                    $donorFee = $appointment->donor_fee;
                    $zfFee = $appointment->zf_fee;
                    if (!isset($doctorDa[$doctorId])) {
                        $doctorDa[$doctorId] = [
                            'doctor_name' => $appointment->doctor->name,
                            'doctor_fee' => $appointmentDetail->fee,
                            'total_patients' => 0,
                            'total_fee' => 0,
                            'total_donor_fee' => 0,
                            'total_zf_fee' => 0,
                            'from_serial' => null,
                            'total_canceled' => 0,
                            'to_serial' => null,
                            'amount' => null,
                        ];
                    }
                    if ($cancel === 0) {
                        $doctorDa[$doctorId]['total_patients'] += 1;
                        $doctorDa[$doctorId]['total_fee'] += $fee;
                        $doctorDa[$doctorId]['total_donor_fee'] += $donorFee;
                        $doctorDa[$doctorId]['total_zf_fee'] += $zfFee;
                    }
                    if ($cancel) {
                        $doctorDa[$doctorId]['total_canceled'] += 1;
                    }
                    $earliestAppointment = Appointment::where('consulting_doctor_id', $doctorId)
                        ->whereDate('appointment_date', '>=', $from_date_only)
                        ->whereTime('appointment_time', '>=', $shift->start_time)
                        ->orderBy('appointment_date', 'asc')
                        ->orderBy('appointment_time', 'asc')
                        ->orderBy('id', 'asc')
                        ->first();
                    if ($earliestAppointment) {
                        $doctorDa[$doctorId]['from_serial'] = $earliestAppointment->doc_serial;
                    }

                    $latestAppointment = Appointment::where('consulting_doctor_id', $doctorId)
                        ->whereDate('appointment_date', '<=', $to_date_only)
                        ->whereTime('appointment_time', '<=', $shift->end_time)
                        ->orderBy('appointment_date', 'desc')
                        ->orderBy('appointment_time', 'desc')
                        ->orderBy('id', 'desc')
                        ->first();

                    if ($latestAppointment) {
                        $doctorDa[$doctorId]['to_serial'] = $latestAppointment->doc_serial;
                    }


                    $doctorDa[$doctorId]['amount'] = $doctorDa[$doctorId]['total_canceled'] * $fee;
                }
            }

            foreach ($doctorDa as $doctorId => $doctor) {

                $parms = [];
                $parms['doctor_id'] = $doctorId;
                $parms['service_name'] = $doctor['doctor_name'];
                $parms['patients'] = $doctor['total_patients'];
                $parms['fee'] = $doctor['total_fee'];
                $parms['doctor_fee'] = $doctor['doctor_fee'];
                $parms['careOff'] = $doctor['total_donor_fee'];
                $parms['zf'] = $doctor['total_zf_fee'];
                $parms['from'] = $doctor['from_serial'];
                $parms['to'] = $doctor['to_serial'];
                $parms['amount'] = $doctor['amount'];
                $shiftDatas[$shift->name]['Specialist'][] = $parms;
            }

            $advance_admissions = new Admission;
            $advance_admissions = $advance_admissions
                ->whereBetween('admission_date', [$from_date, $to_date])
                ->where(function ($query) use ($shift) {
                    if ($shift->start_time < $shift->end_time) {
                        $query->whereBetween('admission_time', [$shift->start_time, $shift->end_time]);
                    } else {
                        $query->where(function ($q) use ($shift) {
                            $q->where('admission_time', '>=', $shift->start_time)
                                ->orWhere('admission_time', '<=', $shift->end_time);
                        });
                    }
                })
                ->where(function ($q) {
                    $q->whereNotNull('advance_amount')
                        ->where('advance_amount', '>', 0);
                })
                ->get();

            $fee = $advance_admissions->sum('advance_amount');
            $patients = $advance_admissions->count();
            $fromIpdSerial = $advance_admissions->min('Ipd_serial');
            $toIpdSerial = $advance_admissions->max('Ipd_serial');

            $parms = [];
            $parms['service_id'] = null;
            $parms['service_name'] = 'Advance';
            $parms['fee'] = $fee;
            $parms['patients'] = $patients;
            $parms['from'] = $fromIpdSerial;
            $parms['to'] = $toIpdSerial;
            $parms['careOff'] = 0;
            $parms['zf'] = 0;
            $parms['discount_amount'] = 0;

            $shiftDatas[$shift->name]['Billing'][] = $parms;

            $received_admissions = new Admission;
            $received_admissions = $received_admissions
                ->whereBetween('discharge_date', [$from_date, $to_date])
                ->where(function ($query) use ($shift) {
                    if ($shift->start_time < $shift->end_time) {
                        $query->whereBetween('discharge_time', [$shift->start_time, $shift->end_time]);
                    } else {
                        $query->where(function ($q) use ($shift) {
                            $q->where('discharge_time', '>=', $shift->start_time)
                                ->orWhere('discharge_time', '<=', $shift->end_time);
                        });
                    }
                })
                ->where(function ($q) {
                    $q->where('status', 'Complete');
                })
                ->get();


            $fee =  $received_admissions->sum('donor_fee') + $received_admissions->sum('zf_fee') + $received_admissions->sum('discount_amount');

            $careOff = $received_admissions->whereNotNull('careoff_id')->sum('donor_fee');
            $zf = $received_admissions->sum('zf_fee');
            $received = $received_admissions->sum('received_amount');

            $refund = $received_admissions->sum('refund_amount');
            $discountAmount = $received_admissions->sum('discount_amount');

            $fromIpdSerial = $received_admissions->min('Ipd_serial') ?? 0;
            $toIpdSerial = $received_admissions->max('Ipd_serial') ?? 0;

            $parms = [];
            $parms['service_id'] = null;
            $parms['service_name'] = 'Indoor Bill';
            $parms['fee'] = $fee;
            $parms['patients'] = $received_admissions->count();
            $parms['careOff'] = $careOff;
            $parms['zf'] = $zf ?? 0;
            $parms['received'] = $received ?? 0;
            $parms['refund'] = $refund;
            $parms['discount_amount'] = $discountAmount ?? 0;
            $parms['from'] = $fromIpdSerial ?? 0;
            $parms['to'] = $toIpdSerial ?? 0;
            $shiftDatas[$shift->name]['Billing'][] = $parms;
            if(file_exists(base_path('config/expense.php'))) {
                $expenses = Expense::whereBetween('date', [$from_date, $to_date])
                    ->where(function ($query) use ($shift) {
                        if ($shift->start_time < $shift->end_time) {
                            $query->whereBetween('expense_time', [$shift->start_time, $shift->end_time]);
                        } else {
                            $query->where(function ($q) use ($shift) {
                                $q->where('expense_time', '>=', $shift->start_time)
                                    ->orWhere('expense_time', '<=', $shift->end_time);
                            });
                        }
                    })
                    ->whereNotNull('expense_time')
                    ->get();
                


                foreach ($expenses as $expense) {
                    $parms = [];
                    $parms['service_id'] = null;
                    $parms['service_name'] = $expense->description;
                    $parms['patients'] = 0;
                    $parms['fee'] = 0;
                    $parms['careOff'] = 0;
                    $parms['zf'] = 0;
                    $parms['discount_amount'] = 0;
                    $parms['bill'] = 0;
                    $parms['amount'] = $expense->amount;
                    $parms['from'] = $expense->serial_no;
                    $parms['to'] = $expense->serial_no;
                    $parms['notes'] = $expense->notes;
                    $shiftDatas[$shift->name]['Expenses'][] = $parms;
                }
            }
        }

        return Inertia::render('OPD/Reports/SummaryByShift', [
            'shifts' => $shifts,
            'reports' => $shiftDatas,
            // 'from_date' => $from_date,
            // 'to_date' => $to_date,
            'from_date' => date('d-m-Y H:i', strtotime($from_date)),
            'to_date' => date('d-m-Y H:i', strtotime($to_date)),
            'search' => $search,
        ]);
    }
    public function summaryDetailByDateReport()
    {
        $appointments = new Appointment();
        $search = '';
        $from_date = date('Y-m-d H:i', strtotime('today 00:00'));
        $to_date = date('Y-m-d H:i', strtotime('today 23:59'));
        // if (isset($_GET['from_date']) && $_GET['from_date'] != '') {
        //     $from_date = date('Y-m-d H:i', strtotime($_GET['from_date']));
        // }
        // if (isset($_GET['to_date']) && $_GET['to_date'] != '') {
        //     $to_date = date('Y-m-d H:i', strtotime($_GET['to_date']));
        // }
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
        if (isset($_GET['search']) && $_GET['search'] != '') {
            $search = $_GET['search'];
        }
        $from_date_only = date('Y-m-d', strtotime($from_date));
        $from_time_only = date('H:i', strtotime($from_date));
        $to_date_only = date('Y-m-d', strtotime($to_date));
        $to_time_only = date('H:i', strtotime($to_date));
        // $appointments = $appointments->with(['appointmentDetails.service','careoff','zf','doctor'])->whereBetween('appointment_date', [$from_date, date('Y-m-d H:i', strtotime($to_date . ' +1 day'))]);
        $appointments = $appointments->with(['appointmentDetails.service', 'careoff', 'zf', 'doctor'])
            ->where(function ($query) use ($from_date_only, $from_time_only, $to_date_only, $to_time_only, $search) {
                $query->whereBetween('appointment_date', [$from_date_only, $to_date_only])
                    ->whereBetween('appointment_time', [$from_time_only, $to_time_only]);
                if ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('appointment_date', 'like', '%' . $search . '%')
                            ->orWhere('patient_name', 'like', '%' . $search . '%')
                            ->orWhereHas('appointmentDetails.service', function ($q) use ($search) {
                                $q->where('name', 'like', '%' . $search . '%');
                            })
                            ->orWhereHas('doctor', function ($q) use ($search) {
                                $q->where('name', 'like', '%' . $search . '%');
                            });
                    });
                }
            });
        $appointments = $appointments->get();

        return Inertia::render('OPD/Reports/SummaryDetailByDate', [
            'reports' => $appointments,
            'from_date' => date('d-m-Y H:i', strtotime($from_date)),
            'to_date' => date('d-m-Y H:i', strtotime($to_date)),
            'search' => $search,
        ]);
    }


    public function specialistByDateReport()
    {
        // Fetch doctors
        $doctors = Employee::select('id', 'name')
            ->where('doctor_type', 'Specialist')
            ->whereHas('designation', function ($q) {
                $q->where('is_doctor', 1);
            })
            ->orderBy('name')
            ->get();

        // Set date range and search filters
        $search = '';
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
        if (isset($_GET['search']) && $_GET['search'] != '') {
            $search = $_GET['search'];
        }

        // Break down the date and time for filtering
        $from_date_only = date('Y-m-d', strtotime($from_date));
        $from_time_only = date('H:i', strtotime($from_date));
        $to_date_only = date('Y-m-d', strtotime($to_date));
        $to_time_only = date('H:i', strtotime($to_date));

        // Fetch appointments with filters
        $appointments = Appointment::with(['appointmentDetails.service', 'careoff', 'zf', 'doctor'])
            ->where(function ($query) use ($from_date_only, $from_time_only, $to_date_only, $to_time_only, $search) {
                $query->whereBetween('appointment_date', [$from_date_only, $to_date_only])
                    ->whereBetween('appointment_time', [$from_time_only, $to_time_only]);

                if ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('appointment_date', 'like', '%' . $search . '%')
                            ->orWhereHas('appointmentDetails.service', function ($q) use ($search) {
                                $q->where('name', 'like', '%' . $search . '%');
                            })->orWhereHas('doctor', function ($q) use ($search) {
                                $q->where('name', 'like', '%' . $search . '%');
                            });
                    });
                }
            })
            ->whereType('Specialist')
            ->orderBy('consulting_doctor_id')
            ->get();

        return Inertia::render('OPD/Reports/SpecialistByDate', [
            'doctors' => $doctors,
            'reports' => $appointments,
            'from_date' => date('d-m-Y H:i', strtotime($from_date)),
            'to_date' => date('d-m-Y H:i', strtotime($to_date)),
            'search' => $search,
        ]);
    }
    public function generalByDateReport()
    {
        $doctors = Employee::select('id', 'name')
            ->where('doctor_type', 'General')
            ->whereHas('designation', function ($q) {
                $q->where('is_doctor', 1);
            })
            ->orderBy('name')
            ->get();

        $appointments = new Appointment();
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

        $search = $_GET['search'] ?? '';

        $from_date_only = date('Y-m-d', strtotime($from_date));
        $from_time_only = date('H:i', strtotime($from_date));
        $to_date_only = date('Y-m-d', strtotime($to_date));
        $to_time_only = date('H:i', strtotime($to_date));

        $appointments = $appointments->with(['appointmentDetails.service', 'careoff', 'zf', 'doctor'])
            ->where(function ($query) use ($from_date_only, $from_time_only, $to_date_only, $to_time_only, $search) {
                $query->whereBetween('appointment_date', [$from_date_only, $to_date_only])
                    ->whereBetween('appointment_time', [$from_time_only, $to_time_only]);

                if ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('appointment_date', 'like', '%' . $search . '%')
                            ->orWhereHas('appointmentDetails.service', function ($q) use ($search) {
                                $q->where('name', 'like', '%' . $search . '%');
                            })->orWhereHas('doctor', function ($q) use ($search) {
                                $q->where('name', 'like', '%' . $search . '%');
                            });
                    });
                }
            })
            ->whereType('General')
            ->orderBy('consulting_doctor_id')
            ->get();

        return Inertia::render('OPD/Reports/GeneralByDate', [
            'doctors' => $doctors,
            'reports' => $appointments,
            'from_date' => date('d-m-Y H:i', strtotime($from_date)),
            'to_date' => date('d-m-Y H:i', strtotime($to_date)),
            'search' => $search,
        ]);
    }


    public function specialistDetailByDoctorReport()
    {
        $doctors = Employee::select('id', 'name')->whereHas('designation', function ($q) {
            $q->where('is_doctor', 1);
        })->where('doctor_type', 'Specialist')->orderBy('name')->get();


        $appointments = new Appointment();
        $search = "";
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
        if (isset($_GET['search']) && $_GET['search'] != '') {
            $search = $_GET['search'];
        }
        $from_date_only = date('Y-m-d', strtotime($from_date));
        $from_time_only = date('H:i', strtotime($from_date));
        $to_date_only = date('Y-m-d', strtotime($to_date));
        $to_time_only = date('H:i', strtotime($to_date));
        if (isset($_GET['doctor_id']) && $_GET['doctor_id'] != '') {
            $doctor_id = $_GET['doctor_id'];
        }

        if ($doctor_id) {
            $appointments = $appointments->where('consulting_doctor_id', $doctor_id);
        }
        // $appointments = $appointments->with(['appointmentDetails.service','careoff','zf','doctor'])->whereBetween('appointment_date', [$from_date, date('Y-m-d H:i', strtotime($to_date . ' +1 day'))]);
        $appointments = $appointments->with(['appointmentDetails.service', 'careoff', 'zf', 'doctor'])
            ->where(function ($query) use ($from_date_only, $from_time_only, $to_date_only, $search, $to_time_only) {
                $query->whereBetween('appointment_date', [$from_date_only, $to_date_only])
                    ->whereBetween('appointment_time', [$from_time_only, $to_time_only]);
                if ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('appointment_date', 'like', '%' . $search . '%')
                            ->orWhereHas('appointmentDetails.service', function ($q) use ($search) {
                                $q->where('name', 'like', '%' . $search . '%');
                            })->orWhereHas('doctor', function ($q) use ($search) {
                                $q->where('name', 'like', '%' . $search . '%');
                            });
                    });
                }
            });
        $appointments = $appointments->where('type', 'specialist')->get();

        return Inertia::render('OPD/Reports/SpecialistDetailByDoctor', [
            'doctor_id' => $doctor_id,
            'doctors' => $doctors,
            'reports' => $appointments,
            'from_date' => date('d-m-Y H:i', strtotime($from_date)),
            'to_date' => date('d-m-Y H:i', strtotime($to_date)),
            'search' => $search,
        ]);
    }

    public function generalDetailByDoctorReport()
    {
        $doctors = Employee::select('id', 'name')->whereHas('designation', function ($q) {
            $q->where('is_doctor', 1);
        })->where('doctor_type', 'General')->orderBy('name')->get();


        $appointments = new Appointment();
        $search = "";
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
        if (isset($_GET['search']) && $_GET['search'] != '') {
            $search = $_GET['search'];
        }
        $from_date_only = date('Y-m-d', strtotime($from_date));
        $from_time_only = date('H:i', strtotime($from_date));
        $to_date_only = date('Y-m-d', strtotime($to_date));
        $to_time_only = date('H:i', strtotime($to_date));
        if (isset($_GET['doctor_id']) && $_GET['doctor_id'] != '') {
            $doctor_id = $_GET['doctor_id'];
        }

        if ($doctor_id) {
            $appointments = $appointments->where('consulting_doctor_id', $doctor_id);
        }
        // $appointments = $appointments->with(['appointmentDetails.service','careoff','zf','doctor'])->whereBetween('appointment_date', [$from_date, date('Y-m-d H:i', strtotime($to_date . ' +1 day'))]);
        $appointments = $appointments->with(['appointmentDetails.service', 'careoff', 'zf', 'doctor'])
            ->where(function ($query) use ($search, $from_date_only, $from_time_only, $to_date_only, $to_time_only) {
                $query->whereBetween('appointment_date', [$from_date_only, $to_date_only])
                    ->whereBetween('appointment_time', [$from_time_only, $to_time_only]);
                if ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('appointment_date', 'like', '%' . $search . '%')
                            ->orWhereHas('appointmentDetails.service', function ($q) use ($search) {
                                $q->where('name', 'like', '%' . $search . '%');
                            })->orWhereHas('doctor', function ($q) use ($search) {
                                $q->where('name', 'like', '%' . $search . '%');
                            });
                    });
                }
            });
        $appointments = $appointments->where('type', 'General')->get();

        return Inertia::render('OPD/Reports/GeneralDetailByDoctor', [
            'doctor_id' => $doctor_id,
            'doctors' => $doctors,
            'reports' => $appointments,
           'from_date' => date('d-m-Y H:i', strtotime($from_date)),
            'to_date' => date('d-m-Y H:i', strtotime($to_date)),
            'search' => $search,
        ]);
    }

    public function specialistDetailReport()
    {
        $doctors = Employee::select('id', 'name')->whereHas('designation', function ($q) {
            $q->where('is_doctor', 1);
        })->orderBy('name')->get();


        $appointments = new Appointment();
        $doctor_id = null;
        $search = "";
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
        if (isset($_GET['search']) && $_GET['search'] != '') {
            $search = $_GET['search'];
        }
        $from_date_only = date('Y-m-d', strtotime($from_date));
        $from_time_only = date('H:i', strtotime($from_date));
        $to_date_only = date('Y-m-d', strtotime($to_date));
        $to_time_only = date('H:i', strtotime($to_date));
        if (isset($_GET['doctor_id']) && $_GET['doctor_id'] != '') {
            $doctor_id = $_GET['doctor_id'];
        }

        if ($doctor_id) {
            $appointments = $appointments->where('consulting_doctor_id', $doctor_id);
        }
        // $appointments = $appointments->with(['appointmentDetails.service','careoff','zf','doctor'])->whereBetween('appointment_date', [$from_date, date('Y-m-d H:i', strtotime($to_date . ' +1 day'))]);
        $appointments = $appointments->with(['appointmentDetails.service', 'careoff.funds', 'zf', 'doctor'])
            ->where(function ($query) use ($search, $from_date_only, $from_time_only, $to_date_only, $to_time_only) {
                $query->whereBetween('appointment_date', [$from_date_only, $to_date_only])
                    ->whereBetween('appointment_time', [$from_time_only, $to_time_only]);
                if ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('appointment_date', 'like', '%' . $search . '%')
                            ->orWhereHas('appointmentDetails.service', function ($q) use ($search) {
                                $q->where('name', 'like', '%' . $search . '%');
                            })->orWhereHas('appointmentDetails', function ($q) use ($search) {
                                $q->where('patient_name', 'like', '%' . $search . '%');
                            })->orWhereHas('doctor', function ($q) use ($search) {
                                $q->where('name', 'like', '%' . $search . '%');
                            })->orWhereHas('careoff', function ($q) use ($search) {
                                $q->where('name', 'like', '%' . $search . '%');
                            });
                    });
                }
            });
        $appointments = $appointments->where('type', 'specialist')->get();

        return Inertia::render('OPD/Reports/SpecialistDetail', [
            'doctor_id' => $doctor_id,
            'doctors' => $doctors,
            'reports' => $appointments,
            'from_date' => date('d-m-Y H:i', strtotime($from_date)),
            'to_date' => date('d-m-Y H:i', strtotime($to_date)),
            'search' => $search,
        ]);
    }

    public function generalDetailReport()
    {
        $doctors = Employee::select('id', 'name')->whereHas('designation', function ($q) {
            $q->where('is_doctor', 1);
        })->orderBy('name')->get();


        $appointments = new Appointment();
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
        if (isset($_GET['doctor_id']) && $_GET['doctor_id'] != '') {
            $doctor_id = $_GET['doctor_id'];
        }

        if ($doctor_id) {
            $appointments = $appointments->where('consulting_doctor_id', $doctor_id);
        }
        // $appointments = $appointments->with(['appointmentDetails.service','careoff','zf','doctor'])->whereBetween('appointment_date', [$from_date, date('Y-m-d H:i', strtotime($to_date . ' +1 day'))]);
        $appointments = $appointments->with(['appointmentDetails.service', 'careoff', 'zf', 'doctor'])
            ->where(function ($query) use ($from_date_only, $from_time_only, $to_date_only, $to_time_only) {
                $query->whereBetween('appointment_date', [$from_date_only, $to_date_only])
                    ->whereBetween('appointment_time', [$from_time_only, $to_time_only]);
            });
        $appointments = $appointments->where('type', 'General')->get();

        return Inertia::render('OPD/Reports/GeneralDetail', [
            'doctor_id' => $doctor_id,
            'doctors' => $doctors,
            'reports' => $appointments,
            'from_date' => date('d-m-Y H:i', strtotime($from_date)),
            'to_date' => date('d-m-Y H:i', strtotime($to_date)),
        ]);
    }

    public function consultantTaxReport()
    {
        $from_date = date('Y-m-d');
        $to_date = date('Y-m-d');
        if (isset($_GET['from_date']) && $_GET['from_date'] != '') {
            $from_date = DateTime::createFromFormat('d-m-Y', $_GET['from_date']);
            if (!$from_date) {
                throw new \Exception('Invalid from_date format. Expected d-m-Y.');
            }
            $from_date = $from_date->format('Y-m-d');
        }

        if (isset($_GET['to_date']) && $_GET['to_date'] != '') {
            $to_date = DateTime::createFromFormat('d-m-Y', $_GET['to_date']);
            if (!$to_date) {
                throw new \Exception('Invalid to_date format. Expected d-m-Y.');
            }
            $to_date = $to_date->format('Y-m-d');
        }

        $doctors = Employee::select('id', 'name')->whereHas('designation', function ($q) {
            $q->where('is_doctor', 1);
        })->with('appointments', function ($q) use ($from_date, $to_date) {
            $q = $q->with(['appointmentDetails.service', 'careoff', 'zf', 'doctor'])->whereBetween('appointment_date', [$from_date, date('Y-m-d H:i', strtotime($to_date . ' +1 day'))]);
        })->orderBy('name')->get();

        return Inertia::render('OPD/Reports/ConsultantTax', [
            'reports' => $doctors,
            'from_date' => date('d-m-Y', strtotime($from_date)),
            'to_date' => date('d-m-Y', strtotime($to_date)),
        ]);
    }

    public function detailByDoctorReport()
    {
        $doctors = Employee::select('id', 'name')->whereHas('designation', function ($q) {
            $q->where('is_doctor', 1);
        })->orderBy('name')->get();


        $appointments = new Appointment();
        $from_date = date('Y-m-d');
        $to_date = date('Y-m-d');
        $doctor_id = null;
        if (isset($_GET['from_date']) && $_GET['from_date'] != '') {
            $from_date = DateTime::createFromFormat('d-m-Y', $_GET['from_date']);
            if (!$from_date) {
                throw new \Exception('Invalid from_date format. Expected d-m-Y.');
            }
            $from_date = $from_date->format('Y-m-d');
        }

        if (isset($_GET['to_date']) && $_GET['to_date'] != '') {
            $to_date = DateTime::createFromFormat('d-m-Y', $_GET['to_date']);
            if (!$to_date) {
                throw new \Exception('Invalid to_date format. Expected d-m-Y.');
            }
            $to_date = $to_date->format('Y-m-d');
        }
        if (isset($_GET['doctor_id']) && $_GET['doctor_id'] != '') {
            $doctor_id = $_GET['doctor_id'];
        }

        if ($doctor_id) {
            $appointments = $appointments->where('consulting_doctor_id', $doctor_id);
        }
        $appointments = $appointments->with(['appointmentDetails.service', 'careoff', 'zf', 'doctor'])->whereBetween('appointment_date', [$from_date, date('Y-m-d H:i', strtotime($to_date . ' +1 day'))]);
        $appointments = $appointments->get();

        return Inertia::render('OPD/Reports/DetailByDoctor', [
            'doctor_id' => $doctor_id,
            'doctors' => $doctors,
            'reports' => $appointments,
           'from_date' => date('d-m-Y', strtotime($from_date)),
            'to_date' => date('d-m-Y', strtotime($to_date)),
        ]);
    }

    public function detailByAmountReport()
    {
        $services = Service::select('id', 'name')->orderBy('name')->get();
        $appointments = new Appointment();
        $from_date = date('Y-m-d');
        $to_date = date('Y-m-d');
        $service_id = null;
        if (isset($_GET['from_date']) && $_GET['from_date'] != '') {
            $from_date = DateTime::createFromFormat('d-m-Y', $_GET['from_date']);
            if (!$from_date) {
                throw new \Exception('Invalid from_date format. Expected d-m-Y.');
            }
            $from_date = $from_date->format('Y-m-d');
        }

        if (isset($_GET['to_date']) && $_GET['to_date'] != '') {
            $to_date = DateTime::createFromFormat('d-m-Y', $_GET['to_date']);
            if (!$to_date) {
                throw new \Exception('Invalid to_date format. Expected d-m-Y.');
            }
            $to_date = $to_date->format('Y-m-d');
        }
        if (isset($_GET['service_id']) && $_GET['service_id'] != '') {
            $service_id = $_GET['service_id'];
        }

        if ($service_id) {
            $appointments = $appointments->whereHas('appointmentDetails', function ($q) use ($service_id) {
                $q->where('service_id', $service_id);
            });
        }
        $appointments = $appointments->with(['appointmentDetails.service', 'careoff', 'zf', 'doctor'])->whereBetween('appointment_date', [$from_date, date('Y-m-d H:i', strtotime($to_date . ' +1 day'))]);
        $appointments = $appointments->get();

        return Inertia::render('OPD/Reports/DetailByAmount', [
            'service_id' => $service_id,
            'services' => $services,
            'reports' => $appointments,
            'from_date' => date('d-m-Y', strtotime($from_date)),
            'to_date' => date('d-m-Y', strtotime($to_date)),
        ]);
    }


    public function detailByDoctorAmountReport()
    {
        $doctors = Employee::select('id', 'name')->whereHas('designation', function ($q) {
            $q->where('is_doctor', 1);
        })->orderBy('name')->get();

        $services = Service::select('id', 'name')->orderBy('name')->get();
        $appointments = new Appointment();
        $from_date = date('Y-m-d');
        $to_date = date('Y-m-d');
        $service_id = null;
        $doctor_id = null;
        if (isset($_GET['from_date']) && $_GET['from_date'] != '') {
            $from_date = DateTime::createFromFormat('d-m-Y', $_GET['from_date']);
            if (!$from_date) {
                throw new \Exception('Invalid from_date format. Expected d-m-Y.');
            }
            $from_date = $from_date->format('Y-m-d');
        }

        if (isset($_GET['to_date']) && $_GET['to_date'] != '') {
            $to_date = DateTime::createFromFormat('d-m-Y', $_GET['to_date']);
            if (!$to_date) {
                throw new \Exception('Invalid to_date format. Expected d-m-Y.');
            }
            $to_date = $to_date->format('Y-m-d');
        }
        if (isset($_GET['service_id']) && $_GET['service_id'] != '') {
            $service_id = $_GET['service_id'];
        }
        if (isset($_GET['doctor_id']) && $_GET['doctor_id'] != '') {
            $doctor_id = $_GET['doctor_id'];
        }

        if ($service_id) {
            $appointments = $appointments->whereHas('appointmentDetails', function ($q) use ($service_id) {
                $q->where('service_id', $service_id);
            });
        }

        if ($doctor_id) {
            $appointments = $appointments->where('consulting_doctor_id', $doctor_id);
        }
        $appointments = $appointments->with(['appointmentDetails.service', 'careoff', 'zf', 'doctor'])->whereBetween('appointment_date', [$from_date, date('Y-m-d H:i', strtotime($to_date . ' +0 day'))]);
        $appointments = $appointments->get();

        $totalFees = [];
        $totalCounts = [];
        foreach ($appointments as $appointment) {
            $doctorId = $appointment->consulting_doctor_id;
            $fee = $appointment->appointmentDetails->sum('fee');

            if (!isset($totalFees[$doctorId])) {
                $totalFees[$doctorId] = 0;
                $totalCounts[$doctorId] = 0;
            }
            $totalFees[$doctorId] += $fee;
            $totalCounts[$doctorId] += 1;
        }

        return Inertia::render('OPD/Reports/DetailByDoctorAmount', [
            'doctor_id' => $doctor_id,
            'doctors' => $doctors,
            'total_fees' => $totalFees,
            'total_counts' => $totalCounts,
            'service_id' => $service_id,
            'services' => $services,
            'reports' => $appointments,
            'from_date' => date('d-m-Y', strtotime($from_date)),
            'to_date' => date('d-m-Y', strtotime($to_date)),
        ]);
    }

    public function MRReport(Request $request)
    {
        $searchTerm = request()->get('search', null);
        $fromDate = date('Y-m-d');
        $toDate = date('Y-m-d');

        if ($request->has('from_date') && $request->from_date != '') {
            $fromDate = DateTime::createFromFormat('d-m-Y', $request->from_date);
            if (!$fromDate) {
                throw new \Exception('Invalid from_date format. Expected d-m-Y.');
            }
            $fromDate = $fromDate->format('Y-m-d');
        }

        if ($request->has('to_date') && $request->to_date != '') {
            $toDate = DateTime::createFromFormat('d-m-Y', $request->to_date);
            if (!$toDate) {
                throw new \Exception('Invalid to_date format. Expected d-m-Y.');
            }
            $toDate = $toDate->format('Y-m-d');
        }

        $appointmentsQuery = Appointment::with(['careoff', 'patient', 'appointmentDetails', 'doctor'])
            ->whereDate('appointment_date', '>=', $fromDate)
            ->whereDate('appointment_date', '<=', $toDate)
            ->orderBy('appointment_date', 'asc')
            ->orderBy('appointment_time', 'asc');

        $admissionsQuery = Admission::with(['careoff', 'patient', 'details', 'details.service', 'details.doctor'])
            ->whereDate('discharge_date', '>=', $fromDate)
            ->whereDate('discharge_date', '<=', $toDate)
            ->orderBy('discharge_date', 'asc')
            ->orderBy('discharge_time', 'asc');

        $patientTestsQuery = PatientTest::with([
                'careoff',
                'patient',
                'patientTestDetails.package',
                'patientTestDetails.testGeneralDetails.subPackage',
                'patientTestDetails.testGeneralDetails.labTest',
                'patientTestDetails.testGeneralDetails.testCategory',
                'testWidalDetails.widalTest',
                'testCrossMatchDetails',
            ])
            ->whereDate('test_date', '>=', $fromDate)
            ->whereDate('test_date', '<=', $toDate)
            ->orderBy('test_date', 'asc')
            ->orderBy('test_time', 'asc');

        if ($searchTerm) {
            $patient = Patient::where('name', 'LIKE', '%' . $searchTerm . '%')
                ->orWhere('phone', 'LIKE', '%' . $searchTerm . '%')->orWhere('mr_number', $searchTerm)->orWhere('cnic', 'LIKE', '%' . $searchTerm . '%')
                ->first();

            if ($patient) {
                $appointmentsQuery->where(function ($query) use ($patient) {
                    $query->where('patient_cnic', 'LIKE', '%' . $patient->cnic . '%')
                          ->orWhere('patient_name', 'LIKE', '%' . $patient->name . '%')
                          ->orWhere('patient_phone', 'LIKE', '%' . $patient->phone . '%');
                });

                $admissionsQuery->where(function ($query) use ($patient) {
                    $query->where('cnic', 'LIKE', '%' . $patient->cnic . '%')
                          ->orWhere('name', 'LIKE', '%' . $patient->name . '%')
                          ->orWhere('phone', 'LIKE', '%' . $patient->phone . '%');
                });

                $patientTestsQuery->where(function ($query) use ($patient) {
                    $query->where('patient_cnic', 'LIKE', '%' . $patient->cnic . '%')
                          ->orWhere('patient_name', 'LIKE', '%' . $patient->name . '%')
                          ->orWhere('patient_phone', 'LIKE', '%' . $patient->phone . '%');
                });
            }
        }

        $appointments = $appointmentsQuery->get();
        $admissions = $admissionsQuery->get();
        $patientTests = $patientTestsQuery->get();

        $combinedResults = collect();

        foreach ($appointments as $appointment) {
            $fees = $appointment->appointmentDetails->sum('fee');

            $patient = Patient::where(function ($query) use ($appointment) {
                $query->where('phone', $appointment->patient_phone);
            })->first();
            $mrNumber = $patient ? $patient->mr_number : '';
            if ($patient) {
                $combinedResults->push([
                    'type' => 'Appointment',
                    'id' => $appointment->id,
                    'patient_name' => $appointment->patient_name,
                    'patient_phone' => $appointment->patient_phone ?? '',
                    'patient_cnic' => $appointment->patient_cnic ?? '',
                    'mr_number' => $mrNumber ?? '',
                    'date' => $appointment->appointment_date,
                    'amount' => $fees,
                    'doctor' => $appointment->doctor->name,
                ]);
            }
        }

        // Loop through admissions
        foreach ($admissions as $admission) {
            $doctors = $admission->details->pluck('doctor.name')->filter()->join(', ');
            $services = $admission->details->pluck('service.name')->filter()->join(', ');

            $amount = ($admission->advance_amount ?? 0)
                + ($admission->donor_fee ?? 0)
                + ($admission->zf_fee ?? 0)
                + ($admission->received_amount ?? 0)
                - ($admission->refund_amount ?? 0)
                - ($admission->discount_amount ?? 0);

            // Fetch patient data correctly
            $patient = Patient::where(function ($query) use ($admission) {
                $query->where('phone', $admission->phone);
            })->first();
            $mrNumber = $patient ? $patient->mr_number : '';

            if ($patient) {
                $combinedResults->push([
                    'type' => 'Admission',
                    'id' => $admission->id,
                    'patient_name' => $admission->name,
                    'patient_phone' => $admission->phone ?? '',
                    'patient_cnic' => $admission->cnic ?? '',
                    'date' => $admission->admission_date,
                    'mr_number' => $mrNumber ?? '',
                    'amount' => $amount,
                    'doctor' => $doctors,
                    'service' => $services,
                ]);
            }
        }

        foreach ($patientTests as $patientTest) {
            $patient = Patient::where(function ($query) use ($patientTest) {
                $query->where('phone', $patientTest->patient_phone);
            })->first();
            $mrNumber = $patient ? $patient->mr_number : '';

            if ($patient) {
                $combinedResults->push([
                    'type' => 'Patient Test',
                    'id' => $patientTest->id,
                    'patient_name' => $patientTest->patient_name,
                    'patient_phone' => $patientTest->patient_phone,
                    'patient_cnic' => $patientTest->patient_cnic,
                    'date' => $patientTest->test_date,
                    'mr_number' => $mrNumber ?? '',
                    'amount' => $patientTest->total_amount ?? 0,
                    'patient_test_details' => $patientTest->patientTestDetails,
                ]);
            }
        }

        return Inertia::render('OPD/Reports/MRReport', [
            'reports' => $combinedResults,
            'search' => $searchTerm,
            'from_date' => $fromDate ? date('d-m-Y', strtotime($fromDate)) : null,
            'to_date' => $toDate ? date('d-m-Y', strtotime($toDate)) : null,
        ]);
    }



}
