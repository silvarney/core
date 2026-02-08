<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Accommodation;
use App\Services\BookingService;
use App\Services\PricingService;
use App\Services\AvailabilityService;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    protected $bookingService;
    protected $pricingService;
    protected $availabilityService;

    public function __construct(
        BookingService $bookingService,
        PricingService $pricingService,
        AvailabilityService $availabilityService
    ) {
        $this->bookingService = $bookingService;
        $this->pricingService = $pricingService;
        $this->availabilityService = $availabilityService;
    }

    /**
     * Calculate price and check availability for a search.
     */
    public function quote(Request $request)
    {
        $request->validate([
            'accommodation_type_id' => 'required|exists:accommodation_types,id',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
        ]);

        try {
            $availableUnits = $this->availabilityService->getAvailableUnits(
                $request->accommodation_type_id,
                $request->check_in,
                $request->check_out
            );

            if ($availableUnits->isEmpty()) {
                return response()->json(['message' => 'Nenhuma unidade disponível para este período.'], 404);
            }

            $price = $this->pricingService->calculateTotalPrice(
                $request->accommodation_type_id,
                $request->check_in,
                $request->check_out
            );

            return response()->json([
                'available' => true,
                'price' => $price,
                'guaranteed_unit' => $availableUnits->first()->id,
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    /**
     * Create a booking from the public site.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id', // In a real scenario, this would be the authenticated user or a new user creation
            'accommodation_id' => 'required|exists:accommodations,id',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
            'guests_adults' => 'required|integer|min:1',
            'guests_children' => 'required|integer|min:0',
            'notes' => 'nullable|string',
        ]);

        $validated['status'] = 'Pending';
        $validated['payment_status'] = 'Unpaid';

        try {
            $booking = $this->bookingService->createBooking($validated);
            return response()->json($booking, 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }
}
