<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Listing extends Model
{
    use HasFactory;

    protected $casts = [
        'categories'  => 'array',
        'images' => 'array',
        'multiple_images' => 'array',
    ];

    protected $fillable = [
        'title',
        'description',
        'mrp',
        'selling_price',
        'publisher',
        'author_name',
        'edition',
        'categories',
        'sku',
        'language',
        'no_of_pages',
        'condition',
        'binding_type',
        'insta_mojo_url',
        'images',
        'multiple_images',
        'base_url',
        'status',
        'created_by',
        'job_id',
        'error',
        'product_id',
    ];

    public function created_by_user()
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }
}
