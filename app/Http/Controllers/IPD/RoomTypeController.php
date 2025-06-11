<?php

namespace App\Http\Controllers\IPD;

use Inertia\Inertia;
use App\Models\UserRole;
use App\Models\IPD\RoomType;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\IPD\RoomTypeRequest;

class RoomTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkPermission:room-types.index')->only('index');
        $this->middleware('checkPermission:room-types.create')->only('create', 'store');
        $this->middleware('checkPermission:room-types.show')->only('show');
        $this->middleware('checkPermission:room-types.edit')->only('edit', 'update');
        $this->middleware('checkPermission:room-types.destroy')->only('destroy');
    }
    public function index()
    {
        $room_types = RoomType::orderBy('created_at', 'desc')->paginate(100);
        $user = Auth::user();
        $role = UserRole::where('user_id', $user->id)->where('role_id', 1)->first();
        return Inertia::render('IPD/RoomTypes/Index', [
            'room_types' => $room_types,
            'role' => $role,
        ]);
    }

    public function create()
    {
        return Inertia::render('IPD/RoomTypes/Create');
    }

    public function store(RoomTypeRequest $request)
    {
        DB::beginTransaction();
        $room_type = new RoomType();
        $formData = $request->only($room_type->getFillable());

        RoomType::create($formData);

        DB::commit();

        return redirect()->route('room-types.index');
    }


    public function edit(RoomType $room_type)
    {
        return Inertia::render('IPD/RoomTypes/Create', [
            'room_type' => $room_type,
        ]);
    }


    public function update(RoomTypeRequest $request, RoomType $room_type)
    {
        DB::beginTransaction();

        $formData = $request->only($room_type->getFillable());
        $room_type->update($formData);

        DB::commit();

        return redirect()->route('room-types.index')->with('message', 'Room type updated successfully.');
    }


    public function destroy($id)
    {
        $room_type = RoomType::findOrFail($id);
        $room_type->delete();

        return redirect()->route('room-types.index')->with('success', 'Room type deleted successfully.');
    }
}
