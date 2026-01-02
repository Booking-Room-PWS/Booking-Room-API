<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

use Illuminate\Validation\ValidationException;

class RoomController extends Controller
{
    public function index()
    {
        return Room::where('is_active', true)->paginate(10);
    }

    public function show(Room $room)
    {
        return $room;
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'is_active' => 'sometimes|boolean',
        ]);

        $room = Room::create($data);
        return response()->json($room, 201);
    }

    //* Room Updete: PUT
    public function update(Request $request, Room $room)
    {
        //* rules untuk full update (PUT)
        $rules = [
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'is_active' => 'sometimes|boolean',
        ];

        //* custom messages
        $messages = [
            'name.required' => 'Name is required.',
            'location.required' => 'Location is required.',
            'capacity.required' => 'Capacity is required.',
            'capacity.integer' => 'Capacity must be an integer.',
            'capacity.min' => 'Capacity must be at least 1.',
        ];

        if ($request->isMethod('put')) {
            $data = $request->validate($rules, $messages);
        } else {
            if (empty($data)) {
                throw ValidationException::withMessages([
                    'data' => ['At least one of name, location, capacity, or is_active must be provided']
                ]);
            }
        }

        $room->update($data);
        return response()->json($room->fresh());
        // return response()->json($room);
    }

    //* Room update sebagian: PATCH
    public function patch(Request $request, Room $room) {
        $rules = [
            'name' => 'sometimes|string|max:255',
            'location' => 'sometimes|string|max:255',
            'capacity' => 'sometimes|integer|min:1',
            'is_active' => 'sometimes|boolean',
        ];
        
        $data = $request->validate($rules);

        if (empty($data)) {
            throw ValidationException::withMessages([
                'data' => 'At least one of name, location, capacity, or is_active must be provided.'
            ]);
        }

        $room->update($data);
        return response()->json($room->fresh());
    }


    public function destroy(Room $room)
    {
        $room->delete();
        return response()->json(['message' => 'Room succesfully deleted']);
    }
}