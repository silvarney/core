<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Season;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SeasonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $seasons = Season::latest()
            ->paginate(10);

        return Inertia::render('Seasons/Index', [
            'seasons' => $seasons,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Seasons/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'priority' => 'required|integer|min:0',
            'active' => 'boolean',
        ]);

        Season::create($validated);

        return redirect()->route('seasons.index')->with('success', 'Temporada criada com sucesso.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Season $season)
    {
        return Inertia::render('Seasons/Edit', [
            'season' => $season,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Season $season)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'priority' => 'required|integer|min:0',
            'active' => 'boolean',
        ]);

        $season->update($validated);

        return redirect()->route('seasons.index')->with('success', 'Temporada atualizada com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Season $season)
    {
        $season->delete();

        return redirect()->route('seasons.index')->with('success', 'Temporada excluída com sucesso.');
    }
}
