<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Rack;

class Warehouse extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'max_capacity'];

    public function racks()
    {
        return $this->hasMany(Rack::class);
    }
}
