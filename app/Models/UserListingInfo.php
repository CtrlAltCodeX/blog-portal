<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class UserListingInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'image',
        'title',
        'created_by',
        'approved_by',
        'approved_at',
        'status',
    ];

    public function create()
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }

    public function approve()
    {
        return $this->hasOne(User::class, 'id', 'approved_by');
    }
}
