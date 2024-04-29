<?php

namespace App\Http\Controllers;

use App\Http\Requests\BlogRequest;
use App\Jobs\PublishProducts;
use App\Models\Listing;
use App\Models\SiteSetting;
use App\Models\User;
use App\Models\UserListingCount;
use App\Models\UserListingInfo;
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
            ->where('categories', 'LIKE', '%' . request()->category . '%');

        if (request()->user != 'all') {
            $googlePosts = $googlePosts->where('created_by', request()->user);
        }

        if (request()->status) $googlePosts = $googlePosts->where('status', request()->status);

        if (!auth()->user()->hasRole('Super Admin')) {
            $googlePosts = $googlePosts->where('created_by', auth()->user()->id);
        }

        $googlePosts = $googlePosts->paginate(150);

        $allCounts = Listing::count();

        $pendingCounts = Listing::where('status', 0);

        $rejectedCounts = Listing::where('status', 2);

        if (!auth()->user()->hasRole('Super Admin')) {
            $pendingCounts = $pendingCounts->where('created_by', auth()->user()->id);

            $rejectedCounts = $rejectedCounts->where('created_by', auth()->user()->id);
        }

        $pendingCounts = $pendingCounts->count();

        $rejectedCounts = $rejectedCounts->count();

        $users = User::where('status', 1)->get();

        return view('database-listing.index', compact('googlePosts', 'allCounts', 'pendingCounts', 'rejectedCounts', 'users'));
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

        $siteSetting = SiteSetting::first();

        return view('database-listing.create', compact('categories', 'siteSetting'));
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

        $siteSetting = SiteSetting::first();

        if (!$url = $this->getSiteBaseUrl()) {
            session()->flash('message', 'Please complete your Site Setting Then Continue');

            return view('settings.error');
        }

        $response = Http::get($url . '/feeds/posts/default?alt=json');

        $categories = $response->json()['feed']['category'];

        return view('database-listing.edit', compact('listing', 'siteSetting', 'categories'));
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
                    'reject_count' => ++$count->reject_count,
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
        if (request()->publish == 3) {
            foreach (request()->ids as $id) {
                PublishProducts::dispatch($id);

                Listing::find($id)->delete();

                $additionalInfo = UserListingInfo::find($id);

                $additionalInfo->update([
                    'status' => 1,
                    'approved_by' => auth()->user()->id,
                    'approved_at' => now()
                ]);
            }
        } else {
            $listings = Listing::whereIn('id', request()->formData[1])
                ->get();

            $status = request()->formData[0]['value'];

            foreach ($listings as $listing) {
                $userCount = UserListingCount::where('user_id', $listing->created_by)
                    ->whereDate('date', $listing->created_at)
                    ->first();

                if ($status == 2 && !$userCount) {
                    UserListingCount::create([
                        'user_id' => $listing->created_by,
                        'date' => $listing->created_at,
                        'approved_count' => 0,
                        'reject_count' => 1,
                    ]);
                } else if ($status == 2 && $userCount) {
                    $userCount->update([
                        'reject_count' => ++$userCount->reject_count,
                    ]);
                }

                if ($status == 0 && $userCount) {
                    $userCount->update([
                        'reject_count' => --$userCount->reject_count,
                    ]);
                }
            }

            $listings->map(function ($list) use ($status) {
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
}
