<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Line;

class Warehouse extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'max_capacity'];

    public function lines(): HasMany
    {
        return $this->hasMany(Line::class);
    }
}
