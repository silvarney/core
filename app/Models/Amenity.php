<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Amenity extends Model
{
    use HasFactory, HasUuids;

    public function uniqueIds(): array
    {
        return ['uuid'];
    }

    protected $fillable = [
        'name',
        'icon',
        'type',
    ];

    public function accommodationTypes()
    {
        return $this->belongsToMany(AccommodationType::class, 'accommodation_type_amenity');
    }
}
