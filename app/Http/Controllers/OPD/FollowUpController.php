<?php

namespace App\Http\Controllers\OPD;

use App\Models\City;
use Inertia\Inertia;
use App\Models\State;
use App\Models\Country;
use App\Models\UserRole;
use App\Models\OPD\FollowUp;
use App\Models\OPD\Appointment;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\OPD\FollowUpRequest;

class FollowUpController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkPermission:follow-ups.index')->only('index');
        $this->middleware('checkPermission:follow-ups.create')->only('create', 'store');
        $this->middleware('checkPermission:follow-ups.show')->only('show');
        $this->middleware('checkPermission:follow-ups.edit')->only('edit', 'update');
        $this->middleware('checkPermission:follow-ups.destroy')->only('destroy');
    }
    public function index()
    {
        $user = Auth::user();
        $follow_ups = FollowUp::query()->orderBy('created_at', 'desc');

        if (isset($_GET['search']) && $_GET['search'] != '') {
            $searchTerm = $_GET['search'];
            $follow_ups->where(function($query) use ($searchTerm) {
                $query->where('id', $searchTerm)
                      ->orWhere('appointment_id', $searchTerm);
            });
        }
        $follow_ups = $follow_ups->paginate(100);
        $role = UserRole::where('user_id', $user->id)->where('role_id', 1)->first();
        return Inertia::render('OPD/FollowUps/Index', [
            'follow_ups' => $follow_ups,
            'role' => $role,
            'filters' => request()->only(['search']),
        ]);
    }


    public function create()
    {
        $appointments = Appointment::orderBy('id')->select('id')->get();
        return Inertia::render('OPD/FollowUps/Create', [
            'appointments' => $appointments,
        ]);
    }

    public function store(FollowUpRequest $request)
    {
        $follow_up = new FollowUp();
        $followUpData = $request->only($follow_up->getFillable());

        FollowUp::create($followUpData);

        return redirect()->route('follow-ups.index');
    }


    public function edit(FollowUp $follow_up)
    {
        $appointments = Appointment::orderBy('id')->select('id')->get();
        return Inertia::render('OPD/FollowUps/Create', [
            'follow_up' => $follow_up,
            'appointments' => $appointments,
        ]);
    }


    public function update(FollowUpRequest $request, FollowUp $follow_up)
    {
        $followUpData = $request->only($follow_up->getFillable());
        $follow_up->update($followUpData);

        return redirect()->route('follow-ups.index')->with('message', 'Follow up updated successfully.');
    }


    public function destroy($id)
    {
        $follow_up = FollowUp::findOrFail($id);
        $follow_up->delete();

        return redirect()->route('follow-ups.index')->with('success', 'Follow up deleted successfully.');
    }
}
