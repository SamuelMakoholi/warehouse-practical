<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Rack;
use App\Models\Pallet;

class Line extends Model
{
    use HasFactory;

    public function rack()
    {
        return $this->belongsTo(Rack::class);
    }

    public function pallets()
    {
        return $this->hasMany(Pallet::class);
    }
}
