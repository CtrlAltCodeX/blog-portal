<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'url',
        'logo',
        'product_background_image',
        'homepage_image',
        'watermark_text',
        'calc_link'
    ];
}
