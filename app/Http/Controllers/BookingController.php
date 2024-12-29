<?php

namespace App\Http\Controllers;

use App\Enums\BookingStatusEnum;
use App\Http\Requests\BookingRequest;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use App\Models\Slot;
use App\Traits\ResponseTrait;

class BookingController extends Controller
{
    use ResponseTrait;
    public function index()
    {
        $slots = auth('api')->user()->bookings->paginate(10);
        return self::successResponsePaginate(data: BookingResource::collection($slots)->response()->getData(true));
    }
    public function store(BookingRequest $request)
    {
        $request->validate([
            'slot_id' => 'required|exists:slots,id',
        ]);

        $slot = Slot::findOrFail($request->slot_id);

        if (!$slot->is_available) {
            return response()->json(['error' => 'Slot is not available'], 400);
        }

        $booking = Booking::create([
            'user_id' => auth('api')->id(),
            'slot_id' => $slot->id,
            'status' => BookingStatusEnum::pending->value,
        ]);

        $slot->update(['is_available' => false]);

        return response()->json($booking, 201);
    }

    public function destroy(Booking $booking)
    {
        $booking->where('user_id', auth()->id())->firstOrFail();
        $booking->update(['status' => BookingStatusEnum::cancelled->value]);
        $booking->slot?->update(['is_available' => true]);

        return response()->json(['message' => 'Booking cancelled successfully']);
    }
}
