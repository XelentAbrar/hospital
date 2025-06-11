<?php

namespace App\Http\Controllers\OPD;

use App\Http\Controllers\Controller;
use App\Http\Requests\OPD\DonationRequest;
use XelentAbrar\HospitalAccounts\Models\Accounts\ChartOfAccount;
use App\Models\OPD\Donation;
use App\Models\UserRole;
use App\Services\VoucherAuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DonationController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkPermission:donations.index')->only('index');
        $this->middleware('checkPermission:donations.create')->only('create', 'store');
        $this->middleware('checkPermission:donations.show')->only('show');
        $this->middleware('checkPermission:donations.edit')->only('edit', 'update');
        $this->middleware('checkPermission:donations.destroy')->only('destroy');
    }

    public function index()
    {
        $donations = Donation::with('creator:id,name')->orderBy('created_at', 'desc');
        if(isset($_GET['search']) && $_GET['search'] != ''){
            $donations = $donations->where('donor', 'LIKE', '%'.$_GET['search'].'%');
        }
        $donations = $donations->paginate(100);
        $user = Auth::user();
        $role = UserRole::where('user_id', $user->id)->whereIn('role_id', [1, 7])->first();
        return Inertia::render('OPD/Donations/Index', [
            'donations' => $donations,
            'role' => $role,
            'filters' => request()->only(['search']),
        ]);
    }

    public function create()
    {
        $donors = Donation::orderBy('created_at', 'desc')->get();
        return Inertia::render('OPD/Donations/Create', [
            'donors' => $donors,
        ]);
    }


    public function store(DonationRequest $request)
    {
        $donation = new Donation();
        $employeeDeductionData = $request->only($donation->getFillable());
        $maxDonorSerial = Donation::max('donor_serial');
        $employeeDeductionData['donor_serial'] = intval($maxDonorSerial) ? intval($maxDonorSerial) + 1 : 1;
        $employeeDeductionData['donor_time'] = now()->format('H:i');
        $employeeDeductionData['created_by'] = auth()->id();
        $data = Donation::create($employeeDeductionData);

        $voucher_audit = new VoucherAuditService();
        $voucher_audit->storeVoucher('donation', $data, 'store');

        return redirect()->route('donations.index');
    }


    public function edit(Donation $donation)
    {
        $donors = Donation::orderBy('created_at', 'desc')->get();
        return Inertia::render('OPD/Donations/Create', [
            'donation' => $donation,
            'donors' => $donors,
        ]);
    }


    public function update(DonationRequest $request, Donation $donation)
    {
        $employeeDeductionData = $request->only($donation->getFillable());

        $voucher_audit = new VoucherAuditService();
        $voucher_audit = $voucher_audit->storeVoucher('donation', $employeeDeductionData, 'update', $donation->id);
        if(!$voucher_audit){
            return redirect()->route('donations.index')->with('message', 'Expense Already Sent to Accounts.');
        }
        $employeeDeductionData['updated_by'] = auth()->id();

        $donation->update($employeeDeductionData);

        return redirect()->route('donations.index')->with('message', 'Donation updated successfully.');
    }



    public function destroy($id)
    {
        $donation = Donation::findOrFail($id);
        $donation->delete();

        return redirect()->route('donations.index')->with('success', 'Donation deleted successfully.');
    }
    public function print(Donation $donation)
    {
        $user = Auth::user();
        $role = UserRole::where('user_id', $user->id)->where('role_id', 1)->first();
        if (!$role && $donation->is_printed == 1 && $donation->is_printed == 2) {
            return redirect()->route('donations.index')->with('error', 'This receivable has already been printed.');
        }
        $newPrintedStatus = $donation->is_printed == 0 ? 1 : 2;
        $donation->update(['is_printed' => $newPrintedStatus]);

        $donation = Donation::with('creator:id,name')->find($donation->id);
        return Inertia::render('OPD/Donations/Print', [
            'donationData' => $donation,
            'donation' => $donation->donation,
        ]);
    }
}
