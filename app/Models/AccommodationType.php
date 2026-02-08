<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class AccommodationType extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'property_id',
        'name',
        'description',
        'capacity_adults',
        'capacity_children',
        'size_m2',
        'base_price',
    ];

    public function uniqueIds(): array
    {
        return ['uuid'];
    }

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function accommodations()
    {
        return $this->hasMany(Accommodation::class);
    }

    public function amenities()
    {
        return $this->belongsToMany(Amenity::class, 'accommodation_type_amenity');
    }

    public function rates()
    {
        return $this->hasMany(Rate::class);
    }
}
