<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Property;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    /**
     * List all active properties.
     */
    public function index(Request $request)
    {
        return response()->json(
            Property::where('active', true)
                ->select(['id', 'name', 'description'])
                ->paginate(20)
        );
    }

    /**
     * Show details of a property, including its types and amenities.
     */
    public function show(Property $property)
    {
        return response()->json($property->load(['accommodationTypes.amenities']));
    }
}
