<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GoogleService;
use App\Http\Requests\BlogRequest;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Http;
use Illuminate\Pagination\LengthAwarePaginator;

class ListingController extends Controller
{
    /**
     * Create a new controller instance.
     * 
     * @return void
     */
    public function __construct(protected GoogleService $googleService)
    {
        $this->middleware('role_or_permission:Listing access|Listing create|Listing edit|Inventory edit|Inventory delete|Inventory access|Listing delete', ['only' => ['index', 'show']]);
        $this->middleware('role_or_permission:Listing create|Inventory create', ['only' => ['create', 'store']]);
        $this->middleware('role_or_permission:Listing edit|Inventory edit', ['only' => ['edit', 'update']]);
        $this->middleware('role_or_permission:Listing delete|Inventory delete', ['only' => ['destroy']]);
    }

    /**
     * Display the listing of the resources
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        if ($this->tokenIsExpired($this->googleService))
            return view('settings.authenticate');

        $googlePosts = $this->getPaginatedData(collect($this->googleService->posts()));

        return view('listing.index', compact('googlePosts'));
    }

    /**
     * Show the create page of list page
     * 
     * @return \Illuminate\View\View
     */
    public function create()
    {
        if (!$url = $this->getSiteBaseUrl()) {
            session()->flash('message', 'Please complete your Site Setting Then Continue');

            return view('settings.error');
        }

        $response = Http::get($url . '/feeds/posts/default?alt=json');

        $categories = $response->json()['feed']['category'];

        return view('listing.create', compact('categories'));
    }

    /**
     * Store the resources
     * 
     * @param \Illuminate\Http\Request
     */
    public function store(BlogRequest $request)
    {
        $result = $this->googleService->createPost($request->all());

        if ($message = $result?->error?->message) {
            session()->flash('error', $message);

            return redirect()->route('inventory.index');
        }

        session()->flash('success', 'Post created successfully');

        return redirect()->route('inventory.index');
    }

    /**
     * Return the edit of post
     * 
     * @return \Illumindate\View\View
     */
    public function edit($postId)
    {
        $post = $this->googleService->editPost($postId);
        $labels = $post->getLabels();
        
        $images = [];
        $doc = new \DOMDocument();
        $doc->loadHTML($post->content);
        $td = $doc->getElementsByTagName('td');
        $div = $doc->getElementsByTagName('div');
        $a = $doc->getElementsByTagName('a');
        $img = $doc->getElementsByTagName('img');
        $span = $doc->getElementsByTagName('span');

        $sku = $td->item(3)->textContent ?? '';
        $publication = $td->item(5)->textContent ?? '';
        $edition_author = explode(',', $td->item(7)->textContent ?? '');
        $author_name = $edition_author[0];
        $edition = $edition_author[1];
        $lang = $edition_author[2]??'';
        $bindingType = $td->item(9)->textContent ?? '';
        $page_no = $td->item(11)->textContent ?? '';
        $author = $div->item(9)->textContent ?? '';
        $desc = $span->item(1)->textContent ?? '';
        $search_key = $span->item(2)->textContent ?? '';
        $price = explode('-', $td->item(1)->textContent ?? '');
        $selling = $price[0];
        $mrp = $price[1];
        $instaUrl = $a->item(1)->getAttribute('href') ?? "";
        $baseimg = $img->item(0)?->getAttribute('src');

        for ($i = 0; $i < $doc->getElementsByTagName("img")->length; $i++) {
            $image = $doc->getElementsByTagName("img")->item($i);
            $images[] = $image->getAttribute('src');
        }

        $allInfo = [
            'sku' => $sku,
            'publication' => $publication,
            'author' => $author,
            'author_name' => $author_name,
            'page_no' => $page_no,
            'weight' => $weight ?? 0,
            'search_key' => $search_key,
            'desc' => $desc,
            'edition' => $edition,
            'selling' => $selling,
            'mrp' => $mrp,
            'multiple' => $images,
            'baseimg' => $baseimg,
            'url' => $instaUrl,
            'bindingtype' => $bindingType,
            'lang' => $lang
        ];

        if (!$url = $this->getSiteBaseUrl()) {
            session()->flash('message', 'Please complete your Site Setting Then Continue');

            return view('settings.error');
        }

        $response = Http::get($url . '/feeds/posts/default?alt=json');

        $categories = $response->json()['feed']['category'];

        return view('listing.edit', compact('post', 'categories', 'allInfo', 'labels'));
    }

    /**
     * Return the edit of post
     * 
     * @return \Illumindate\View\View
     */
    public function update(Request $request, $postId)
    {
        $this->googleService->updatePost($request->all(), $postId);

        session()->flash('success', 'Post updated successfully');

        return redirect()->route('inventory.index');
    }

    /**
     * Delete Posts
     *
     * @param int $postId
     * @return \Illumindate\View\View
     */
    public function destroy($postId)
    {
        $this->googleService->deletePost($postId);

        session()->flash('success', 'Post delete successfully');

        return redirect()->route('inventory.index');
    }

    /**
     * Inventory Listing
     *
     * @return void
     */
    public function inventory()
    {
        if ($this->tokenIsExpired($this->googleService)) {
            $url = $this->googleService->refreshToken($this->googleService->getCredentails()->toArray());
            request()->session()->put('page_url', request()->url());

            return redirect()->to($url);
        }

        $googlePosts = $this->googleService->posts();

        return view('listing.inventory', compact('googlePosts'));
    }

    /**
     * Get Paginated Data
     *
     * @param Object $googlePosts
     * @param integer $perPage
     * @return Object
     */
    public function getPaginatedData($googlePosts, $perPage = 10)
    {
        $currentPage = request()->get('page') ?: 1;

        $currentPageItems = $googlePosts->slice(($currentPage - 1) * $perPage, $perPage)->values();

        $googlePosts = new LengthAwarePaginator(
            $currentPageItems,
            $googlePosts->count(),
            $perPage,
            $currentPage,
            ['path' => LengthAwarePaginator::resolveCurrentPath()]
        );

        return $googlePosts;
    }
}
