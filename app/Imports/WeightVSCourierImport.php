<?php
namespace App\Imports;

use App\Models\WeightVSCourier;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class WeightVSCourierImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new WeightVSCourier([
            'pub_name' => $row['pub_name'],
            'book_type_1' => $row['book_type_1'],
            'book_discount_1' => $row['book_discount_1'],
            'book_type_2' => $row['book_type_2'],
            'book_discount_2' => $row['book_discount_2'],
            'book_type_3' => $row['book_type_3'],
            'book_discount_3' => $row['book_discount_3'],
            'book_type_4' => $row['book_type_4'],
            'book_discount_4' => $row['book_discount_4'],
            'book_type_5' => $row['book_type_5'],
            'book_discount_5' => $row['book_discount_5'],
            'book_type_6' => $row['book_type_6'],
            'book_discount_6' => $row['book_discount_6'],
            'location_dis' => $row['location_dis'],
        ]);
    }
}

