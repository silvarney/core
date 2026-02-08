<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Accommodation;
use App\Models\Booking;
use App\Models\User;
use App\Services\BookingService;
use App\Services\PricingService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BookingController extends Controller
{
    protected $bookingService;

    protected $pricingService;

    public function __construct(BookingService $bookingService, PricingService $pricingService)
    {
        $this->bookingService = $bookingService;
        $this->pricingService = $pricingService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Booking::with([
            'user',
            'accommodation.type.property',
            'services',
            'payments',
        ])
            ->select([
                'id', 'user_id', 'accommodation_id',
                'check_in', 'check_out',
                'guests_adults', 'guests_children',
                'total_price', 'status', 'payment_status',
                'created_at',
            ])
            ->latest();

        if ($request->search) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                    ->orWhere('email', 'like', '%'.$request->search.'%');
            });
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        return Inertia::render('Bookings/Index', [
            'bookings' => $query->paginate(10)->withQueryString(),
            'filters' => $request->only(['search', 'status']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Bookings/Create', [
            'accommodations' => Accommodation::with('type.property')->get(),
            'users' => User::all(['id', 'name', 'email']),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'accommodation_id' => 'required|exists:accommodations,id',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
            'guests_adults' => 'required|integer|min:1',
            'guests_children' => 'required|integer|min:0',
            'total_price' => 'required|numeric|min:0',
            'status' => 'required|string|in:pending,confirmed,cancelled,completed',
            'payment_status' => 'required|string|in:unpaid,partial,paid,refunded',
            'notes' => 'nullable|string',
        ]);

        // Validate Capacity
        $accommodation = Accommodation::with('type')->findOrFail($validated['accommodation_id']);
        if ($validated['guests_adults'] > $accommodation->type->capacity_adults) {
            return back()->withErrors(['guests_adults' => "A capacidade máxima de adultos para esta unidade é {$accommodation->type->capacity_adults}."])->withInput();
        }
        if ($validated['guests_children'] > $accommodation->type->capacity_children) {
            return back()->withErrors(['guests_children' => "A capacidade máxima de crianças para esta unidade é {$accommodation->type->capacity_children}."])->withInput();
        }

        try {
            $this->bookingService->createBooking($validated);

            return redirect()->route('bookings.index')->with('success', 'Reserva criada com sucesso.');
        } catch (\Exception $e) {
            return back()->withErrors(['accommodation_id' => $e->getMessage()])->withInput();
        }
    }

    /**
     * Calculate price for a given period and accommodation. (AJAX)
     */
    public function calculatePrice(Request $request)
    {
        $request->validate([
            'accommodation_id' => 'required|exists:accommodations,id',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
        ]);

        $accommodation = Accommodation::findOrFail($request->accommodation_id);

        try {
            $price = $this->pricingService->calculateTotalPrice(
                $accommodation->accommodation_type_id,
                $request->check_in,
                $request->check_out
            );

            return response()->json(['price' => $price]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Booking $booking)
    {
        $booking->load('user', 'accommodation.type.property');

        // Formata datas para YYYY-MM-DD para o input type="date"
        $bookingData = $booking->toArray();
        $bookingData['check_in'] = $booking->check_in->format('Y-m-d');
        $bookingData['check_out'] = $booking->check_out->format('Y-m-d');

        return Inertia::render('Bookings/Edit', [
            'booking' => $bookingData,
            'accommodations' => Accommodation::with('type.property')->get(),
            'users' => User::all(['id', 'name', 'email']),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'accommodation_id' => 'required|exists:accommodations,id',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
            'guests_adults' => 'required|integer|min:1',
            'guests_children' => 'required|integer|min:0',
            'total_price' => 'required|numeric|min:0',
            'status' => 'required|string|in:pending,confirmed,cancelled,completed',
            'payment_status' => 'required|string|in:unpaid,partial,paid,refunded',
            'notes' => 'nullable|string',
        ]);

        // Validate Capacity
        $accommodation = Accommodation::with('type')->findOrFail($validated['accommodation_id']);
        if ($validated['guests_adults'] > $accommodation->type->capacity_adults) {
            return back()->withErrors(['guests_adults' => "A capacidade máxima de adultos para esta unidade é {$accommodation->type->capacity_adults}."])->withInput();
        }
        if ($validated['guests_children'] > $accommodation->type->capacity_children) {
            return back()->withErrors(['guests_children' => "A capacidade máxima de crianças para esta unidade é {$accommodation->type->capacity_children}."])->withInput();
        }

        $booking->update($validated);

        return redirect()->route('bookings.index')->with('success', 'Reserva atualizada com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Booking $booking)
    {
        $booking->delete();

        return redirect()->route('bookings.index')->with('success', 'Reserva excluída com sucesso.');
    }
}
