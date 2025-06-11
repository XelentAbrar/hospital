<?php

namespace App\Services;

use App\Models\OPD\Donor;
use App\Models\Lab\LabTest;
use App\Models\OPD\Service;
use App\Models\HRMS\Employee;
use XelentAbrar\HospitalAccounts\Models\Accounts\VoucherAudit;
use XelentAbrar\HospitalAccounts\Models\Accounts\ChartOfAccount;
use XelentAbrar\HospitalAccounts\Models\Accounts\VoucherAuditDetail;
use XelentAbrar\HospitalAccounts\Models\Accounts\VoucherDetail;
use XelentAbrar\HospitalAccounts\Models\AccountSetting;
use App\Models\HRMS\UserCashSummary;

class VoucherAuditService
{
    function storeVoucher($type, $data, $status, $parent_id = null, $reverse = null) {
        if(!file_exists(base_path('config/accounts.php'))) {
            return 0;
        }
        if($type == 'opd'){
            if($status == 'store'){
                $data['id'] = $parent_id;
                return $this->storeOpd($data, $reverse);
            }
            else if(VoucherAudit::where('type', 'opd')->where('parent_id', $parent_id)->exists()){
                $id = VoucherAudit::where('type', 'opd')->where('parent_id', $parent_id)->value('id');
                VoucherAuditDetail::where('voucher_audit_id', $id)->delete();
                VoucherAudit::whereId($id)->delete();
                $data['id'] = $parent_id;
                return $this->storeOpd($data);
            }
            else{
                return 0;
            }
        }
        else if($type == 'ipd'){
            $data['id'] = $parent_id;
            if($data['status'] == 'Complete'){
                if(VoucherAudit::whereType('ipd')->where('parent_id', $data['id'])->exists()){
                    $voucher_ids = VoucherAudit::whereType('ipd')->where('parent_id', $data['id'])->pluck('id');
                    VoucherAuditDetail::whereIn('voucher_audit_id', $voucher_ids)->whereNotIn('system_type',['pre_advance','pre_cash'])->delete();
                    VoucherAudit::doesntHave('voucherAuditDetails')->delete();
                }
            }
            return $this->storeIpd($data, $reverse);
        }
    }

