<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnDemandListing extends Model
{
    use HasFactory;

    protected $fillable = [
        'requested_by',
        'image',
        'category',
        'status',
        'completed_by',
        'completed_at'
    ];

    public function requestedBy()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function completedBy()
    {
        return $this->belongsTo(User::class, 'completed_by');
    }
}
