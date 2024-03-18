<?php

namespace App\Exports;

use App\Models\BackupListing;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromArray;

class ListingsExport implements FromArray, WithHeadings, WithCustomCsvSettings
{
    use Exportable;

    public function array(): array
    {
        // Fetch your data here, for example:
        $listings = BackupListing::all();

        $data = [];

        // Add headings
        $data[] = [
            'title',
            'id',
            'price',
            'clicks',
            'unpaid clicks',
            'condition',
            'availability',
            'Free listings - disapproved or invalid',
            'channel',
            'feed label',
            'language',
            'brand',
            'description',
            'identifier exists',
            'image link',
            'pause',
            'shipping(country)',
        ];

        // Add data rows
        foreach ($listings as $listing) {
            $data[] = [
                $listing['title'],
                $listing['product_id'],
                $listing['selling_price'],
                '0',
                '0',
                $listing['condition'],
                'In Stock',
                '',
                '',
                '',
                'en',
                $listing['publisher'],
                $listing['description'],
                '',
                $listing['base_url'],
                '',
                'IN',
            ];
        }

        return $data;
    }

    public function headings(): array
    {
        return [];
    }

    public function getCsvSettings(): array
    {
        return [
            'delimiter' => "\t",
        ];
    }
}
