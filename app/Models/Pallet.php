<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Line;
use App\Models\Package;

class Pallet extends Model
{
    use HasFactory;

    public function line()
    {
        return $this->belongsTo(Line::class);
    }

    public function packages()
    {
        return $this->hasMany(Package::class);
    }
}
