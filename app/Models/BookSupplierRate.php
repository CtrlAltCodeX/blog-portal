<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookSupplierRate extends Model
{
    protected $table = 'books_supplier_rates';

    protected $fillable = [
        'book_title',
        'publisher_name',
        'supplier_1_rate',
        'supplier_2_rate',
        'supplier_3_rate',
        'supplier_4_rate',
        'supplier_5_rate',
        'supplier_6_rate',
        'supplier_7_rate',
        'supplier_8_rate',
        'supplier_9_rate',
        'supplier_10_rate',
    ];
}
