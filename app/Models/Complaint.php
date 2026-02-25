<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    use HasFactory;

    protected $fillable = [
        'complaint_id',
        'user_id',
        'issue_type_id',
        'department_id',
        'title',
        'description',
        'delivery_timeline',
        'specific_tag',
        'employee_name',
        'employee_email',
        'employee_mobile',
        'send_mail',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(ComplaintUser::class, 'user_id');
    }

    public function orders()
    {
        return $this->hasMany(ComplaintOrder::class, 'complaint_id');
    }

    public function attachments()
    {
        return $this->hasMany(ComplaintAttachment::class, 'complaint_id');
    }

    public function issueType()
    {
        return $this->belongsTo(IssueType::class, 'issue_type_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
}
