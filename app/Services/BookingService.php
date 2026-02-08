<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Accommodation;
use Exception;
use Illuminate\Support\Facades\DB;

class BookingService
{
    protected $availabilityService;
    protected $pricingService;

    public function __construct(AvailabilityService $availabilityService, PricingService $pricingService)
    {
        $this->availabilityService = $availabilityService;
        $this->pricingService = $pricingService;
    }

    /**
     * Create a new booking with automatic price calculation and availability check.
     */
    public function createBooking(array $data)
    {
        return DB::transaction(function () use ($data) {
            $accommodation = Accommodation::findOrFail($data['accommodation_id']);

            // 1. Check Availability
            if (!$this->availabilityService->isUnitAvailable($accommodation->id, $data['check_in'], $data['check_out'])) {
                throw new Exception("A unidade selecionada não está disponível para este período.");
            }

            // 2. Calculate Price (if not explicitly provided or to validate)
            $calculatedPrice = $this->pricingService->calculateTotalPrice($accommodation->accommodation_type_id, $data['check_in'], $data['check_out']);

            // We use the calculated price unless the user explicitly provided one (for manual overrides in CRM)
            $data['total_price'] = $data['total_price'] ?? $calculatedPrice;

            return Booking::create($data);
        });
    }

    /**
     * Cancel a booking.
     */
    public function cancelBooking($bookingId)
    {
        $booking = Booking::findOrFail($bookingId);
        $booking->update(['status' => 'Cancelled']);
        return $booking;
    }

    /**
     * Confirm a booking.
     */
    public function confirmBooking($bookingId)
    {
        $booking = Booking::findOrFail($bookingId);
        $booking->update(['status' => 'Confirmed']);
        return $booking;
    }
}
