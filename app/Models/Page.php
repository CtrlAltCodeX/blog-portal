<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    protected $fillable = [
        'batch_id',
        'user_id',
        'title',
        'description',
        'category_id',
        'sub_category_id',
        'sub_sub_category_id',
        'status'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
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
}
