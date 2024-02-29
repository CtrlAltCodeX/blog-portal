<?php

namespace App\Http\Controllers;

use App\Exports\BackupListingsExport;
use App\Mail\BackupMail;
use App\Models\BackupListing;
use Illuminate\Support\Facades\Mail;
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
    public function exportData()
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

        return $getAllListings;
    }

    /**
     * Download Excel
     *
     * @return void
     */
    public function downloadExcel()
    {
        return Excel::download(new BackupListingsExport($this->exportData()), 'report.xlsx');
    }
}
