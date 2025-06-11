<?php

namespace Database\Seeders;

use App\Models\HRMS\Department;
use App\Models\HRMS\Designation;
use App\Models\HRMS\DoctorCharge;
use App\Models\HRMS\Employee;
use App\Models\OPD\Appointment;
use App\Models\OPD\AppointmentDetail;
use App\Models\OPD\Donor;
use App\Models\Role;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ImportSqlSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        Department::truncate();
        Employee::truncate();
        DoctorCharge::truncate();
        Schema::enableForeignKeyConstraints();

        $depts = DB::connection('mysql2')->table('dept')->get();
        foreach ($depts as $dept) {
            Department::create([
                'id' => $dept->deptid,
                'name' => $dept->name,
                'type' => 'payroll',
            ]);
        }

        $designation = Designation::create([
            'name' => 'Doctor',
            'is_doctor' => '1',
        ]);

        // $docs = \DB::connection('mysql2')->table('doc')->get();
        // SELECT doc.docid AS DocID, doc.docName AS DocName, doc.fName AS FName, dept.name AS Department, doc.rate AS Fee1, doc.Rate2 AS Fee2, doc.Rate3 AS Fee3, doc.share AS Share, dept.deptid
        //     FROM doc INNER JOIN dept ON doc.dept = dept.deptid
        //     WHERE (((dept.deptid)<>15))

        // SELECT doc.docid AS DocID, doc.docName AS DocName, doc.fName AS FName, dept.name AS Department, 
        //        doc.rate AS Fee1, doc.Rate2 AS Fee2, doc.Rate3 AS Fee3, doc.share AS Share, dept.deptid
        // FROM doc 
        // INNER JOIN dept ON doc.dept = dept.deptid
        // WHERE dept.deptid <> 15

        $query = "
            SELECT dept.name AS dep, doc.docid, doc.docName, doc.fName, doc.share, doc.active, doc.Rate, doc.Rate2, doc.Rate3, doc.dept, dept.IO
            FROM doc INNER JOIN dept ON doc.dept = dept.deptid
            WHERE (((doc.active)='True'))
            ORDER BY dept.name;
        ";

        $docs = DB::connection('mysql2')->select($query);

        foreach ($docs as $doc) {
            $employee = Employee::create([
                'id' => $doc->docid,
                'name' => $doc->docName,
                'email' => str_replace(" ","",$doc->docName) . rand(0, 100) . '@gmail.com',
                'doj' => date('Y-m-d'),
                'dob' => date('Y-m-d'),
                'image' => date('Y-m-d'),
                'department_id' => $doc->dept,
                'designation_id' => $designation->id,
                'shares' => $doc->share,
                'shares_type' => 'Fixed',
            ]);


            // SELECT itblDoc.docName, itblTransaction.srno AS sSr, itblTransaction.srno AS eSr, itblTransaction.pno, itblTransaction.amount, itblDoc.share, [amount]*([share]/100) AS docSh, itblTransaction.dated AS sdt, itblTransaction.userName, itblTransaction.enable, itblTransaction.pName, itblTransaction.age, itblTransaction.sex, itblTransaction.CareOff, itblDoc.docid, itblDept.deptid, itblTransaction.discount
            // FROM (itblDept INNER JOIN itblDoc ON itblDept.deptid=itblDoc.dept) INNER JOIN itblTransaction ON itblDoc.docid=itblTransaction.doc
            // WHERE (((itblTransaction.dated)>[sDT] And (itblTransaction.dated)<DateAdd("d",1,[eDT])) AND ((itblTransaction.enable)=True) AND ((itblDoc.docid)<>16))
            // ORDER BY itblDoc.docName;



            $employee->doctorCharges()->create([
                'visiting_charges' => $doc->Rate,
                'consulting_charges' => $doc->Rate2
            ]);
        }

        $speciallist = "SELECT *  FROM `transaction` WHERE `patienttype` LIKE 'OPD' AND recdoc in (select docName from doc where rate > 0)  ORDER BY `transaction`.`recdoc`  DESC";
        $speciallist = DB::connection('mysql2')->select($speciallist);

        foreach ($speciallist as $special) {
            $emp_id = Employee::where('name', $special->recdoc)->value('id');
            $donor_fee = 0;
            $donor_id = null;
            if($special->CareOff != '' && $special->CareOff != null){
                $donor_fee = $special->amount;
                if(!Donor::whereName($special->CareOff)->exists()){
                    $donor = Donor::insert(['name' => $special->CareOff, 'type' => 'care_off']);
                    $donor_id = $donor;
                }
                else{
                    $donor = Donor::whereName($special->CareOff)->first();
                    $donor_id = $donor->id;
                }
            }
                    
            $employee = Appointment::insert([
                'consulting_doctor_id' => $emp_id,
                'type' => 'Specialist',
                'appointment_date' => date('Y-m-d', strtotime($special->dated)),
                'appointment_time' => date('H:i:s'),
                'patient_name' => $special->pName,
                'patient_phone' => $special->phoneno,
                'patient_gender' => $special->sex,
                'careoff_id' => $donor_id ?? null,
                'donor_fee' => $donor_fee,
                'zf_fee' => $special->zakat > 0 ? $special->zakat : null,
                'welfare' => $special->authority,
            ]);

            AppointmentDetail::create([
                'appointment_id' => $employee,
                'fee' => $special->amount,
                'service_id' => null,
            ]);
        }



        $generals = "SELECT *  FROM `transaction` WHERE `patienttype` LIKE 'OPD' AND recdoc not in (select docName from doc where rate > 0) ORDER BY `transaction`.`recdoc`  DESC";
        $generals = DB::connection('mysql2')->select($generals);

        foreach ($generals as $general) {
            $emp_id = Employee::where('name', $general->recdoc)->value('id');
            $donor_fee = 0;
            $donor_id = null;
            if($general->CareOff != '' && $general->CareOff != null){
                $donor_fee = $general->amount;
                if(!Donor::whereName($general->CareOff)->exists()){
                    $donor = Donor::insert(['name' => $general->CareOff, 'type' => 'care_off']);
                    $donor_id = $donor;
                }
                else{
                    $donor = Donor::whereName($general->CareOff)->first();
                    $donor_id = $donor->id;
                }
            }
                    
            $employee = Appointment::insert([
                'consulting_doctor_id' => $emp_id,
                'type' => 'General',
                'appointment_date' => date('Y-m-d', strtotime($general->dated)),
                'appointment_time' => date('H:i:s'),
                'patient_name' => $general->pName,
                'patient_phone' => $general->phoneno,
                'patient_gender' => $general->sex,
                'careoff_id' => $donor_id ?? null,
                'donor_fee' => $donor_fee,
                'zf_fee' => $general->zakat > 0 ? $general->zakat : null,
                'welfare' => $general->authority,
            ]);

            AppointmentDetail::create([
                'appointment_id' => $employee,
                'fee' => $general->amount,
                'service_id' => null,
            ]);
        }


        
        
    }
}
