<?php

namespace App\Imports;

use App\Models\Publication;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PublicationsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Publication([
            'publication_name' => $row['publication_name'], 
            'discount_information' => $row['discount_information'],
            'location' => $row['location'],
        ]);
    }
}

