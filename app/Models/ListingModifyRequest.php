<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListingModifyRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'category',
        'status',
        'requested_by',
        'updated_by'
    ];

    public function product()
    {
        return $this->belongsTo(BackupListing::class, 'product_id', 'product_id');
    }

    public function requestedBy()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
