<?php
namespace App\Imports;

use App\Models\CityCost;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CityCostImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new CityCost([
            'city_name'       => $row['city_name'],
            'cost_percentage' => $row['cost_percentage'],
        ]);
    }
}
