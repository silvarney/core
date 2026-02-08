<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rate;
use App\Models\Season;
use App\Models\AccommodationType;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rates = Rate::with(['accommodationType.property', 'season'])
            ->latest()
            ->paginate(10);

        return Inertia::render('Rates/Index', [
            'rates' => $rates,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Rates/Create', [
            'seasons' => Season::where('active', true)->get(),
            'accommodationTypes' => AccommodationType::with('property')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'accommodation_type_id' => 'required|exists:accommodation_types,id',
            'season_id' => 'required|exists:seasons,id',
            'price' => 'required|numeric|min:0',
            'min_nights' => 'required|integer|min:1',
        ]);

        Rate::create($validated);

        return redirect()->route('rates.index')->with('success', 'Tarifa criada com sucesso.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Rate $rate)
    {
        return Inertia::render('Rates/Edit', [
            'rate' => $rate,
            'seasons' => Season::where('active', true)->get(),
            'accommodationTypes' => AccommodationType::with('property')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Rate $rate)
    {
        $validated = $request->validate([
            'accommodation_type_id' => 'required|exists:accommodation_types,id',
            'season_id' => 'required|exists:seasons,id',
            'price' => 'required|numeric|min:0',
            'min_nights' => 'required|integer|min:1',
        ]);

        $rate->update($validated);

        return redirect()->route('rates.index')->with('success', 'Tarifa atualizada com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rate $rate)
    {
        $rate->delete();

        return redirect()->route('rates.index')->with('success', 'Tarifa excluída com sucesso.');
    }
}
