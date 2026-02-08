<?php

namespace App\Services;

use App\Models\Accommodation;
use App\Models\Booking;
use Carbon\Carbon;

class AvailabilityService
{
    /**
     * Check if a specific unit is available for a date range.
     */
    public function isUnitAvailable($accommodationId, $checkIn, $checkOut)
    {
        $accommodation = Accommodation::find($accommodationId);

        if (!$accommodation || $accommodation->status !== 'available') {
            return false;
        }

        $conflicts = Booking::where('accommodation_id', $accommodationId)
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($checkIn, $checkOut) {
                $query->whereBetween('check_in', [$checkIn, Carbon::parse($checkOut)->subDay()])
                    ->orWhereBetween('check_out', [Carbon::parse($checkIn)->addDay(), $checkOut])
                    ->orWhere(function ($q) use ($checkIn, $checkOut) {
                        $q->where('check_in', '<=', $checkIn)
                            ->where('check_out', '>=', $checkOut);
                    });
            })
            ->exists();

        return !$conflicts;
    }

    /**
     * Get all available units of a certain type for a date range.
     */
    public function getAvailableUnits($accommodationTypeId, $checkIn, $checkOut)
    {
        return Accommodation::where('accommodation_type_id', $accommodationTypeId)
            ->where('status', 'available')
            ->whereDoesntHave('bookings', function ($query) use ($checkIn, $checkOut) {
                $query->where('status', '!=', 'cancelled')
                    ->where(function ($q) use ($checkIn, $checkOut) {
                        $q->whereBetween('check_in', [$checkIn, Carbon::parse($checkOut)->subDay()])
                            ->orWhereBetween('check_out', [Carbon::parse($checkIn)->addDay(), $checkOut])
                            ->orWhere(function ($iq) use ($checkIn, $checkOut) {
                                $iq->where('check_in', '<=', $checkIn)
                                    ->where('check_out', '>=', $checkOut);
                            });
                    });
            })
            ->get();
    }
}
