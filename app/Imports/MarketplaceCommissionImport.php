<?php
namespace App\Imports;

use App\Models\MarketplaceCommission;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MarketplaceCommissionImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new MarketplaceCommission([
            'min_range'      => $row['min_range'],
            'max_range'      => $row['max_range'],
            'min_commission' => $row['min_commission'],
            'max_commission' => $row['max_commission'],
        ]);
    }
}
