<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Rate extends Model
{
    use HasFactory, HasUuids;

    public function uniqueIds(): array
    {
        return ['uuid'];
    }

    protected $fillable = [
        'accommodation_type_id',
        'season_id',
        'price',
        'min_nights',
    ];

    public function accommodationType()
    {
        return $this->belongsTo(AccommodationType::class);
    }

    public function season()
    {
        return $this->belongsTo(Season::class);
    }
}
