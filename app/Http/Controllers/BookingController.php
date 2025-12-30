<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class BookingController extends Controller
{
    public function index()
    {
        $user = auth('api')->user();

        if ($user && $user->is_admin) {
            return Booking::with(['room', 'user'])->paginate(15);
        }

        return $user->bookings()->with('room')->paginate(15);
    }

    public function show(Booking $booking)
    {
        return $booking->load(['room', 'user']);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'start_at' => 'required|date|before:end_at',
            'end_at' => 'required|date|after:start_at',
            'purpose' => 'required|string|max:255',
        ]);

        $room = Room::findOrFail($data['room_id']);
        if (!$room->is_active) {
            throw ValidationException::withMessages(['room_id' => ['Room is not active']]);
        }

        $conflict = Booking::where('room_id', $data['room_id'])
            ->whereIn('status', ['pending', 'approved'])
            ->where(function ($q) use ($data) {
                $q->whereBetween('start_at', [$data['start_at'], $data['end_at']])
                  ->orWhereBetween('end_at', [$data['start_at'], $data['end_at']])
                  ->orWhere(function ($qq) use ($data) {
                      $qq->where('start_at', '<=', $data['start_at'])
                         ->where('end_at', '>=', $data['end_at']);
                  });
            })->exists();

        if ($conflict) {
            throw ValidationException::withMessages(['time' => ['Waktu booking bentrok']]);
        }

        $booking = Booking::create([
            'room_id' => $data['room_id'],
            'user_id' => auth('api')->id(),
            'start_at' => $data['start_at'],
            'end_at' => $data['end_at'],
            'purpose' => $data['purpose'],
            'status' => 'pending',
        ]);

        return response()->json($booking, 201);
    }

    public function cancel(Booking $booking)
    {
        // user boleh cancel sendiri; asumsi owner nya sudah di-handle di frontend/backend check
        $booking->status = 'cancelled';
        $booking->save();
        return response()->json($booking);
    }

    public function approve(Booking $booking)
    {
        $booking->status = 'approved';
        $booking->save();
        return response()->json($booking);
    }

    public function reject(Booking $booking)
    {
        $booking->status = 'rejected';
        $booking->save();
        return response()->json($booking);
    }
}