    function storeIpd($data, $type = null, $voucher_created = null) {
        $advCoaValue = AccountSetting::where('key', 'Advance')->value('value');
        $cashCoaValue = AccountSetting::where('key', 'cash_in_hand')->value('value');
        $adv_coa_id = ChartOfAccount::where('acc_code', $advCoaValue)->value('id');
        $cash_coa_id = ChartOfAccount::where('acc_code', $cashCoaValue)->value('id');
        $MiscValue = AccountSetting::where('key', 'MISC_RECEIPTS')->value('value');
        $zFValue = AccountSetting::where('key', 'ZF_Patients')->value('value');
        $discValue = AccountSetting::where('key', 'DISCOUNT')->value('value');
        $voucher = VoucherAudit::create([
            'voucher_type_id' => '1',
            'type' => 'ipd',
            'parent_id' => $data['id'],
            'voucher_date' => date('Y-m-d'),
            // 'narration' => $data['id'].' '.$data['name'],
            'narration' => 'Slip No : ' . $data['id'] . ' - ' . $data['name'],
            'created_by' => \Auth::id()
        ]);

        // $adv_coa_id = ChartOfAccount::where('acc_code', '111051001')->value('id');
        // $cash_coa_id = ChartOfAccount::where('acc_code', '101051001')->value('id');
        // $accCodes = ChartOfAccount::pluck('acc_code');
        // $cash_coa_id = AccountSetting::whereIn('value', $accCodes)->get('value');
        // $adv_coa_id = AccountSetting::whereIn('value', $accCodes)->get('value');

        $check = 0;
        if($data['status'] != 'Complete'){
            foreach($data['details'] as $details){
                if(isset($details['service']['id']) && $details['day_charges'] > 0 && $details['day_charges'] != null && (!isset($details['is_posted']) || $details['is_posted'] == '0')){
                    $coa_id = Service::whereId($details['service']['id'])->value('coa_id');
                    $coa_id = $coa_id ?? ChartOfAccount::where('acc_code', $MiscValue)->value('id');
                    // $coa_id = $coa_id ?? ChartOfAccount::where('acc_code', '131011030')->value('id');
                    $service_id = $details['service']['id'];
                    $amount = ($details['day_charges'] > 0 ? $details['day_charges'] : $details['service_amount']);
                    if($details['service']['is_advance'] == '1'){
                        $check = 1;
                        VoucherAuditDetail::create([
                            'voucher_audit_id' => $voucher->id,
                            'acc_code' => $adv_coa_id,
                            'dr' => $type == 'reverse' ? $amount : 0,
                            'cr' => $type == 'reverse' ? 0 : $amount,
                            'transaction_type' => 'ipd',
                            'transaction_no' => $data['id'],
                            'system_type' => 'pre_advance',
                            'remarks' => null
                        ]);
                        VoucherAuditDetail::create([
                            'voucher_audit_id' => $voucher->id,
                            'acc_code' => $cash_coa_id,
                            'dr' => $type == 'reverse' ? 0 : $amount,
                            'cr' => $type == 'reverse' ? $amount : 0,
                            'transaction_type' => 'ipd',
                            'transaction_no' => $data['id'],
                            'system_type' => 'pre_cash',
                            'remarks' => null
                        ]);
                    }
                    $userId = $data['created_by'] ?? \Auth::id();
                    $userCashSummary = UserCashSummary::where('user_id', $userId)
                                                    ->where('status', 'pending')
                                                    ->latest()
                                                    ->first();

                    if (!$userCashSummary) {
                        UserCashSummary::create([
                            'user_id' => $userId,
                            'receipt_count' => 1,
                            'total_amount' => $amount,
                            'status' => 'pending',
                            'activity_time' => $data['admission_date'] . ' ' . ($data['admission_time'] ?? now()->format('H:i:s')),
                        ]);
                    } else {
                        $userCashSummary->increment('receipt_count');
                        $userCashSummary->increment('total_amount', $amount);
                        $userCashSummary->update([
                            'activity_time' => $data['admission_date'] . ' ' . ($data['admission_time'] ?? now()->format('H:i:s'))
                        ]);
                    }

                }
            }
            if($check == 0){
                VoucherAudit::whereId($voucher->id)->delete();
            }
        }
        else{
            $advance_charges = 0;
            foreach($data['details'] as $details){
                if(isset($details['service']['id']) && (($details['service_amount'] > 0 && $details['service_amount'] != null) || ($details['day_charges'] > 0 && $details['day_charges'] != null))){
                    $coa_id = Service::whereId($details['service']['id'])->value('coa_id');
                    $coa_id = $coa_id ?? ChartOfAccount::where('acc_code', $MiscValue)->value('id');
                    // $coa_id = $coa_id ?? ChartOfAccount::where('acc_code', '131011030')->value('id');
                    $service_id = $details['service']['id'];
                    if($details['service']['is_advance'] == '1'){
                        $advance_charges += $details['day_charges'];
                    }
                    if($details['service']['is_advance'] != '1'){
                        VoucherAuditDetail::create([
                            'voucher_audit_id' => $voucher->id,
                            'acc_code' => $coa_id,
                            'dr' => $type == 'reverse' ? ($details['day_charges']) : 0,
                            'cr' => $type == 'reverse' ? 0 : ($details['day_charges']),
                            'transaction_type' => 'ipd',
                            'transaction_no' => $data['id'],
                            'system_type' => 'service_'.$service_id,
                            'remarks' => null
                        ]);
                    }
                }
                else if(isset($details['doctor']['id']) && $details['amount'] > 0 && $details['amount'] != null){
                    $coa_id = Employee::whereId($details['doctor']['id'])->value('coa_id');
                    $coa_id = $coa_id ?? ChartOfAccount::where('acc_code', $MiscValue)->value('id');
                    // $coa_id = $coa_id ?? ChartOfAccount::where('acc_code', '131011030')->value('id');
                    $doctor_id = $details['doctor']['id'];

                    // if($details['service']['is_advance'] != '1'){
                        VoucherAuditDetail::create([
                            'voucher_audit_id' => $voucher->id,
                            'acc_code' => $coa_id,
                            'dr' => $type == 'reverse' ? ($details['day_charges'] > 0 ? $details['day_charges'] : $details['amount']) : 0,
                            'cr' => $type == 'reverse' ? 0 : ($details['day_charges'] > 0 ? $details['day_charges'] : $details['amount']),
                            'transaction_type' => 'ipd',
                            'transaction_no' => $data['id'],
                            'system_type' => 'doctor_'.$doctor_id,
                            'remarks' => null
                        ]);
                    // }
                }
                else if(!isset($details['service']['id']) && !isset($details['doctor']['id'])){
                    $amount = $details['day_charges'];
                    if($amount > 0){
                        $coa_id = ChartOfAccount::where('acc_code', $MiscValue)->value('id');
                        // $coa_id = ChartOfAccount::where('acc_code', '131011030')->value('id');

                        if(!VoucherDetail::where('transaction_type', 'ipd')->where('transaction_no', $data['id'])->where('acc_code', $coa_id)->where('system_type', 'days_charges')->exists()){
                            VoucherAuditDetail::create([
                                'voucher_audit_id' => $voucher->id,
                                'acc_code' => $coa_id,
                                'dr' => $type == 'reverse' ? $amount : 0,
                                'cr' => $type == 'reverse' ? 0 : $amount,
                                'transaction_type' => 'ipd',
                                'transaction_no' => $data['id'],
                                'system_type' => 'days_charges',
                                'remarks' => null
                            ]);
                        }
                    }
                }
            }

            if($advance_charges > 0){
                VoucherAuditDetail::create([
                    'voucher_audit_id' => $voucher->id,
                    'acc_code' => $adv_coa_id,
                    'dr' => $type == 'reverse' ? 0 : $advance_charges,
                    'cr' => $type == 'reverse' ? $advance_charges : 0,
                    'transaction_type' => 'ipd',
                    'transaction_no' => $data['id'],
                    'system_type' => 'pre_advance',
                    'remarks' => null
                ]);

            }

            if($data['zf_fee'] > 0){
                $cos_id = ChartOfAccount::where('acc_code', $zFValue)->value('id');
                // $cos_id = ChartOfAccount::where('acc_code', '121101001')->value('id');
                VoucherAuditDetail::create([
                    'voucher_audit_id' => $voucher->id,
                    'acc_code' => $cos_id,
                    'dr' => $type == 'reverse' ? 0 : $data['zf_fee'],
                    'cr' => $type == 'reverse' ? $data['zf_fee'] : 0,
                    'transaction_type' => 'ipd',
                    'transaction_no' => $data['id'],
                    'system_type' => 'zf_amount',
                    'remarks' => null
                ]);
            }

            if($data['careoff_id'] && file_exists(base_path('config/donation.php'))){
                $donor = Donor::whereId($data['careoff_id'])->first();
                if($donor->coa_id){
                    VoucherAuditDetail::create([
                        'voucher_audit_id' => $voucher->id,
                        'acc_code' => $donor->coa_id,
                        'dr' => $type == 'reverse' ? 0 : $data['donor_fee'],
                        'cr' => $type == 'reverse' ? $data['donor_fee'] : 0,
                        'transaction_type' => 'ipd',
                        'transaction_no' => $data['id'],
                        'system_type' => 'co_amount',
                        'remarks' => null
                    ]);
                }
            }

            if($data['discount_amount'] > 0){
                $disc_coa_id = ChartOfAccount::where('acc_code', $discValue)->value('id');
                // $disc_coa_id = ChartOfAccount::where('acc_code', '121101023')->value('id');
                VoucherAuditDetail::create([
                    'voucher_audit_id' => $voucher->id,
                    'acc_code' => $disc_coa_id,
                    'dr' => $type == 'reverse' ? 0 : $data['discount_amount'],
                    'cr' => $type == 'reverse' ? $data['discount_amount'] : 0,
                    'transaction_type' => 'ipd',
                    'transaction_no' => $data['id'],
                    'system_type' => 'disc_amount',
                    'remarks' => null
                ]);
            }


            if($data['received_amount'] > 0){
                VoucherAuditDetail::create([
                    'voucher_audit_id' => $voucher->id,
                    'acc_code' => $cash_coa_id,
                    'dr' => $type == 'reverse' ? 0 : $data['received_amount'],
                    'cr' => $type == 'reverse' ? $data['received_amount'] : 0,
                    'transaction_type' => 'ipd',
                    'transaction_no' => $data['id'],
                    'system_type' => 'received_amount',
                    'remarks' => null
                ]);
                $userId = $data['created_by'] ?? \Auth::id();
                $userCashSummary = UserCashSummary::where('user_id', $userId)
                                                ->latest()
                                                ->first();

                if (!$userCashSummary || $userCashSummary->status == 'complete') {
                    UserCashSummary::create([
                        'user_id' => $userId,
                        'receipt_count' => 1,
                        'total_amount' => $data['received_amount'],
                        'status' => 'pending',
                        'activity_time' => $data['admission_date'] . ' ' . ($data['admission_time'] ?? now()->format('H:i:s')),
                    ]);
                } else {
                    $userCashSummary->increment('receipt_count');
                    $userCashSummary->increment('total_amount', $data['received_amount']);
                    $userCashSummary->update([
                        'activity_time' => $data['admission_date'] . ' ' . ($data['admission_time'] ?? now()->format('H:i:s'))
                    ]);
                }
            }
            if($data['total_amount'] > 0){
                VoucherAuditDetail::create([
                    'voucher_audit_id' => $voucher->id,
                    'acc_code' => $cash_coa_id,
                    'dr' => $type == 'reverse' ? 0 : $data['total_amount'],
                    'cr' => $type == 'reverse' ? $data['total_amount'] : 0,
                    'transaction_type' => 'ipd',
                    'transaction_no' => $data['id'],
                    'system_type' => 'total_amount',
                    'remarks' => null
                ]);
                $userId = $data['created_by'] ?? \Auth::id();
                $userCashSummary = UserCashSummary::where('user_id', $userId)
                                                ->latest()
                                                ->first();

                if (!$userCashSummary || $userCashSummary->status == 'complete') {
                    UserCashSummary::create([
                        'user_id' => $userId,
                        'receipt_count' => 1,
                        'total_amount' => $data['total_amount'],
                        'status' => 'pending',
                        'activity_time' => $data['admission_date'] . ' ' . ($data['admission_time'] ?? now()->format('H:i:s')),
                    ]);
                } else {
                    $userCashSummary->increment('receipt_count');
                    $userCashSummary->increment('total_amount', $data['total_amount']);
                    $userCashSummary->update([
                        'activity_time' => $data['admission_date'] . ' ' . ($data['admission_time'] ?? now()->format('H:i:s'))
                    ]);
                }

            }
            if($data['refund_amount'] > 0){
                VoucherAuditDetail::create([
                    'voucher_audit_id' => $voucher->id,
                    'acc_code' => $cash_coa_id,
                    'dr' => $type == 'reverse' ? $data['refund_amount'] : 0,
                    'cr' => $type == 'reverse' ? 0 : $data['refund_amount'],
                    'transaction_type' => 'ipd',
                    'transaction_no' => $data['id'],
                    'system_type' => 'refund_amount',
                    'remarks' => null
                 ]);

                 $userId = $data['created_by'] ?? \Auth::id();
                 $userCashSummary = UserCashSummary::where('user_id', $userId)
                                                   ->latest()
                                                   ->first();

                 if (!$userCashSummary || $userCashSummary->status == 'complete') {
                     UserCashSummary::create([
                         'user_id' => $userId,
                         'receipt_count' => 1,
                         'total_amount' => -$data['refund_amount'],
                         'status' => 'pending',
                         'activity_time' => $data['admission_date'] . ' ' . ($data['admission_time'] ?? now()->format('H:i:s')),
                     ]);
                 } else {
                     $userCashSummary->increment('receipt_count');
                     $userCashSummary->decrement('total_amount', $data['refund_amount']);
                     $userCashSummary->update([
                         'activity_time' => $data['admission_date'] . ' ' . ($data['admission_time'] ?? now()->format('H:i:s'))
                     ]);
                 }

            }
        }

        return 1;
    }


