<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Accommodation extends Model
{
    use HasFactory, HasUuids;

    public function uniqueIds(): array
    {
        return ['uuid'];
    }

    protected $fillable = [
        'accommodation_type_id',
        'name',
        'status',
        // Descrição e Comodidades
        'double_bed',
        'single_bed',
        'air_conditioning',
        'bathroom',
        'tv',
        'refrigerator',
        'cooktop',
        'microwave',
        'coffee_maker',
        'grill',
        // Novos campos
        'pool',
        'mini_pool',
        'hydromassage',
        'fireplace',
        'mezzanine',
        'wine_cellar',
        'wifi',
        'closet',
        'breakfast_included',
        'bed_types',
    ];

    public function type()
    {
        return $this->belongsTo(AccommodationType::class, 'accommodation_type_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Get the property through the accommodation type.
     */
    public function property()
    {
        return $this->hasOneThrough(Property::class, AccommodationType::class, 'id', 'id', 'accommodation_type_id', 'property_id');
    }

    protected $casts = [
        'double_bed' => 'integer',
        'single_bed' => 'integer',
        'air_conditioning' => 'integer',
        'bathroom' => 'integer',
        'tv' => 'integer',
        'refrigerator' => 'integer',
        'cooktop' => 'integer',
        'microwave' => 'integer',
        'coffee_maker' => 'integer',
        'grill' => 'integer',
        // Novos campos
        'pool' => 'integer',
        'mini_pool' => 'integer',
        'hydromassage' => 'integer',
        'fireplace' => 'integer',
        'mezzanine' => 'integer',
        'wine_cellar' => 'integer',
        'wifi' => 'integer',
        'closet' => 'integer',
        'breakfast_included' => 'integer',
        'bed_types' => 'array',
    ];
}
