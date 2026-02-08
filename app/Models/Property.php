<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name',
        'description',
        'address',
        'email',
        'phone',
        'active',
        'site',
        'api_key',
    ];

    public function uniqueIds(): array
    {
        return ['uuid'];
    }

    public function accommodationTypes()
    {
        return $this->hasMany(AccommodationType::class);
    }
}
