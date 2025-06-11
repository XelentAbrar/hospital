<?php

namespace Database\Seeders;

use App\Models\Accounts\AccountCode;
use App\Models\Accounts\ChartOfAccount;
use App\Models\Accounts\ControlAccount;
use App\Models\Accounts\SubControlAccount;
use App\Models\Accounts\SubHeadAccount;
use App\Models\Accounts\Voucher;
use App\Models\Accounts\VoucherDetail;
use App\Models\Accounts\VoucherType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Schema::disableForeignKeyConstraints();
        // VoucherType::truncate();
        // VoucherDetail::truncate();
        // Voucher::truncate();
        // ControlAccount::truncate();
        // SubControlAccount::truncate();
        // SubHeadAccount::truncate();
        // AccountCode::truncate();
        // ChartOfAccount::truncate();
        // Schema::enableForeignKeyConstraints();

        // $level1 = DB::connection('mysql2')->table('useraccount')->where('aLevel','1')->get();
        // foreach ($level1 as $dept) {
        //     if(!ControlAccount::where('acc_code', $dept->Code)->exists()){
        //         ControlAccount::create([
        //             'acc_code' => $dept->Code,
        //             'acc_desc' => $dept->Name,
        //         ]);
        //     }
        // }


        // $level2 = DB::connection('mysql2')->table('useraccount')->where('aLevel','2')->get();
        // foreach ($level2 as $dept) {
        //     if(!SubControlAccount::where('acc_code', $dept->Code)->exists()){
        //         $sub_control_account = SubControlAccount::create([
        //             'coa1_id' => ControlAccount::where('acc_code',$dept->ParentCode)->value('id'),
        //             'acc_code' => $dept->Code,
        //             'acc_desc' => $dept->Name,
        //         ]);
        //         $sub_head_account = SubHeadAccount::create([
        //             'coa2_id' => $sub_control_account->id,
        //             'acc_code' => $dept->Code,
        //             'acc_desc' => $dept->Name,
        //         ]);
        //         AccountCode::create([
        //             'coa3_id' => $sub_head_account->id,
        //             'acc_code' => $dept->Code,
        //             'acc_desc' => $dept->Name,
        //         ]);
        //     }
        // }


        // $level3 = DB::connection('mysql2')->table('useraccount')->where('aLevel','3')->get();
        // foreach ($level3 as $dept) {
        //     if(!ChartOfAccount::where('acc_code',$dept->Code)->exists()){
        //         $sub_control_account = ChartOfAccount::create([
        //             'coa4_id' => AccountCode::where('acc_code',$dept->ParentCode)->value('id'),
        //             'acc_code' => $dept->Code,
        //             'acc_desc' => $dept->Name,
        //         ]);
        //     }
        // }

        // $type = DB::connection('mysql2')->table('vouchertype')->get();
        // foreach ($type as $dept) {
        //     if(!VoucherType::where('voucher_name', $dept->name)->exists()){
        //         VoucherType::create([
        //             'voucher_name' => $dept->name,
        //             'voucher_abrv' => $dept->abb,
        //         ]);
        //     }
        // }


        // $type = DB::connection('mysql2')->table('voucher')->get();
        // foreach ($type as $dept) {
        //     $v_id = Voucher::create([
        //         'voucher_type_id' => $dept->vType,
        //         'voucher_date' => $dept->dated,
        //         'voucher_no' => $dept->vno,
        //         'narration' => $dept->description,
        //     ]);
        //     $type_Detail = DB::connection('mysql2')->table('voucherdetail')->where('vouchid', $dept->Vouchid)->get();
        //     foreach ($type_Detail as $detail) {
        //         VoucherDetail::create([
        //             'voucher_id' => $v_id->id,
        //             'acc_code' => ChartOfAccount::where('acc_code',$detail->code)->value('id'),
        //             'dr' => $detail->debit,
        //             'cr' => $detail->credit,
        //             'transaction_type' => $detail->chaque,
        //             'transaction_no' => '',
        //             'remarks' => $detail->remarks,
        //         ]);    
        //     }
        // }

        // $level1 = ControlAccount::get();
        // $level1 = DB::connection('mysql2')->table('control_accounts')->get();
        // foreach ($level1 as $dept) {
        //     ControlAccount::create([
        //         'acc_desc' => $dept->acc_desc,
        //         'old_acc_code' => $dept->acc_code,
        //         'old_acc_id' => $dept->id,
        //     ]);
        // }


        // $level2 = DB::connection('mysql2')->table('sub_control_accounts')->get();
        // foreach ($level2 as $dept) {
        //     $sub_control_account = SubControlAccount::create([
        //         'coa1_id' => ControlAccount::where('old_acc_id',$dept->coa1_id)->value('id'),
        //         'old_acc_code' => $dept->acc_code,
        //         'old_acc_id' => $dept->id,
        //         'acc_desc' => $dept->acc_desc,
        //     ]);
        //     $sub_head_account = SubHeadAccount::create([
        //         'coa2_id' => $sub_control_account->id,
        //         'old_acc_code' => $dept->acc_code,
        //         'old_acc_id' => $dept->id,
        //         'acc_desc' => $dept->acc_desc,
        //     ]);
        //     AccountCode::create([
        //         'coa3_id' => $sub_head_account->id,
        //         'old_acc_code' => $dept->acc_code,
        //         'old_acc_id' => $dept->id,
        //         'acc_desc' => $dept->acc_desc,
        //     ]);
        // }

        // $level3 = DB::connection('mysql2')->table('chart_of_accounts')->get();
        // foreach ($level3 as $dept) {
        //     $sub_control_account = ChartOfAccount::create([
        //         'coa4_id' => AccountCode::where('old_acc_id',$dept->coa4_id)->value('id'),
        //         'old_acc_code' => $dept->acc_code,
        //         'old_acc_id' => $dept->id,
        //         'acc_desc' => $dept->acc_desc,
        //     ]);
        // }

        
        // $type = DB::connection('mysql2')->table('voucher_types')->get();
        // foreach ($type as $dept) {
        //     VoucherType::create([
        //         'voucher_name' => $dept->voucher_name,
        //         'voucher_abrv' => $dept->voucher_abrv,
        //     ]);
        // }


        // $type = DB::connection('mysql2')->table('vouchers')->get();
        // foreach ($type as $temp) {
        //     $v_id = Voucher::create([
        //         'voucher_type_id' => $temp->voucher_type_id,
        //         'voucher_date' => $temp->voucher_date,
        //         'voucher_no' => $temp->voucher_no,
        //         'narration' => $temp->narration,
        //     ]);
            
        //     \Log::info($v_id->id);
        //     $type_Detail = DB::connection('mysql2')->table('voucher_details')->where('voucher_id', $v_id->id)->get();
        //     foreach ($type_Detail as $detail) {
        //         VoucherDetail::create([
        //             'voucher_id' => $v_id->id,
        //             'acc_code' => ChartOfAccount::where('old_acc_id',$detail->acc_code)->value('id'),
        //             'dr' => $detail->dr,
        //             'cr' => $detail->cr,
        //             'transaction_type' => $detail->transaction_type,
        //             'transaction_no' => '',
        //             'remarks' => $detail->remarks,
        //         ]);    
        //     }
        // }




        // $old_vouchers = Voucher::where('voucher_date', '>', date('Y-m-d', strtotime('2024-06-30')))->with('voucherDetails')->get();
        // $old_voucher_detail = VoucherDetail::whereIn('voucher_id', $old_vouchers->pluck('id'))->get();

        $id = Voucher::withTrashed()->where('voucher_date', '<', date('Y-m-d', strtotime('2024-07-01')))->pluck('id');
        VoucherDetail::whereIn('voucher_id', $id)->withTrashed()->forceDelete();
        Voucher::withTrashed()->whereIn('id', $id)->forceDelete();
        
        $level1 = DB::connection('mysql2')->table('useraccount')->where('aLevel','1')->get();
        foreach ($level1 as $dept) {
            if(!ControlAccount::where('acc_code', $dept->Code)->exists()){
                ControlAccount::create([
                    'acc_code' => $dept->Code,
                    'acc_desc' => $dept->Name,
                    'created_at' => date('Y-m-d H:i:s', strtotime($dept->dated)),
                ]);
            }
        }


        $level2 = DB::connection('mysql2')->table('useraccount')->where('aLevel','2')->get();
        foreach ($level2 as $dept) {
            if(!SubControlAccount::where('acc_code', $dept->Code)->exists()){
                $sub_control_account = SubControlAccount::create([
                    'coa1_id' => ControlAccount::where('acc_code',$dept->ParentCode)->value('id'),
                    'acc_code' => $dept->Code,
                    'acc_desc' => $dept->Name,
                    'created_at' => date('Y-m-d H:i:s', strtotime($dept->dated)),
                ]);
                $sub_head_account = SubHeadAccount::create([
                    'coa2_id' => $sub_control_account->id,
                    'acc_code' => $dept->Code,
                    'acc_desc' => $dept->Name,
                    'created_at' => date('Y-m-d H:i:s', strtotime($dept->dated)),
                ]);
                AccountCode::create([
                    'coa3_id' => $sub_head_account->id,
                    'acc_code' => $dept->Code,
                    'acc_desc' => $dept->Name,
                    'created_at' => date('Y-m-d H:i:s', strtotime($dept->dated)),
                ]);
            }
        }

        $level3 = DB::connection('mysql2')->table('useraccount')->where('aLevel','3')->get();
        foreach ($level3 as $dept) {
            if(!ChartOfAccount::where('acc_code',$dept->Code)->exists()){
                $sub_control_account = ChartOfAccount::create([
                    'coa4_id' => AccountCode::where('acc_code',$dept->ParentCode)->value('id'),
                    'acc_code' => $dept->Code,
                    'acc_desc' => $dept->Name,
                ]);
            }
        }



        ChartOfAccount::where('id', '>',0)->update(['opening' => 0]);

        $level3 = DB::connection('mysql2')->table('useraccount')->where('aLevel','3')->where('opdr','!=','0')->get();
        foreach ($level3 as $dept) {
            if(ChartOfAccount::where('acc_code',$dept->Code)->exists()){
                ChartOfAccount::where('acc_code',$dept->Code)->update(['opening' => $dept->opdr]);
            }
        }

        $level3 = DB::connection('mysql2')->table('useraccount')->where('aLevel','3')->where('opcr','!=','0')->get();
        foreach ($level3 as $dept) {
            if(ChartOfAccount::where('acc_code',$dept->Code)->exists()){
                ChartOfAccount::where('acc_code',$dept->Code)->update(['opening' => +$dept->opcr * -1]);
            }
        }

        $type = DB::connection('mysql2')->table('vouchertype')->get();
        foreach ($type as $dept) {
            if(!VoucherType::where('voucher_name', $dept->name)->exists()){
                VoucherType::create([
                    'voucher_name' => $dept->name,
                    'voucher_abrv' => $dept->abb,
                ]);
            }
        }


        $type = DB::connection('mysql2')->table('voucher')->get();
        foreach ($type as $dept) {
            $v_id = Voucher::create([
                'voucher_type_id' => $dept->vType,
                'voucher_date' => date('Y-m-d', strtotime($dept->dated)),
                'created_at' => date('Y-m-d H:i:s', strtotime($dept->dated)),
                'is_posted' => 1,
                'voucher_no' => $dept->vno,
                'narration' => $dept->description,
                'deleted_at' => $dept->markdelete == '1' ? date('Y-m-d H:i:s', strtotime($dept->dated)) : null,
            ]);
            $type_Detail = DB::connection('mysql2')->table('voucherdetail')->where('vouchid', $dept->Vouchid)->get();
            foreach ($type_Detail as $detail) {
                VoucherDetail::create([
                    'voucher_id' => $v_id->id,
                    'acc_code' => ChartOfAccount::where('acc_code',$detail->code)->value('id'),
                    'dr' => $detail->debit,
                    'cr' => $detail->credit,
                    'transaction_type' => $detail->chaque != null && $detail->chaque != '' ? 'Cheque' : null,
                    'transaction_no' => $detail->chaque != null && $detail->chaque != '' ? $detail->chaque : null,
                    'remarks' => $detail->remarks,
                    'deleted_at' => $dept->markdelete == '1' ? date('Y-m-d H:i:s', strtotime($dept->dated)) : null,
                ]);    
            }
        }

        // foreach ($old_vouchers as $dept) {
        //     $id = $id+1;
        //     $v_id = Voucher::create([
        //         'id' => $id,
        //         'voucher_type_id' => $dept->voucher_type_id,
        //         'voucher_date' => date('Y-m-d', strtotime($dept->voucher_date)),
        //         'created_at' => date('Y-m-d H:i:s', strtotime($dept->created_at)),
        //         'is_posted' => $dept->is_posted,
        //         'voucher_no' => $dept->voucher_no,
        //         'narration' => $dept->narration,
        //         'deleted_at' => $dept->deleted_at,
        //     ]);
        //     foreach ($old_voucher_detail->where('voucher_id', $dept->id) as $detail) {
        //         VoucherDetail::create([
        //             'voucher_id' => $v_id->voucher_id,
        //             'acc_code' => $detail->acc_code,
        //             'dr' => $detail->dr,
        //             'cr' => $detail->cr,
        //             'transaction_type' => $detail->transaction_type,
        //             'transaction_no' => $detail->transaction_no,
        //             'remarks' => $detail->remarks,
        //             'deleted_at' => $detail->deleted_at,
        //         ]);    
        //     }
        // }

        





    //     SELECT sum(vd.credit), sum(vd.debit)  FROM voucherdetail vd, voucher v WHERE vd.code  LIKE '11102%'  AND vd.markdelete = 0 AND v.dated BETWEEN '2022-07-01' AND '2023-06-30' AND vd.vouchid = v.Vouchid



    //     $type1 = DB::connection('mysql2')->select("
    // SELECT 
    //     vno
    // FROM 
    //     voucherdetail vd
    // JOIN 
    //     voucher v ON vd.vouchid = v.Vouchid
    // WHERE 
    //     vd.code LIKE '11102%' 
    //     AND vd.markdelete = 0 
    //     AND v.dated BETWEEN '2022-07-01' AND '2023-06-30'
    // ");

    // $type2 = DB::connection('mysql')->select("
    // SELECT voucher_no  FROM `voucher_details` vd, vouchers v WHERE `acc_code` in (select id from chart_of_accounts where acc_code LIKE '11102%')  AND vd.deleted_at is null AND v.voucher_date BETWEEN '2022-07-01' AND '2023-06-30' AND vd.voucher_id = v.id
    // ");
    // $array1 = [];
    // foreach ($type1 as $dept) {
    //     $array1[] = $dept->vno;
    // }
    
    // $array2 = [];
    // foreach ($type2 as $dept) {
    //     $array2[] = $dept->voucher_no;
    // }

        
    }
}
