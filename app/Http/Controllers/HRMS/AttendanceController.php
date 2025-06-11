<?php

namespace App\Http\Controllers\HRMS;

use App\Http\Controllers\Controller;
use App\Models\HRMS\AttendanceMachine;
use App\Models\HRMS\DailyAttendance;
use App\Models\HRMS\Employee;
use App\Models\HRMS\EmployeeShiftRoster;
use Rats\Zkteco\Lib\ZKTeco;

class AttendanceController extends Controller
{
    public function __construct()
    {
        // $this->middleware('checkPermission:attendance-machines.index')->only('index');
        // $this->middleware('checkPermission:attendance-machines.create')->only('create', 'store');
        // $this->middleware('checkPermission:attendance-machines.show')->only('show');
        // $this->middleware('checkPermission:attendance-machines.edit')->only('edit', 'update');
        // $this->middleware('checkPermission:attendance-machines.destroy')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     */
    // public function index($id)
    // {
    //     \Log::info('Received ID:', ['id' => $id]);
    //     $deviceip = AttendanceMachine::where('id', $id)->value('ip_address');
    //     if($deviceip){
    //         $zk = new ZKTeco($deviceip, 4370);
    //         $a = $zk->connect();
    //         $zk->disableDevice();
    //         $attendances = $zk->getAttendance();
    //         $zk->enableDevice();
    //         foreach($attendances as $attendance){
    //             \Log::info('Received attendance:', ['attendance' => $attendance]);
    //             if(Employee::where('machine_id', $attendance['uid'])->exists()){
    //                 $employee_id = Employee::where('machine_id', $attendance['uid'])->value('id');
    //             }
    //             else{
    //                 $employee_id = Employee::whereId($attendance['uid'])->value('id');
    //             }

    //             $dailyAttendance = DailyAttendance::where('date', date('Y-m-d', strtotime($attendance['timestamp'])))->whereEmployeeId($employee_id)->first();


    //             $employee = EmployeeShiftRoster::where('employee_id',$employee_id)->where('roster_date', date('Y-m-d', strtotime($attendance['timestamp'])))->first();

    //             $employee_early = EmployeeShiftRoster::where('employee_id',$employee_id)->where('roster_date', date('Y-m-d', strtotime('-1 day', strtotime($attendance['timestamp']))))->first();
    //             if($employee_early){
    //                 $start_time = date('H:i:s', strtotime($employee_early->shift?->start_time));
    //                 $end_time = date('H:i:s', strtotime($employee_early->shift?->end_time));
    //                 if($start_time > $end_time){
    //                     if($dailyAttendance){
    //                         $employee = $employee_early;
    //                     }
    //                 }
    //             }

    //             if($employee){
    //                 if($dailyAttendance){
    //                     DailyAttendance::whereId($dailyAttendance->id)->update([
    //                         'check_out' => date('H:i:s', strtotime($attendance['timestamp'])),
    //                     ]);
    //                 }
    //                 else{
    //                     $late = 0;
    //                     $startTime = $employee->shift?->start_time;
    //                     $newStartTime = date('H:i:s', strtotime('+15 minutes', strtotime($startTime)));
    //                     $attendanceTime = date('H:i:s', strtotime($attendance['timestamp']));
    //                     if($newStartTime < $attendanceTime){
    //                         $late = 1;
    //                     }
    //                     DailyAttendance::create([
    //                         'employee_id' => $employee_id,
    //                         'date' => date('Y-m-d', strtotime($attendance['timestamp'])),
    //                         'check_in' => date('H:i:s', strtotime($attendance['timestamp'])),
    //                         'check_out' => null,
    //                         'checkin_type' => 'machine',
    //                         'checkout_type' => 'machine',
    //                         'late' => $late
    //                     ]);
    //                 }
    //             }
    //         }
    //     }
    //     return redirect()->route('attendance-machines.index');
    // }
    public function index($id)
{
    \Log::info('Received ID:', ['id' => $id]);
    $deviceip = AttendanceMachine::where('id', $id)->value('ip_address');
    if ($deviceip) {
        $zk = new ZKTeco($deviceip, 4370);
        $zk->connect();
        $zk->disableDevice();
        $attendances = $zk->getAttendance();
        $zk->enableDevice();

        foreach ($attendances as $attendance) {
            \Log::info('Received attendance:', ['attendance' => $attendance]);
            $employee_id = Employee::where('machine_id', $attendance['id'])->value('id');

            if (!$employee_id) {
                continue;
            }

            $dailyAttendance = DailyAttendance::where('date', date('Y-m-d', strtotime($attendance['timestamp'])))
                ->whereEmployeeId($employee_id)
                ->first();

            if ($dailyAttendance) {
                DailyAttendance::whereId($dailyAttendance->id)->update([
                    'check_out' => date('H:i:s', strtotime($attendance['timestamp'])),
                ]);
            } else {
                DailyAttendance::create([
                    'employee_id' => $employee_id,
                    'date' => date('Y-m-d', strtotime($attendance['timestamp'])),
                    'check_in' => date('H:i:s', strtotime($attendance['timestamp'])),
                    'check_out' => null,
                    'checkin_type' => 'machine',
                    'checkout_type' => 'machine',
                    'late' => 0,
                ]);
            }
        }
    }
    return redirect()->route('attendance-machines.index');
}

}
