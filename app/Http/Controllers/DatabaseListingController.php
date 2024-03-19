<?php

namespace App\Http\Controllers;

use App\Http\Requests\BlogRequest;
use App\Models\Listing;
use App\Models\UserListingCount;
use App\Models\UserListingInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Services\GoogleService;

class DatabaseListingController extends Controller
{
    /**
     * Constructor
     *
     * @param GoogleService $googleService
     */
    public function __construct(protected GoogleService $googleService)
    {
    }

    /**
     * Display the listing of the resources
     */
    public function index()
    {
        $googlePosts = Listing::with('created_by_user')
            ->orderBy('created_at', 'desc')
            ->where('status', request()->status)
            ->where('categories', 'LIKE', '%' . request()->category . '%');

        if (!auth()->user()->hasRole('Admin')) {
            $googlePosts = $googlePosts->where('created_by', auth()->user()->id);
        }

        $googlePosts = $googlePosts->paginate(150);

        $allCounts = Listing::count();

        $pendingCounts = Listing::where('status', 0)->count();

        $rejectedCounts = Listing::where('status', 2)->count();

        return view('database-listing.index', compact('googlePosts', 'allCounts', 'pendingCounts', 'rejectedCounts'));
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
        try {
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
                'status' => 0,
                'created_by' => auth()->user()->id
            ];

            $listing = Listing::create($data);

            UserListingInfo::create([
                'image' => $request->images[0],
                'title' => $request->title,
                'created_by' => auth()->user()->id,
                'approved_by' => null,
                'approved_at' => null,
                'status' => 0,
            ]);

            if ($listing) {
                session()->flash('success', 'Listing created successfully');

                return redirect()->back();
            }

            session()->flash('error', 'Someting went wrong');

            return redirect()->back();
        } catch (\Exception $e) {
            session()->flash('error', 'Something went Wrong!!');

            return redirect()->back();
        }
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
            'images' => $request->images[0],
            'multiple_images' => $request->multipleImages,
            'created_by' => auth()->user()->id
        ];

        $listing = Listing::find($id);

        if ($request->status == 2) {
            $count = UserListingCount::where('user_id', $request->created_by)
                ->where('date', $request->created_on)
                ->first();

            if ($count) {
                $count->update([
                    'reject_count' => $count->reject_count++,
                ]);
            } else {
                UserListingCount::create([
                    'user_id' => $request->created_by,
                    'date' => $request->created_on,
                    'approved_count' => 0,
                    'reject_count' => 1,
                ]);
            }

            $data['status'] = request()->status;

            $additionalInfo = UserListingInfo::where('image', $request->images[0])
                ->where('title', request()->title)
                ->first();

            $additionalInfo->update([
                'status' => request()->status
            ]);
        } else if ($request->status == 1) {
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $listing = Listing::find($id);

        if ($listing->delete()) {
            session()->flash('success', 'Listing deleted succesfully.');

            return redirect()->back();
        }

        session()->flash('error', 'Someting went wrong');

        return redirect()->back();
    }

    /**
     * Update Status of Listing
     *
     * @return void
     */
    public function updateStatus()
    {
        $listing = Listing::whereIn('id', request()->formData[1])
            ->get();

        $status = request()->formData[0]['value'];

        $listing->map(function ($list) use ($status) {
            $additionalInfo = UserListingInfo::where("image", $list->images)
                ->where('title', $list->title)
                ->first();

            $additionalInfo->update([
                'status' => $status
            ]);

            $list->update(['status' => $status]);
        });

        return true;
    }
}
