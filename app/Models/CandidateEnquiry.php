<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CandidateEnquiry extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'age',
        'address',
        'experience',
        'previous_salary',
        'family_background',
        'father_occupation',
        'preference',
        'notes',
        'status',
        'user_id',
        'application_status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
