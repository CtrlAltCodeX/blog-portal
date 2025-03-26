<?php

namespace App\Http\Controllers;

use App\Imports\BulkUploadListingsImport;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Jobs\ImportListingCsv;
use App\Models\Listing;
use App\Models\SiteSetting;
use App\Models\UserListingInfo;
use App\Models\WeightVSCourier;

class BulkUploadController extends Controller
{
    public function __construct()
    {
        $this->middleware('role_or_permission:Product Listing > Bulk Listing Upload', ['only' => ['getOptions']]);
        $this->middleware('role_or_permission:Product Listing > Bulk Listing Review (Edit)', ['only' => ['viewUploadedFile']]);
    }

    public function getOptions()
    {
        return view('bulk-upload.options');
    }

    public function import()
    {
        request()->validate([
            'csv_file' => 'required|max:2048',
        ]);

        // Load the data using the Import class
        $import = new BulkUploadListingsImport();

        Excel::import($import, request()->file('csv_file'));

        // Get the imported data
        $googlePosts = $import->getData();

        return view('bulk-upload.listing', compact('googlePosts'));
    }

    public function importData(Request $request)
    {
        if (!empty($request->ids)) {
            foreach ($request->ids as $key => $data) {
                $data = json_decode($data);

                $postId = isset($data->p_id) ? sprintf("%.0f", $data->p_id) : null;

                ImportListingCsv::dispatch($data, auth()->user()->id, $postId, $key);
            }
        } else {
            session()->flash('error', 'Please upload file first');

            return redirect()->route('view.upload');
        }
        
        session()->flash('success', 'Listing imported successfully');
        
        return redirect()->route('upload-file.options');
    }

    public function viewUploadedFile()
    {
        if (request()->type == 1) {
            $listings = Listing::where('is_bulk_upload', 1)
                ->whereNull('product_id')
                ->get();
        } else if (request()->type == 2) {
            $listings = Listing::where('is_bulk_upload', 1)
                ->whereNotNull('product_id')
                ->get();
        }

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

        $publications  = WeightVSCourier::all();

        return view('database-listing.edit', compact('listing', 'siteSetting', 'categories','publications'));
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

    public function delete(Request $request)
    {
        if ($request->formData[0]['value'] == 'Select') {
            session()->flash('error', 'Please choose the option to perform');
        }

        if (!empty($request->formData[1])) {
            Listing::whereIn('id', $request->formData[1])
                ->delete();

            return true;
        } else {
            session()->flash('error', 'Something went wrong');
        }
    }
}
