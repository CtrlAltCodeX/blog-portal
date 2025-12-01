<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Content extends Model
{

    protected $table = 'content';


    protected $fillable = [
        'user_id',
        'batch_id',
        'category_id',
        'sub_category_id',
        'sub_sub_category_id',
        'title',
        'brief_description',
        'preferred_date',
        'attach_image',
        'attach_docs',
        'attach_url',
        // new fields
        'verified_by',
        'verified_date',
        'verified_time',
        'status',
        'rejection_cause',
        'worktype_id',
        'expected_amount',
        'content_report_note',
        'host_record_note',

    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function subCategory()
    {
        return $this->belongsTo(Category::class, 'sub_category_id');
    }

    public function subSubCategory()
    {
        return $this->belongsTo(Category::class, 'sub_sub_category_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function verifiedUser()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function workType()
    {
        return $this->belongsTo(WorkType::class, 'worktype_id');
    }
}
