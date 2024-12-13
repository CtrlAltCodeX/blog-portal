<?php

namespace App\Http\Controllers;

use App\Imports\BulkUploadListingsImport;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class BulkUploadController extends Controller
{
    public function getOptions()
    {
        return view('bulk-upload.options');
    }

    public function import()
    {
        request()->validate([
            'csv_file' => 'required|mimes:csv,txt|max:2048',
        ]);

        // Load the data using the Import class
        $import = new BulkUploadListingsImport();

        Excel::import($import, request()->file('csv_file'));

        // Get the imported data
        $googlePosts = $import->getData();

        return view('bulk-upload.listing', compact('googlePosts'));
    }

    public function downloadImage()
    {
        $response = Http::withOptions(['verify' => false])
            ->get('https://m.media-amazon.com/images/I/7144vU5aPZL._AC_SL1500_.jpg');

        if ($response->successful()) {
            $filename = basename('asda.jpg'); // Get the filename from the URL
            $path = 'images/' . $filename; // Define the storage path
            Storage::disk('public')->put($path, $response->body());

            return response()->json(['message' => 'Image downloaded successfully', 'path' => $path]);
        }

        return response()->json(['message' => 'Failed to download the image'], 400);
    }

    public function importData()
    {
        foreach (request()->ids as $data) {
            dump(json_decode($data));
        }
        die;
    }
}
