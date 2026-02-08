<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Accommodation;
use App\Models\AccommodationType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;

class AccommodationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $accommodations = Accommodation::with('type.property')
            ->latest()
            ->paginate(10);

        return Inertia::render('Accommodations/Index', [
            'accommodations' => $accommodations,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $cacheKey = 'accommodation_types_with_property';

        return Inertia::render('Accommodations/Create', [
            'accommodationTypes' => Cache::remember($cacheKey, 3600, function () {
                return AccommodationType::with('property')->get();
            }),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'accommodation_type_id' => 'required|exists:accommodation_types,id',
            'name' => 'required|string|max:255',
            'status' => 'required|string|in:available,occupied,maintenance,cleaning',
            // Descrição e Comodidades
            'double_bed' => 'nullable|integer|min:0',
            'single_bed' => 'nullable|integer|min:0',
            'air_conditioning' => 'nullable|integer|min:0',
            'bathroom' => 'nullable|integer|min:0',
            'tv' => 'nullable|integer|min:0',
            'refrigerator' => 'nullable|integer|min:0',
            'cooktop' => 'nullable|integer|min:0',
            'microwave' => 'nullable|integer|min:0',
            'coffee_maker' => 'nullable|integer|min:0',
            'grill' => 'nullable|integer|min:0',
            // Novos campos
            'pool' => 'nullable|integer|min:0',
            'mini_pool' => 'nullable|integer|min:0',
            'hydromassage' => 'nullable|integer|min:0',
            'fireplace' => 'nullable|integer|min:0',
            'mezzanine' => 'nullable|integer|min:0',
            'wine_cellar' => 'nullable|integer|min:0',
            'wifi' => 'nullable|integer|min:0',
            'closet' => 'nullable|integer|min:0',
            'breakfast_included' => 'nullable|integer|min:0',
            'bed_types' => 'nullable|array',
        ]);

        Accommodation::create($validated);

        // Clear cache when new accommodation is created
        Cache::forget('accommodation_types_with_property');

        return redirect()->route('accommodations.index')->with('success', 'Acomodação criada com sucesso.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Accommodation $accommodation)
    {
        $cacheKey = 'accommodation_types_with_property';

        return Inertia::render('Accommodations/Edit', [
            'accommodation' => $accommodation,
            'accommodationTypes' => Cache::remember($cacheKey, 3600, function () {
                return AccommodationType::with('property')->get();
            }),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Accommodation $accommodation)
    {
        $validated = $request->validate([
            'accommodation_type_id' => 'required|exists:accommodation_types,id',
            'name' => 'required|string|max:255',
            'status' => 'required|string|in:available,occupied,maintenance,cleaning',
            // Descrição e Comodidades
            'double_bed' => 'nullable|integer|min:0',
            'single_bed' => 'nullable|integer|min:0',
            'air_conditioning' => 'nullable|integer|min:0',
            'bathroom' => 'nullable|integer|min:0',
            'tv' => 'nullable|integer|min:0',
            'refrigerator' => 'nullable|integer|min:0',
            'cooktop' => 'nullable|integer|min:0',
            'microwave' => 'nullable|integer|min:0',
            'coffee_maker' => 'nullable|integer|min:0',
            'grill' => 'nullable|integer|min:0',
            // Novos campos
            'pool' => 'nullable|integer|min:0',
            'mini_pool' => 'nullable|integer|min:0',
            'hydromassage' => 'nullable|integer|min:0',
            'fireplace' => 'nullable|integer|min:0',
            'mezzanine' => 'nullable|integer|min:0',
            'wine_cellar' => 'nullable|integer|min:0',
            'wifi' => 'nullable|integer|min:0',
            'closet' => 'nullable|integer|min:0',
            'breakfast_included' => 'nullable|integer|min:0',
            'bed_types' => 'nullable|array',
        ]);

        $accommodation->update($validated);

        // Clear cache when accommodation is updated
        Cache::forget('accommodation_types_with_property');

        return redirect()->route('accommodations.index')->with('success', 'Acomodação atualizada com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Accommodation $accommodation)
    {
        $accommodation->delete();

        // Clear cache when accommodation is deleted
        Cache::forget('accommodation_types_with_property');

        return redirect()->route('accommodations.index')->with('success', 'Acomodação excluída com sucesso.');
    }
}
