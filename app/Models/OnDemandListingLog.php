<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnDemandListingLog extends Model
{
    use HasFactory;

    protected $fillable = ['requested_by', 'count', 'category'];

    public function requestedBy()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }
}

