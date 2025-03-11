<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Warehouse;
use App\Models\Rack;

class Line extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'warehouse_id',
        'type',
        'max_allowed_capacity'
    ];

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function racks(): HasMany
    {
        return $this->hasMany(Rack::class);
    }
}
