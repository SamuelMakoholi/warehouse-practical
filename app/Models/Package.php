<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Pallet;
use App\Models\QualityMark;

class Package extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'serial_number',
        'type',
        'mass',
        'barcode',
        'pallet_id',
        'quality_mark_id',
        'is_discarded'
    ];

    protected $casts = [
        'is_discarded' => 'boolean',
        'mass' => 'decimal:2'
    ];

    public function pallet(): BelongsTo
    {
        return $this->belongsTo(Pallet::class);
    }

    public function qualityMark(): BelongsTo
    {
        return $this->belongsTo(QualityMark::class);
    }
}
