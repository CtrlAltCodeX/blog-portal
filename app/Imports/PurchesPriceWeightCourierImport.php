<?php

namespace App\Imports;

use App\Models\PurchesPriceWeightCourier;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PurchesPriceWeightCourierImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {

        return new PurchesPriceWeightCourier([
            'min' => $row['min'],
            'max' => $row['max'],
            'weight' => $row['weight'],
            'courier_rate' => $row['courier_rate'],
            'packing_charge' => $row['packing_charge'],
            'our_min_profit' => $row['our_min_profit'],
            'max_profit' => $row['max_profit'],
        ]);
    }
}
