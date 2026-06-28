<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index()
    {
        return response()->json(Room::all());
    }

    public function show(Room $room)
    {
        return response()->json($room);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_id' => 'required|string|unique:rooms,room_id',
            'name' => 'required|string|max:255',
            'section' => 'required|string|max:255',
            'type' => 'required|in:llamados,baños',
            'sede' => 'nullable|string|in:torre,urgencias',
        ]);

        if (!isset($validated['sede'])) {
            $validated['sede'] = 'torre';
        }

        $room = Room::create($validated);

        return response()->json($room, 201);
    }

    public function update(Request $request, Room $room)
    {
        $validated = $request->validate([
            'room_id' => 'required|string|unique:rooms,room_id,' . $room->id,
            'name' => 'required|string|max:255',
            'section' => 'required|string|max:255',
            'type' => 'required|in:llamados,baños',
            'sede' => 'nullable|string|in:torre,urgencias',
        ]);

        $room->update($validated);

        return response()->json($room);
    }

    public function destroy(Room $room)
    {
        $room->roomStates()->delete();
        $room->historial()->delete();
        $room->delete();

        return response()->json(null, 204);
    }
}
