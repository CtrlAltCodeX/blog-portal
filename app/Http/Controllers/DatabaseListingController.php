<?php

namespace App\Http\Controllers;

use App\Http\Requests\BlogRequest;
use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Services\GoogleService;

class DatabaseListingController extends Controller
{
    public function __construct(protected GoogleService $googleService) {

    }

    /**
     * Display the listing of the resources
     */
    public function index() {
        $googlePosts = Listing::paginate(10);

        return view('database-listing.index', compact('googlePosts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!$url = $this->getSiteBaseUrl()) {
            session()->flash('message', 'Please complete your Site Setting Then Continue');

            return view('settings.error');
        }

        $response = Http::get($url . '/feeds/posts/default?alt=json');

        $categories = $response->json()['feed']['category'];

        return view('database-listing.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BlogRequest $request)
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
            'images' => $request->images,
            'multiple_images' => $request->multipleImages,
            'base_url' => $request->base_url,
            'status' => 0
        ];

        $listing = Listing::create($data);

        if ($listing) {
            session()->flash('success', 'Listing created successfully');

            return redirect()->back();
        }

        session()->flash('error', 'Someting went wrong');

        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $listing = Listing::find($id);

        return view('database-listing.edit', compact('listing'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BlogRequest $request, string $id)
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
            'images' => $request->images,
            'multiple_images' => $request->multipleImages,
            'base_url' => $request->base_url
        ];

        $listing = Listing::find($id);

        if ($listing->update($data)) {
            session()->flash('success', 'Listing Updated successfully');

            return redirect()->back();
        }

        session()->flash('error', 'Someting went wrong');

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $listing = Listing::find($id);

        if($listing->delete()) {
            session()->flash('success', 'Listing deleted succesfully.');

            return redirect()->back();
        }

        session()->flash('error', 'Someting went wrong');
        
        return redirect()->back();
    }
}
