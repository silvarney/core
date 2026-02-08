<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Accommodation;
use App\Models\Property;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InventoryController extends Controller
{
    /**
     * Display a listing of the physical units and their statuses.
     */
    public function index(Request $request)
    {
        $propertyId = $request->property_id;
        $month = (int) $request->input('month', now()->month);
        $year = (int) $request->input('year', now()->year);

        $selectedDate = now()->setYear($year)->setMonth($month)->startOfMonth();
        $startOfMonth = $selectedDate->copy()->startOfMonth();
        $endOfMonth = $selectedDate->copy()->endOfMonth();

        $query = Property::with([
            'accommodationTypes.accommodations.bookings' => function ($query) use ($startOfMonth, $endOfMonth) {
                $query->where('status', '!=', 'cancelled')
                    ->where(function ($q) use ($startOfMonth, $endOfMonth) {
                        $q->whereBetween('check_in', [$startOfMonth, $endOfMonth])
                            ->orWhereBetween('check_out', [$startOfMonth, $endOfMonth])
                            ->orWhere(function ($sub) use ($startOfMonth, $endOfMonth) {
                                $sub->where('check_in', '<=', $startOfMonth)
                                    ->where('check_out', '>=', $endOfMonth);
                            });
                    })
                    ->with('user:id,name')
                    ->select(['id', 'accommodation_id', 'user_id', 'check_in', 'check_out', 'status']);
            },
        ]);

        if ($propertyId) {
            $query->where('id', $propertyId);
        }

        $properties = $query->get();

        // Formata as datas para garantir consistência no frontend
        $properties->each(function ($property) {
            $property->accommodationTypes->each(function ($type) {
                $type->accommodations->each(function ($accommodation) {
                    $accommodation->bookings->each(function ($booking) {
                        $booking->check_in = $booking->check_in->format('Y-m-d');
                        $booking->check_out = $booking->check_out->format('Y-m-d');
                    });
                });
            });
        });

        if ($propertyId) {
            $query->where('id', $propertyId);
        }

        $properties = $query->get();

        // Calculate days for the selected month to send to frontend
        $daysInMonth = [];
        $current = $startOfMonth->copy();
        while ($current <= $endOfMonth) {
            $daysInMonth[] = [
                'date' => $current->toDateString(),
                'day' => $current->day,
                'is_weekend' => $current->isWeekend(),
            ];
            $current->addDay();
        }

        return Inertia::render('Inventory/Index', [
            'properties' => $properties,
            'allProperties' => Property::all(['id', 'name']),
            'filters' => [
                'property_id' => $propertyId,
                'month' => (int) $month,
                'year' => (int) $year,
            ],
            'calendar' => [
                'days' => $daysInMonth,
                'month_name' => $selectedDate->translatedFormat('F Y'),
            ],
        ]);
    }

    /**
     * Update the status of a specific accommodation.
     */
    public function updateStatus(Request $request, Accommodation $accommodation)
    {
        $validated = $request->validate([
            'status' => 'required|string|in:available,occupied,maintenance,cleaning',
        ]);

        $accommodation->update([
            'status' => $validated['status'],
        ]);

        return back()->with('success', 'Status da unidade atualizado com sucesso.');
    }
}
