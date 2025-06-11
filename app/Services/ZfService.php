<?php

namespace App\Services;

use App\Models\IPD\Admission;
use App\Models\Lab\PatientTest;
use App\Models\OPD\Appointment;
use XelentAbrar\HospitalDonation\Models\Donation\Donor;
use XelentAbrar\HospitalDonation\Models\Donation\DonorFund;

class ZfService
{
    function getZfFees() {
        if(file_exists(base_path('config/donation.php'))) {
            $zfs = DonorFund::whereIn('donor_id',Donor::where('type','zf')->pluck('id'))->sum('charges');
            $zf_fees = $zfs - Appointment::sum('zf_fee');
            if(file_exists(base_path('config/lab.php'))) {
                $zf_fees = $zf_fees - PatientTest::sum('zf_fee');
            }
            $zf_fees = $zf_fees - Admission::sum('zf_fee');
            return $zf_fees;
        }
    }
}
