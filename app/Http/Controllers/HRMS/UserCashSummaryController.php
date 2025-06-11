<?php

namespace App\Http\Controllers\HRMS;

use App\Http\Controllers\Controller;
use App\Models\HRMS\UserCashSummary;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class UserCashSummaryController extends Controller
{
    public function index(Request $request)
{
    $status = $request->input('status', 'pending');
    $search = $request->input('search');
    $fromDate = $request->input('from_date');
    $toDate = $request->input('to_date', date('Y-m-d'));

    $user = $request->user();

    $role = UserRole::where('user_id', $user->id)->first();

    $summaries = UserCashSummary::with(['user', 'createdBy', 'updatedBy'])
        ->when($status, function($query) use ($status) {
            $query->where('status', $status);
        })
        ->when($search, function($query) use ($search) {
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        })
        ->when($fromDate, function($query) use ($fromDate, $toDate) {
            $query->whereBetween('created_at', [
                $fromDate . ' 00:00:00',
                $toDate . ' 23:59:59'
            ]);
        })
        ->when($role->role_id !== 1, function($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->latest()
        ->get();
    $approvers = User::whereHas('roles', function($query) {
        $query->where('role_id', 10);
    })->get();

    return response()->json([
        'summaries' => $summaries,
        'users' => $approvers,
        'role' => $role,
    ]);
}

    // public function index(Request $request)
    // {
    //     $status = $request->input('status', 'pending');
    //     $search = $request->input('search');
    //     $fromDate = $request->input('from_date');
    //     $toDate = $request->input('to_date', date('Y-m-d'));

    //     $summaries = UserCashSummary::with(['user', 'createdBy', 'updatedBy'])
    //         ->when($status, function($query) use ($status) {
    //             $query->where('status', $status);
    //         })
    //         ->when($search, function($query) use ($search) {
    //             $query->whereHas('user', function($q) use ($search) {
    //                 $q->where('name', 'like', "%{$search}%");
    //             });
    //         })
    //         ->when($fromDate, function($query) use ($fromDate, $toDate) {
    //             $query->whereBetween('created_at', [
    //                 $fromDate . ' 00:00:00',
    //                 $toDate . ' 23:59:59'
    //             ]);
    //         })
    //         ->latest()
    //         ->get();

    //     $approvers = User::whereHas('roles', function($query) {
    //         $query->where('role_id', 10);
    //     })->get();

    //     $user = $request->user();
    //     // dd($user);
    //     $role = UserRole::where('user_id', $user->id)->where('role_id', 10)->first();
    //     return response()->json([
    //                 'summaries' => $summaries,
    //                 'users' => $approvers,
    //                 'role' => $role,
    //             ]);
    // }
        public function CashIndex(Request $request)
    {
        $status = $request->input('status', 'pending');
        $search = $request->input('search');
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date', date('Y-m-d'));

        $user = Auth::user();

        $role = UserRole::where('user_id', $user->id)->first();

        $summaries = UserCashSummary::with(['user', 'createdBy', 'updatedBy'])
            ->when($status, function($query) use ($status) {
                $query->where('status', $status);
            })
            ->when($search, function($query) use ($search) {
                $query->whereHas('user', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            })
            ->when($fromDate, function($query) use ($fromDate, $toDate) {
                $query->whereBetween('created_at', [
                    $fromDate . ' 00:00:00',
                    $toDate . ' 23:59:59'
                ]);
            })
            ->when($role && $role->role_id !== 1, function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->latest()
            ->get();

        $approvers = User::whereHas('roles', function($query) {
            $query->where('role_id', 10);
        })->get();

        return Inertia::render('HRMS/Logs/UserCashSummary', [
            'summaries' => $summaries,
            'users' => $approvers,
            'role' => $role,
            'filters' => [
                'status' => $status,
                'search' => $search,
                'from_date' => $fromDate,
                'to_date' => $toDate,
            ],
        ]);
    }

    // public function CashIndex(Request $request)
    // {
        //     $status = $request->input('status', 'pending');
        //     $search = $request->input('search');
        //     $fromDate = $request->input('from_date');
        //     $toDate = $request->input('to_date', date('Y-m-d'));

        //     $summaries = UserCashSummary::with(['user', 'createdBy', 'updatedBy'])
        //         ->when($status, function($query) use ($status) {
        //             $query->where('status', $status);
        //         })
        //         ->when($search, function($query) use ($search) {
        //             $query->whereHas('user', function($q) use ($search) {
        //                 $q->where('name', 'like', "%{$search}%");
        //             });
        //         })
        //         ->when($fromDate, function($query) use ($fromDate, $toDate) {
        //             $query->whereBetween('created_at', [
        //                 $fromDate . ' 00:00:00',
        //                 $toDate . ' 23:59:59'
        //             ]);
        //         })
        //         ->latest()
        //         ->get();

        //     $approvers = User::whereHas('roles', function($query) {
        //         $query->where('role_id', 10);
        //     })->get();

        //     $user = Auth::user();
        //     $role = UserRole::where('user_id', $user->id)->where('role_id', 1)->first();

        //     return Inertia::render('HRMS/Logs/UserCashSummary', [
        //         'summaries' => $summaries,
        //         'users' => $approvers,
        //         'role' => $role,
        //         'filters' => [
        //             'status' => $status,
        //             'search' => $search,
        //             'from_date' => $fromDate,
        //             'to_date' => $toDate,
        //         ],
        //     ]);
    // }


    public function userDetails(Request $request)
    {
        $userId = $request->input('user_id');
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date', date('Y-m-d'));
        $search = $request->input('search');

        $user = User::findOrFail($userId);
        $authUser = Auth::user();
            $role = UserRole::where('user_id', $authUser->id)->first();
            if ($role && $role->role_id !== 1) {
                if ($authUser->id !== $user->id) {
                    return redirect()->route('user-cash-summaries.index');
                }
            }
        $appointments = $user->createdAppointments()
        ->when($fromDate, function($query) use ($fromDate, $toDate) {
            $query->whereBetween('appointment_date', [
                $fromDate . ' 00:00:00',
                $toDate . ' 23:59:59'
            ]);
        })
        ->when($search, function($query) use ($search) {
            $query->where(function($q) use ($search) {
                $q->where('patient_name', 'like', "%{$search}%")
                ->orWhere('id', $search)
                ->orWhereHas('patient', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('id', 'like', "%{$search}%");
                })
                ->orWhereHas('doctor', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('appointmentDetails.service', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            });
        })
        ->with(['doctor.education', 'patient', 'creator:id,name', 'appointmentDetails.service', 'careoff'])
        ->get();

        $admissions = $user->createdAdmissions()
        ->when($fromDate, function($query) use ($fromDate, $toDate) {
            $query->whereBetween('admission_date', [
                $fromDate . ' 00:00:00',
                $toDate . ' 23:59:59'
            ]);
        })
        ->when($search, function($query) use ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('id', $search)
                ->orWhereHas('patient', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('id', 'like', "%{$search}%");
                })
                ->orWhereHas('details.doctor', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('details.service', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            });
        })
        ->with(['patient', 'room', 'roomBed', 'creator:id,name', 'updator:id,name', 'details.service', 'details.doctor', 'department', 'room_type'])
        ->get();

        $updatedAdmissions = $user->updatedAdmissions()
        ->when($fromDate, function($query) use ($fromDate, $toDate) {
            $query->whereBetween('admission_date', [
                $fromDate . ' 00:00:00',
                $toDate . ' 23:59:59'
            ]);
        })
        ->when($search, function($query) use ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('id', $search)
                ->orWhereHas('patient', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('id', 'like', "%{$search}%");
                })
                ->orWhereHas('details.doctor', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('details.service', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            });
        })
        ->with(['patient', 'room', 'roomBed', 'creator:id,name', 'updator:id,name', 'details.service', 'details.doctor', 'department', 'room_type'])
        ->get();

        $allAdmissions = $admissions->merge($updatedAdmissions)->unique('id');

        $patientTests = $user->createdPatientTests()
            ->when($fromDate, function($query) use ($fromDate, $toDate) {
                $query->whereBetween('test_date', [
                    $fromDate . ' 00:00:00',
                    $toDate . ' 23:59:59'
                ]);
            })
            ->when($search, function($query) use ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('patient_name', 'like', "%{$search}%")
                    ->orWhere('id', $search)
                    ->orWhereHas('patient', function($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('id', 'like', "%{$search}%");
                    })
                    ->orWhereHas('patientTestDetails.testGeneralDetails.labTest', function($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('patientTestDetails.package', function($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('patientTestDetails.testGeneralDetails.subPackage', function($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
                });
            })
            ->with([
                'patientTestDetails.testGeneralDetails',
                'patientTestDetails.testGeneralDetails.testCategory',
                'patientTestDetails.testGeneralDetails.subPackage',
                'patientTestDetails.testGeneralDetails.labTest.testCategory',
                'patientTestDetails.testGeneralDetails.labTest.unit',
                'testWidalDetails',
                'testWidalDetails.widalTest',
                'testCrossMatchDetails',
                'doctor.education',
                'package.testCategory',
                'patientTestDetails.package.testCategory',
                'creator:id,name',
                'updator:id,name',
                'careoff:id,name'
            ])
            ->get();

        if(file_exists(base_path('config/donation.php'))) {
            $donorFunds = $user->createdDonorFunds()
                ->when($fromDate, function($query) use ($fromDate, $toDate) {
                    $query->whereBetween('donor_date', [
                        $fromDate . ' 00:00:00',
                        $toDate . ' 23:59:59'
                    ]);
                })
                ->with(['donor'])
                ->get();
            }

        if(file_exists(base_path('config/expense.php'))) {
        $expenses = $user->createdExpenses()
            ->when($fromDate, function($query) use ($fromDate, $toDate) {
                $query->whereBetween('date', [
                    $fromDate . ' 00:00:00',
                    $toDate . ' 23:59:59'
                ]);
            })
            ->get();
        }

        return Inertia::render('HRMS/Logs/UserCashDetails', [
            'user' => $user,
            'appointments' => $appointments,
            'admissions' => $allAdmissions,
            'patientTests' => $patientTests,
            'donorFunds' => $donorFunds ?? null,
            'expenses' => $expenses ?? null,
            'filters' => [
                'from_date' => $fromDate,
                'to_date' => $toDate,
                'search' => $search,
            ],
        ]);
    }
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'collected_amount' => [
                'required',
                'numeric',
                'min:0',
                function ($attribute, $value, $fail) use ($id) {
                    $summary = UserCashSummary::find($id);
                    if ($value > $summary->total_amount) {
                        $fail('Collected amount cannot exceed total amount');
                    }
                }
            ],
            'updated_by' => 'required|exists:users,id',
        ]);

        $summary = UserCashSummary::findOrFail($id);
        $remainingAmount = $summary->total_amount - $validated['collected_amount'];

        \DB::transaction(function () use ($summary, $validated, $remainingAmount) {
            $summary->update([
                'collected_amount' => $validated['collected_amount'],
                'updated_by' => $validated['updated_by'],
                'created_by' => auth()->id(),
                'remaining_amount' => $remainingAmount,
                'status' => 'complete',
                'approved_by' => '1',
            ]);

            if ($remainingAmount) {
                UserCashSummary::create([
                    'user_id' => $summary->updated_by,
                    'total_amount' => $remainingAmount,
                    'collected_amount' => 0,
                    'remaining_amount' => $remainingAmount,
                    'opening_balance' => $remainingAmount,
                    'status' => 'pending',
                    'created_by' => auth()->id(),
                ]);
            }
        });

        return redirect()->back()->with('success', 'Cash summary updated successfully');
    }

}
