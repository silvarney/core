<?php

namespace App\Services;

use App\Models\AccommodationType;
use App\Models\Season;
use App\Models\Rate;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class PricingService
{
    /**
     * Calculate the total price for a stay.
     */
    public function calculateTotalPrice($accommodationTypeId, $checkIn, $checkOut)
    {
        $period = CarbonPeriod::create($checkIn, Carbon::parse($checkOut)->subDay());
        $totalPrice = 0;

        foreach ($period as $date) {
            $price = $this->getPriceForDate($accommodationTypeId, $date);

            if ($price === null) {
                throw new \Exception("Nenhuma tarifa ou preço base encontrado para o tipo de acomodação na data: " . $date->format('d/m/Y'));
            }

            $totalPrice += $price;
        }

        return $totalPrice;
    }

    /**
     * Get the price for a specific date considering season priorities.
     * Fallback to base_price if no season rate is found.
     */
    public function getPriceForDate($accommodationTypeId, $date)
    {
        $dateStr = $date->toDateString();

        // 1. Find applicable seasons ordered by priority descending
        $seasonIds = Season::where('active', true)
            ->where('start_date', '<=', $dateStr)
            ->where('end_date', '>=', $dateStr)
            ->orderBy('priority', 'desc')
            ->pluck('id');

        foreach ($seasonIds as $seasonId) {
            $rate = Rate::where('accommodation_type_id', $accommodationTypeId)
                ->where('season_id', $seasonId)
                ->first();

            if ($rate) {
                return (float) $rate->price;
            }
        }

        // 2. Fallback to base_price from AccommodationType
        $type = AccommodationType::find($accommodationTypeId);

        return $type ? (float) $type->base_price : null;
    }
}
