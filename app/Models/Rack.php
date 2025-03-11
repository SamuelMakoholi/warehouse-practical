<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Line;
use App\Models\Pallet;

class Rack extends Model
{
    use HasFactory;

    protected $fillable = [
        'serial_number',
        'capacity',
        'line_id'
    ];

    public function line(): BelongsTo
    {
        return $this->belongsTo(Line::class);
    }

    public function pallets(): HasMany
    {
        return $this->hasMany(Pallet::class);
    }
}
