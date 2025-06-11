<?php

namespace App\Http\Controllers\IPD;

use Inertia\Inertia;
use App\Models\IPD\Room;
use App\Models\UserRole;
use App\Models\IPD\RoomType;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\IPD\RoomRequest;

class RoomController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkPermission:rooms.index')->only('index');
        $this->middleware('checkPermission:rooms.create')->only('create', 'store');
        $this->middleware('checkPermission:rooms.show')->only('show');
        $this->middleware('checkPermission:rooms.edit')->only('edit', 'update');
        $this->middleware('checkPermission:rooms.destroy')->only('destroy');
    }
    public function index()
    {
        $rooms = Room::with('roomType:id,name')->orderBy('created_at', 'desc')->paginate(100);
        $user = Auth::user();
        $role = UserRole::where('user_id', $user->id)->where('role_id', 1)->first();
        return Inertia::render('IPD/Rooms/Index', [
            'rooms' => $rooms,
            'role' => $role,
        ]);
    }

    public function create()
    {
        $room_types = RoomType::select('id', 'name')->orderBy('name')->get();
        return Inertia::render('IPD/Rooms/Create', [
            'room_types' => $room_types
        ]);
    }

    public function store(RoomRequest $request)
    {
        DB::beginTransaction();

        $room = new Room();
        $formData = $request->only($room->getFillable());

        Room::create($formData);

        DB::commit();

        return redirect()->route('rooms.index');
    }


    public function edit(Room $room)
    {
        $room_types = RoomType::select('id', 'name')->orderBy('name')->get();
        return Inertia::render('IPD/Rooms/Create', [
            'room' => $room,
            'room_types' => $room_types,
        ]);
    }


    public function update(RoomRequest $request, Room $room)
    {
        DB::beginTransaction();

        $formData = $request->only($room->getFillable());
        $room->update($formData);

        DB::commit();

        return redirect()->route('rooms.index')->with('message', 'Room updated successfully.');
    }


    public function destroy($id)
    {
        $room = Room::findOrFail($id);
        $room->delete();

        return redirect()->route('rooms.index')->with('success', 'Room deleted successfully.');
    }
}
