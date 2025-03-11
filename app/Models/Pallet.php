<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Package;
use App\Models\Rack;

class Pallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'serial_number',
        'rack_id',
        'capacity',
        'quality_mark'
    ];

    public function rack(): BelongsTo
    {
        return $this->belongsTo(Rack::class);
    }

    public function packages(): HasMany
    {
        return $this->hasMany(Package::class);
    }
}
