<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComplaintReplyAttachment extends Model
{
    use HasFactory;

    protected $fillable = ['complaint_reply_id', 'file_path'];

    public function reply()
    {
        return $this->belongsTo(ComplaintReply::class, 'complaint_reply_id');
    }
}
