<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContentCreate extends Model
{
    protected $fillable = [
         'user_id',
        'batch_id',
        'category',
        'sub_category',
        'sub_sub_category',
        'title',
        'brief_description',
        'preferred_date',
        'attach_image',
        'attach_docs',
        'attach_url',

    ];

    public function categoryRelation()
{
    return $this->belongsTo(Category::class, 'category');
}

public function subCategoryRelation()
{
    return $this->belongsTo(Category::class, 'sub_category');
}

public function subSubCategoryRelation()
{
    return $this->belongsTo(Category::class, 'sub_sub_category');
}

public function creator()
{
    return $this->belongsTo(User::class, 'user_id');
}

}
