<?php

namespace App\Imports;

use App\Models\BookSupplierRate;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BookSupplierRateImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new BookSupplierRate([
            'book_title'        => $row['books_title_name'] ?? null,
            'publisher_name'    => $row['publisher_name'] ?? null,

            'supplier_1_rate'   => $row['supplier_1_rate'] ?? null,
            'supplier_2_rate'   => $row['supplier_2_rate'] ?? null,
            'supplier_3_rate'   => $row['supplier_3_rate'] ?? null,
            'supplier_4_rate'   => $row['supplier_4_rate'] ?? null,
            'supplier_5_rate'   => $row['supplier_5_rate'] ?? null,
            'supplier_6_rate'   => $row['supplier_6_rate'] ?? null,
            'supplier_7_rate'   => $row['supplier_7_rate'] ?? null,
            'supplier_8_rate'   => $row['supplier_8_rate'] ?? null,
            'supplier_9_rate'   => $row['supplier_9_rate'] ?? null,
            'supplier_10_rate'  => $row['supplier_10_rate'] ?? null,
        ]);
    }
}