    function storeOpd($data, $type = null) {
        $advCoaValue = AccountSetting::where('key', 'Advance')->value('value');
        $cashCoaValue = AccountSetting::where('key', 'cash_in_hand')->value('value');
        $zFValue = AccountSetting::where('key', 'ZF_Patients')->value('value');
        $voucher = VoucherAudit::create([
            'voucher_type_id' => '1',
            'type' => 'opd',
            'parent_id' => $data['id'],
            'voucher_date' => $data['appointment_date'],
            // 'narration' => $data['id'].' '.$data['patient_name'],
            'narration' => 'Slip No : ' . $data['id'] . ' - ' . $data['patient_name'],
            'created_by' => \Auth::id()
        ]);
        $fee = $data['appointment_details'][0]['fee'] ?? 0;

        $coa_id = Employee::where('id', $data['consulting_doctor_id'])->value('coa_id');
        $coh = $fee - +$data['zf_fee'] - +$data['donor_fee'];

        VoucherAuditDetail::create([
            'voucher_audit_id' => $voucher->id,
            'acc_code' => $coa_id,
            'dr' => $type == 'reverse' ? $fee : 0,
            'cr' => $type == 'reverse' ? 0 : $fee,
            'transaction_type' => 'opd',
            'transaction_no' => $data['id'],
            'remarks' => null
        ]);

        if($data['zf_fee'] > 0){
            VoucherAuditDetail::create([
                'voucher_audit_id' => $voucher->id,
                'acc_code' => ChartOfAccount::where('acc_code', $zFValue)->value('id'),
                // 'acc_code' => ChartOfAccount::where('acc_code', '121101001')->value('id'),
                'dr' => $type == 'reverse' ? 0 : $data['zf_fee'],
                'cr' => $type == 'reverse' ? $data['zf_fee'] : 0,
                'transaction_type' => 'opd',
                'transaction_no' => $data['id'],
                'remarks' => null
            ]);
        }

        if($data['careoff_id'] && file_exists(base_path('config/donation.php'))){
            $donor = Donor::whereId($data['careoff_id'])->first();
            if($donor->coa_id){
                VoucherAuditDetail::create([
                    'voucher_audit_id' => $voucher->id,
                    'acc_code' => $donor->coa_id,
                    'dr' => $type == 'reverse' ? 0 : $data['donor_fee'],
                    'cr' => $type == 'reverse' ? $data['donor_fee'] : 0,
                    'transaction_type' => 'opd',
                    'transaction_no' => $data['id'],
                    'remarks' => null
                ]);
            }
        }

        if($coh > 0){
            VoucherAuditDetail::create([
                'voucher_audit_id' => $voucher->id,
                'acc_code' => ChartOfAccount::where('acc_code', $cashCoaValue)->value('id'),
                // 'acc_code' => ChartOfAccount::where('acc_code', '101051001')->value('id'),
                'dr' => $type == 'reverse' ? 0 : $coh,
                'cr' => $type == 'reverse' ? $coh : 0,
                'transaction_type' => 'opd',
                'transaction_no' => $data['id'],
                'remarks' => null
            ]);

        }
        $userId = $data['created_by'] ?? \Auth::id();
        $userCashSummary = UserCashSummary::where('user_id', $userId)
                                          ->latest()
                                          ->first();

        if (!$userCashSummary || $userCashSummary->status == 'complete') {
            UserCashSummary::create([
                'user_id' => $userId,
                'receipt_count' => 1,
                'total_amount' => $coh,
                'status' => 'pending',
                'activity_time' => $data['appointment_date'] . ' ' . ($data['appointment_time'] ?? now()->format('H:i:s')),
            ]);
        } else {
            $userCashSummary->increment('receipt_count', 1);
            $userCashSummary->increment('total_amount', $coh);
            $userCashSummary->update([
                'activity_time' => $data['appointment_date'] . ' ' . ($data['appointment_time'] ?? now()->format('H:i:s'))
            ]);
        }

        return 1;
    }
}
