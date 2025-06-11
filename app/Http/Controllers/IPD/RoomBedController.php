<?php

namespace App\Http\Controllers\IPD;

use Inertia\Inertia;
use App\Models\IPD\Room;
use App\Models\UserRole;
use App\Models\IPD\RoomBed;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\IPD\RoomBedRequest;

class RoomBedController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkPermission:room-beds.index')->only('index');
        $this->middleware('checkPermission:room-beds.create')->only('create', 'store');
        $this->middleware('checkPermission:room-beds.show')->only('show');
        $this->middleware('checkPermission:room-beds.edit')->only('edit', 'update');
        $this->middleware('checkPermission:room-beds.destroy')->only('destroy');
    }
    public function index()
    {
        $room_beds = RoomBed::with('room:id,room_number')->orderBy('created_at', 'desc')->paginate(100);
        $user = Auth::user();
        $role = UserRole::where('user_id', $user->id)->where('role_id', 1)->first();
        return Inertia::render('IPD/RoomBeds/Index', [
            'room_beds' => $room_beds,
            'role' => $role,
        ]);
    }

    public function create()
    {
        $rooms = Room::select('id', 'room_number')->orderBy('room_number')->get();
        return Inertia::render('IPD/RoomBeds/Create', [
            'rooms' => $rooms
        ]);
    }

    public function store(RoomBedRequest $request)
    {
        DB::beginTransaction();

        $room_bed = new RoomBed();
        $formData = $request->only($room_bed->getFillable());

        RoomBed::create($formData);

        DB::commit();

        return redirect()->route('room-beds.index');
    }


    public function edit(RoomBed $room_bed)
    {
        $rooms = Room::select('id', 'room_number')->orderBy('room_number')->get();
        return Inertia::render('IPD/RoomBeds/Create', [
            'room_bed' => $room_bed,
            'rooms' => $rooms,
        ]);
    }


    public function update(RoomBedRequest $request, RoomBed $room_bed)
    {
        DB::beginTransaction();

        $formData = $request->only($room_bed->getFillable());
        $room_bed->update($formData);

        DB::commit();

        return redirect()->route('room-beds.index')->with('message', 'Room bed updated successfully.');
    }


    public function destroy($id)
    {
        $room_bed = RoomBed::findOrFail($id);
        $room_bed->delete();

        return redirect()->route('room-beds.index')->with('success', 'Room bed deleted successfully.');
    }
}
