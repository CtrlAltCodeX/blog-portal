<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CityCost extends Model
{
    use HasFactory;

    protected $fillable = ['city_name', 'cost_percentage'];
}
