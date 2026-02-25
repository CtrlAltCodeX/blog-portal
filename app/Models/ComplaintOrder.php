<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComplaintOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'complaint_id',
        'order_id',
        'ref_no',
        'tracking_id',
        'cx_name',
        'cx_phone',
        'loss_value'
    ];

    public function complaint()
    {
        return $this->belongsTo(Complaint::class, 'complaint_id');
    }
}
