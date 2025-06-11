<?php

namespace App\Http\Controllers;

use XelentAbrar\HospitalAccounts\Models\Accounts\Voucher;
use XelentAbrar\HospitalAccounts\Models\Accounts\VoucherDetail;
use XelentAbrar\HospitalExpense\Models\Expenses\Expense;
use App\Models\IPD\Admission;
use App\Models\Lab\PatientTest;
use App\Models\OPD\Appointment;
use XelentAbrar\HospitalDonation\Models\Donation\DonorFund;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $expenses = $vouchers = $appointments = $admissions = $donors = $labs = [];
        if($request->searchFrom == 'Accounts' && file_exists(base_path('config/accounts.php'))){
            
            $vouchers = Voucher::where(function($q) use($request) {
                $q->where('voucher_no', 'LIKE', '%'.$request->searchParms.'%');
            })
            ->with(['voucherType','voucherDetails.chartOfAccount'])
            ->limit(5)
            ->orderBy('created_at', 'desc')
            ->get()->map(function ($voucher) {
                // Add a new column "status"
                $voucher->search = 'voucher_no';
                return $voucher;
            });

            if(count($vouchers) == 0){
                $vouchers = Voucher::whereHas('voucherDetails.chartOfAccount', function ($q) use ($request) {
                    $q->where('acc_desc', 'LIKE', '%'.$request->searchParms.'%')
                    ->orWhere('acc_code', 'LIKE', '%'.$request->searchParms.'%');
                })
                ->with([
                    'voucherType',
                    'voucherDetails' => function ($q) use ($request) {
                        $q->whereHas('chartOfAccount', function ($q) use ($request) {
                            $q->where('acc_desc', 'LIKE', '%'.$request->searchParms.'%')
                            ->orWhere('acc_code', 'LIKE', '%'.$request->searchParms.'%');
                        })->with(['chartOfAccount:id,acc_desc,acc_code,coa4_id','chartOfAccount.accountCode:id,acc_desc,acc_code']); // Select only needed fields
                    }
                ])
                ->limit(5)
                ->orderBy('created_at', 'desc')
                ->get()->map(function ($voucher) {
                    // Add a new column "status"
                    $voucher->search = 'acc_desc';
                    return $voucher;
                });
            }

            // $vouchers = Voucher::where(function($q) use($request) {
            //     $q->where('voucher_no', 'LIKE', '%'.$request->searchParms.'%');
            // })
            // ->orWhereHas('voucherDetails.chartOfAccount', function ($q) use ($request) {
            //     $q->where('acc_desc', 'LIKE', '%'.$request->searchParms.'%')
            //       ->orWhere('acc_code', 'LIKE', '%'.$request->searchParms.'%');
            // })
            // ->with([
            //     'voucherType',
            //     'voucherDetails' => function ($q) use ($request) {
            //         $q->whereHas('chartOfAccount', function ($q) use ($request) {
            //             $q->where('acc_desc', 'LIKE', '%'.$request->searchParms.'%')
            //               ->orWhere('acc_code', 'LIKE', '%'.$request->searchParms.'%');
            //         })->with(['chartOfAccount:id,acc_desc,acc_code,coa4_id','chartOfAccount.accountCode:id,acc_desc,acc_code']); // Select only needed fields
            //     }
            // ])
            // ->limit(5)
            // ->orderBy('created_at', 'desc')
            // ->get();

            // $data = new VoucherDetail();
            // $data = $data->where(function($q) use($request){
            //     $q->where('voucher_no', 'LIKE', '%'.$request->searchParms.'%');
            // })->limit(5)->orderBy('created_at','desc')->get();
            
        } else if($request->searchFrom == 'Donor' && file_exists(base_path('config/donation.php'))){

            if(file_exists(base_path('config/donation.php'))) {
                $donors = DonorFund::with('donor')->where(function($q) use($request){
                    $q->where('account_on', 'LIKE', '%'.$request->searchParms.'%');
                    $q->orWhere('donor_serial', 'LIKE', $request->searchParms.'%');
                    $q->orWhere('narration', 'LIKE', '%'.$request->searchParms.'%');
                })
                ->orWhereHas('donor', function ($q) use ($request) {
                    $q->where('name', 'LIKE', '%'.$request->searchParms.'%');
                    $q->orWhere('description', 'LIKE', '%'.$request->searchParms.'%');
                })
                ->orWhereHas('donor.coa', function ($q) use ($request) {
                    $q->where('acc_desc', 'LIKE', '%'.$request->searchParms.'%');
                    $q->orWhere('acc_code', 'LIKE', '%'.$request->searchParms);
                })
                ->limit(5)
                ->orderBy('created_at', 'desc')
                ->get();
            }

        } else if($request->searchFrom == 'Lab' && file_exists(base_path('config/lab.php'))){

            $labs = PatientTest::where(function($q) use($request){
                $q->where('lab_no', 'LIKE', '%'.$request->searchParms.'%');
                $q->orWhere('patient_name', 'LIKE', $request->searchParms.'%');
                $q->orWhere('patient_cnic', 'LIKE', '%'.$request->searchParms.'%');
                $q->orWhere('patient_email', 'LIKE', '%'.$request->searchParms.'%');
                $q->orWhere('file_no', 'LIKE', '%'.$request->searchParms.'%');
                $q->orWhere('lab_serial', 'LIKE', '%'.$request->searchParms.'%');
            })
            ->limit(5)
            ->orderBy('created_at', 'desc')
            ->get();

        } else if($request->searchFrom == 'Expense' && file_exists(base_path('config/expense.php'))){

            if(file_exists(base_path('config/exepnse.php'))) {
                $expenses = Expense::where(function($q) use($request){
                    $q->where('description', 'LIKE', '%'.$request->searchParms.'%');
                    $q->orWhere('receipt_no', 'LIKE', $request->searchParms.'%');
                    $q->orWhere('approved_by', 'LIKE', '%'.$request->searchParms.'%');
                })
                ->orWhereHas('coa', function ($q) use ($request) {
                    $q->where('acc_desc', 'LIKE', '%'.$request->searchParms.'%');
                    $q->orWhere('acc_code', 'LIKE', '%'.$request->searchParms);
                })
                ->limit(5)
                ->orderBy('created_at', 'desc')
                ->get();
            }
        }
        else if($request->searchFrom == 'OPD'){
            $appointments = Appointment::where(function($q) use($request) {
                $q->where('patient_name', 'LIKE', '%'.$request->searchParms.'%')
                  ->orWhere('patient_cnic', 'LIKE', $request->searchParms.'%')
                  ->orWhere('patient_phone', 'LIKE', '%'.$request->searchParms.'%')
                  ->orWhere('patient_email', 'LIKE', $request->searchParms.'%')
                  ->orWhere('file_no', $request->searchParms);
            })
            ->orWhereHas('patient', function ($q) use ($request) {
                $q->where('mr_number', 'LIKE', '%'.$request->searchParms.'%');
            })
            ->with('patient','doctor')
            ->limit(5)
            ->orderBy('created_at', 'desc')
            ->get();
        
        } else if($request->searchFrom == 'IPD'){

            $admissions = Admission::where(function($q) use($request){
                $q->where('name', 'LIKE', '%'.$request->searchParms.'%');
                $q->orWhere('cnic', 'LIKE', $request->searchParms.'%');
                $q->orWhere('phone', 'LIKE', '%'.$request->searchParms.'%');
                $q->orWhere('file_no', $request->searchParms);
                $q->orWhere('Ipd_serial', $request->searchParms);
            })
            ->orWhereHas('patient', function ($q) use ($request) {
                $q->where('mr_number', 'LIKE', '%'.$request->searchParms.'%');
            })
            ->with('patient')
            ->limit(5)
            ->orderBy('created_at', 'desc')
            ->get();

        }
        else {
            $appointments = Appointment::where(function($q) use($request) {
                $q->where('patient_name', 'LIKE', '%'.$request->searchParms.'%')
                  ->orWhere('patient_cnic', 'LIKE', $request->searchParms.'%')
                  ->orWhere('patient_phone', 'LIKE', '%'.$request->searchParms.'%')
                  ->orWhere('patient_email', 'LIKE', $request->searchParms.'%')
                  ->orWhere('file_no', $request->searchParms);
            })
            ->orWhereHas('patient', function ($q) use ($request) {
                $q->where('mr_number', 'LIKE', '%'.$request->searchParms.'%');
            })
            ->with('patient','doctor')
            ->limit(5)
            ->orderBy('created_at', 'desc')
            ->get();
        

            $admissions = Admission::where(function($q) use($request){
                $q->where('name', 'LIKE', '%'.$request->searchParms.'%');
                $q->orWhere('cnic', 'LIKE', $request->searchParms.'%');
                $q->orWhere('phone', 'LIKE', '%'.$request->searchParms.'%');
                $q->orWhere('file_no', $request->searchParms);
                $q->orWhere('Ipd_serial', $request->searchParms);
            })
            ->orWhereHas('patient', function ($q) use ($request) {
                $q->where('mr_number', 'LIKE', '%'.$request->searchParms.'%');
            })
            ->with('patient')
            ->limit(5)
            ->orderBy('created_at', 'desc')
            ->get();


            $labs = PatientTest::where(function($q) use($request){
                $q->where('lab_no', 'LIKE', '%'.$request->searchParms.'%');
                $q->orWhere('patient_name', 'LIKE', $request->searchParms.'%');
                $q->orWhere('patient_cnic', 'LIKE', '%'.$request->searchParms.'%');
                $q->orWhere('patient_email', 'LIKE', '%'.$request->searchParms.'%');
                $q->orWhere('file_no', 'LIKE', '%'.$request->searchParms.'%');
                $q->orWhere('lab_serial', 'LIKE', '%'.$request->searchParms.'%');
            })
            ->limit(5)
            ->orderBy('created_at', 'desc')
            ->get();

        }
        
        return response()->json([
            'vouchers' => $vouchers,
            'appointments' => $appointments,
            'admissions' => $admissions,
            'donors' => $donors ?? null,
            'expenses' => $expenses ?? null,
            'labs' => $labs,
        ]);
    }

}
