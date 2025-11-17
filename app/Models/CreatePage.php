<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class CreatePage extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'sub_category_id',
        'any_preferred_date',
        'date',
        'upload',
        'url',
        'batch_id',
        'status',
        'official_remark',
        'remarks_user_id',
        'remarks_date',
        'sub_sub_category_id',
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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getComputedStatusAttribute()
    {
        $created = Carbon::parse($this->created_at);
        $diffDays = $created->diffInDays(now());

        $status = ucfirst($this->status);

        if ($this->status === 'pending') {
            if ($diffDays >= 7) {
                $status = 'No Actions Taken (Auto Rejected)';
            } elseif ($diffDays >= 3) {
                $status = 'Last Day Action';
            } else {
                $status = 'Pending';
            }
        }

        return $status;
    }
}
