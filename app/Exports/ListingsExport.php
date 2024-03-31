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
            'channel',
            'clicks',
            'description',
            'feed label',
            'free listings - disapproved or invalid',
            'identifier exists',
            'image link',
            'language',
            'pause',
            'shipping(country)',
            'unpaid clicks',
            'update type',
        ];

        // Add data rows
        foreach ($listings as $listing) {
            $data[] = [
                $listing['title'],
                $listing['product_id'],
                $listing['selling_price'] . "INR",
                '0',
                '0',
                strtolower($listing['condition']),
                'in stock',
                'IN',
                'Online',
                'IN',
                'en',
                $listing['publisher'],
                'Online',
                '0',
                $listing['description'],
                '',
                '',
                'yes',
                $listing['base_url'],
                'en',
                '',
                'IN',
                '0',
                '',
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
