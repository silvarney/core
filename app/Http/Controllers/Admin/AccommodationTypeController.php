<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AccommodationType;
use App\Models\Property;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AccommodationTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $accommodationTypes = AccommodationType::with('property')
            ->latest()
            ->paginate(10);

        return Inertia::render('AccommodationTypes/Index', [
            'accommodationTypes' => $accommodationTypes,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('AccommodationTypes/Create', [
            'properties' => Property::all(['id', 'name']),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'capacity_adults' => 'required|integer|min:0',
            'capacity_children' => 'required|integer|min:0',
            'size_m2' => 'nullable|numeric|min:0',
            'base_price' => 'required|numeric|min:0',
            'checkin_time' => 'required|date_format:H:i',
            'checkout_time' => 'required|date_format:H:i',
        ]);

        AccommodationType::create($validated);

        return redirect()->route('accommodation-types.index')->with('success', 'Tipo de acomodação criado com sucesso.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AccommodationType $accommodationType)
    {
        return Inertia::render('AccommodationTypes/Edit', [
            'accommodationType' => $accommodationType,
            'properties' => Property::all(['id', 'name']),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AccommodationType $accommodationType)
    {
        $validated = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'capacity_adults' => 'required|integer|min:0',
            'capacity_children' => 'required|integer|min:0',
            'size_m2' => 'nullable|numeric|min:0',
            'base_price' => 'required|numeric|min:0',
            'checkin_time' => 'required|date_format:H:i',
            'checkout_time' => 'required|date_format:H:i',
        ]);

        $accommodationType->update($validated);

        return redirect()->route('accommodation-types.index')->with('success', 'Tipo de acomodação atualizado com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AccommodationType $accommodationType)
    {
        $accommodationType->delete();

        return redirect()->route('accommodation-types.index')->with('success', 'Tipo de acomodação excluído com sucesso.');
    }
}
