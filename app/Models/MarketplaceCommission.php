<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketplaceCommission extends Model
{
    use HasFactory;

    protected $fillable = ['min_range', 'max_range', 'min_commission', 'max_commission'];
}
