<?php

namespace App\Http\Controllers;

use App\Exports\BackupListingsExport;
use App\Models\BackupListing;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Excel as BaseExcel;

class BackupListingsController extends Controller
{
    /**
     * Initiate the class instance
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Index function
     *
     * @return \Illuminate\View\View
     */
    public function backupListings()
    {
        $getAllListings = BackupListing::all();

        return view('backup-listings.index', compact('getAllListings'));
    }

    /**
     * Export Table
     *
     * @return void
     */
    public function export()
    {
        $getAllListings = BackupListing::all()->makeHidden(['id', 'created_at', 'updated_at'])->toArray();

        $mainColums = [
            'Product id',
            'Title',
            'Description',
            'MRP',
            'Selling Price',
            'Publisher',
            'Author Name',
            'Edition',
            'Categories',
            'SKU',
            'language',
            'No of Pages',
            'Condition',
            'Binding Type',
            'Insta Mojo URL',
            'Base URL'
        ];

        array_unshift($getAllListings, $mainColums);

        $attachment = Excel::raw(
            new BackupListingsExport($getAllListings),
            BaseExcel::CSV
        );

        return Excel::download(new BackupListingsExport($getAllListings), 'report.xlsx');
    }
}
