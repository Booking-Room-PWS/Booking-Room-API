<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Helpers\ApiFormatter;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\JWTException;

class RoomController extends Controller
{
    protected function checkAuth()
    {
        if (! JWTAuth::getToken()) {
            return ApiFormatter::createJson(401, 'A token is required');
        }

        try {
            $user = JWTAuth::parseToken()->authenticate();
            if (! $user) {
                return ApiFormatter::createJson(404, 'User not found');
            }
            return null;
        } catch (TokenExpiredException $e) {
            return ApiFormatter::createJson(401, 'Token expired');
        } catch (TokenInvalidException $e) {
            return ApiFormatter::createJson(401, 'Token invalid');
        } catch (JWTException $e) {
            return ApiFormatter::createJson(401, 'Token error or absent');
        }
    }

    public function index()
    {
        if ($resp = $this->checkAuth()) return $resp;

        $rooms = Room::where('is_active', true)->paginate(10);
        // ->toArray() returns same pagination structure as Laravel's JSON response
        return ApiFormatter::createJson(200, 'OK', $rooms->toArray());
    }

    public function show(Room $room)
    {
        if ($resp = $this->checkAuth()) return $resp;

        // return attributes only (same format as before)
        return ApiFormatter::createJson(200, 'OK', $room->toArray());
    }

    public function store(Request $request)
    {
        if ($resp = $this->checkAuth()) return $resp;

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'is_active' => 'sometimes|boolean',
        ]);

        $room = Room::create($data);

        // return attributes only (not raw object)
        return ApiFormatter::createJson(201, 'Room created', $room->toArray());
    }

    public function update(Request $request, Room $room)
    {
        if ($resp = $this->checkAuth()) return $resp;

        $rules = [
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'is_active' => 'sometimes|boolean',
        ];

        $messages = [
            'name.required' => 'Name is required.',
            'location.required' => 'Location is required.',
            'capacity.required' => 'Capacity is required.',
            'capacity.integer' => 'Capacity must be an integer.',
            'capacity.min' => 'Capacity must be at least 1.',
        ];

        $data = $request->validate($rules, $messages);

        $room->update($data);

        return ApiFormatter::createJson(200, 'Room updated', $room->fresh()->toArray());
    }

    public function patch(Request $request, Room $room)
    {
        if ($resp = $this->checkAuth()) return $resp;

        $rules = [
            'name' => 'sometimes|string|max:255',
            'location' => 'sometimes|string|max:255',
            'capacity' => 'sometimes|integer|min:1',
            'is_active' => 'sometimes|boolean',
        ];

        $messages = [
            'capacity.integer' => 'Capacity must be an integer.',
            'capacity.min' => 'Capacity must be at least 1.',
        ];

        $data = $request->validate($rules, $messages);

        if (empty($data)) {
            throw ValidationException::withMessages([
                'data' => ['At least one of name, location, capacity, or is_active must be provided.']
            ]);
        }

        $room->update($data);

        return ApiFormatter::createJson(200, 'Room updated', $room->fresh()->toArray());
    }

    public function destroy(Room $room)
    {
        if ($resp = $this->checkAuth()) return $resp;

        $room->delete();

        return ApiFormatter::createJson(200, 'Room successfully deleted', null);
    }
}
