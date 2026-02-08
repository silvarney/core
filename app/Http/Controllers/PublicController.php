<?php

namespace App\Http\Controllers;

use App\Models\AccommodationType;
use App\Models\Booking;
use App\Models\Property;
use App\Models\User;
use App\Services\AvailabilityService;
use App\Services\BookingService;
use App\Services\PricingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Inertia\Inertia;

class PublicController extends Controller
{
    protected $availabilityService;

    protected $pricingService;

    protected $bookingService;

    public function __construct(
        AvailabilityService $availabilityService,
        PricingService $pricingService,
        BookingService $bookingService
    ) {
        $this->availabilityService = $availabilityService;
        $this->pricingService = $pricingService;
        $this->bookingService = $bookingService;
    }

    public function index()
    {
        $properties = Property::all();

        return Inertia::render('Public/Home', [
            'properties' => $properties,
        ]);
    }

    public function property(Property $property)
    {
        return Inertia::render('Public/PropertyDetails', [
            'property' => $property->load('accommodationTypes.amenities'),
        ]);
    }

    public function search(Request $request)
    {
        $request->validate([
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
        ]);

        // Optimize: Only load types that have available accommodations
        $types = AccommodationType::with(['property', 'amenities'])
            ->whereHas('accommodations', function ($query) {
                $query->where('status', 'available');
            })
            ->get();

        $results = $types->map(function ($type) use ($request) {
            $availableUnits = $this->availabilityService->getAvailableUnits(
                $type->id,
                $request->check_in,
                $request->check_out
            );

            if ($availableUnits->isEmpty()) {
                return null;
            }

            $totalPrice = $this->pricingService->calculateTotalPrice(
                $type->id,
                $request->check_in,
                $request->check_out
            );

            return [
                'type' => $type,
                'available_count' => $availableUnits->count(),
                'total_price' => $totalPrice,
                'check_in' => $request->check_in,
                'check_out' => $request->check_out,
                'guaranteed_unit_id' => $availableUnits->first()->id,
            ];
        })->filter();

        return Inertia::render('Public/Search', [
            'results' => $results->values(),
            'filters' => $request->only(['check_in', 'check_out']),
        ]);
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'accommodation_type_id' => 'required|exists:accommodation_types,id',
            'check_in' => 'required|date',
            'check_out' => 'required|date',
        ]);

        $type = AccommodationType::with('property')->findOrFail($request->accommodation_type_id);

        $totalPrice = $this->pricingService->calculateTotalPrice(
            $type->id,
            $request->check_in,
            $request->check_out
        );

        return Inertia::render('Public/Checkout', [
            'type' => $type,
            'details' => [
                'check_in' => $request->check_in,
                'check_out' => $request->check_out,
                'total_price' => $totalPrice,
            ],
        ]);
    }

    public function storeBooking(Request $request)
    {
        $request->validate([
            'accommodation_type_id' => 'required|exists:accommodation_types,id',
            'check_in' => 'required|date',
            'check_out' => 'required|date',
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'guests' => 'required|integer|min:1',
        ]);

        // 1. Find or Create User
        $user = User::firstOrCreate(
            ['email' => $request->email],
            [
                'name' => $request->name,
                'password' => Hash::make(Str::random(12)),
            ]
        );

        // 2. Find an available unit
        $availableUnits = $this->availabilityService->getAvailableUnits(
            $request->accommodation_type_id,
            $request->check_in,
            $request->check_out
        );

        if ($availableUnits->isEmpty()) {
            return back()->withErrors(['error' => 'Infelizmente esta unidade não está mais disponível para as datas selecionadas.']);
        }

        $unit = $availableUnits->first();

        // 3. Create Booking
        try {
            $booking = $this->bookingService->createBooking([
                'user_id' => $user->id,
                'accommodation_id' => $unit->id,
                'check_in' => $request->check_in,
                'check_out' => $request->check_out,
                'guests' => $request->guests,
                'status' => 'Pending',
                'payment_status' => 'Unpaid',
            ]);

            return redirect()->route('home')->with('success', 'Reserva realizada com sucesso! Verifique seu e-mail.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
