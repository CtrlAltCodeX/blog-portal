<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComplaintUser extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'email', 'phone', 'otp', 'otp_expires_at'];

    public function complaints()
    {
        return $this->hasMany(Complaint::class, 'user_id');
    }
}
