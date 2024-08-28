<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Guest;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index()
    {
        return Room::with('guest')->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'floor_number' => 'required|integer',
            'room_number' => 'required|integer',
            'capacity' => 'required|integer',
            'status' => 'required|in:READY,TAKEN,MAINTENANCE',
        ]);

        $room = Room::create($request->all());
        return response()->json($room, 201);
    }

    public function show(Room $room)
    {
        return $room;
    }

    public function update(Request $request, Room $room)
    {
        $request->validate([
            'floor_number' => 'sometimes|required|integer',
            'room_number' => 'sometimes|required|integer',
            'capacity' => 'sometimes|required|integer',
            'status' => 'sometimes|required|in:READY,TAKEN,MAINTENANCE',
        ]);

        $room->update($request->all());
        return response()->json($room);
    }

    public function destroy(Room $room)
    {
        $room->delete();
        return response()->json(['message' => 'Room deleted successfully']);
    }

    public function assignRoomToGuest(Request $request, Room $room, $guestId)
    {
        $guest = Guest::find($guestId);

        if (!$guest) {
            return response()->json(['message' => 'Guest not found'], 404);
        }

        $room->status = 'TAKEN';
        $room->guest_id = $guest->id;
        $room->save();

        return response()->json(['message' => 'Room assigned to guest', 'room' => $room]);
    }

    public function deassignRoomFromGuest(Room $room)
    {
        $room->status = 'MAINTENANCE';
        $room->guest_id = null;
        $room->save();

        return response()->json(['message' => 'Room de-assigned from guest', 'room' => $room]);
    }

    public function setRoomReady(Room $room)
    {
        $room->status = 'READY';
        $room->save();

        return response()->json(['message' => 'Room status set to READY', 'room' => $room]);
    }
}
