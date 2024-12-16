<?php

namespace App\Http\Controllers;

use App\Imports\BulkUploadListingsImport;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Jobs\ImportListingCsv;
use App\Models\Listing;
use App\Models\SiteSetting;

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

    public function importData(Request $request)
    {
        if (!empty($request->ids)) {
            foreach ($request->ids as $key => $data) {
                $data = json_decode($data);

                $job = ImportListingCsv::dispatch($data, auth()->user()->id);

                if ($job == true) {
                    session()->flash('success', 'Listing imported successfully');
                    return redirect()->route('view.upload');
                } else {
                    session()->flash('error', 'Something went Wrong');
                    return redirect()->route('view.upload');
                }
            }
        } else {
            session()->flash('error', 'Please upload file first');
            return redirect()->route('view.upload');
        }
    }

    public function viewUploadedFile()
    {
        $listings = Listing::where('is_bulk_upload', 1)->get();
        return view('bulk-upload.view_uploaded', compact('listings'));
    }

    public function edit($id)
    {
        $listing = Listing::find($id);
        $siteSetting = SiteSetting::first();

        if (!$url = $this->getSiteBaseUrl()) {
            session()->flash('message', 'Please complete your Site Setting Then Continue');

            return view('settings.error');
        }

        $response = Http::withoutVerifying()
            ->get($url . '/feeds/posts/default?alt=json');

        $categories = $response->json()['feed']['category'];
        return view('bulk-upload.edit', compact('listing', 'siteSetting', 'categories'));
    }

    public function update(Request $request)
    {
        $data = [
            '_token' => $request->_token,
            'title' => $request->title,
            'description' => $request->description,
            'mrp' => $request->mrp,
            'selling_price' => $request->selling_price,
            'publisher' => $request->publication,
            'author_name' => $request->author_name,
            'edition' => $request->edition,
            'categories' => $request->label,
            'sku' => $request->sku,
            'language' => $request->language,
            'no_of_pages' => $request->pages,
            'condition' => $request->condition,
            'binding_type' => $request->binding,
            'insta_mojo_url' => $request->url,
            'images' => $request->images[0],
            'multiple_images' => $request->multipleImages,
            'created_by' => auth()->user()->id,
            'is_bulk_upload' => 0
        ];

        $listing = Listing::find($request->id);

        if ($request->status == 1) {
            $additionalInfo = UserListingInfo::where('image', $request->images[0])
                ->where('title', request()->title)
                ->first();

            $additionalInfo->update([
                'status' => request()->status
            ]);
        }

        if ($listing->update($data)) {
            session()->flash('success', 'Listing Updated successfully');

            return redirect()->back();
        }

        session()->flash('error', 'Someting went wrong');

        return redirect()->back();
    }
}
