<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserListingCount extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
        'approved_count',
        'reject_count',
    ];
}
