<?php

namespace App\Exports;

use App\Models\BackupListing;
use App\Models\BackupListingImage;
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
            'link',
            'title',
            'id',
            'price',
            // 'clicks',
            // 'unpaid clicks',
            'condition',
            'availability',
            // 'Free listings - disapproved or invalid',
            // 'channel',
            // 'feed label',
            // 'language',
            'additional_image_link',
            'brand',
            // 'channel',
            // 'clicks',
            'description',
            // 'feed label',
            // 'free listings - disapproved or invalid',
            'identifier_exists',
            'image_link',
            // 'language',
            // 'pause',
            // 'shipping(country)',
            // 'unpaid clicks',
            // 'update type',
            // 'adult',
        ];

        // Add data rows
        foreach ($listings as $key => $listing) {
            if($key == 0){
                $title = str_replace(['"', '(', ')', ',', '&', '|'], ['', '', '', '', '', ''], $listing['title']);
                
                $additionalImages = BackupListingImage::where('listing_id', $listing->id)
                    ->pluck('image_url')
                    ->toArray();
                    
                if ($listing['publisher']) {
                    if ((string) $listing['publisher']) {
                        $publisher  = str_replace('-', "", $listing['publisher']);
                    } else {
                        $publisher = 'Exam360';
                    }
                } else {
                    $publisher = 'Exam360';
                }
    
                $data[] = [
                    $listing['url'],
                    $title,
                    $listing['id'],
                    ($listing['selling_price'] != 0) ? $listing['selling_price'] . "INR" : 1299 . "INR",
                    '0',
                    '0',
                    'new',
                    'in_stock',
                    'IN',
                    'Online',
                    'IN',
                    'en',
                    implode(",", $additionalImages),
                    $publisher,
                    'Online',
                     '0',
                    'Product Description',
                    '',
                    '',
                    'yes',
                    $listing['base_url'],
                    'en',
                    '',
                    'IN',
                    '0',
                    '',
                    'no',
                ];
                
            }
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
