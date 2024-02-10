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
        $this->middleware('role_or_permission:Listing (Main Menu)|Listing create|Listing edit|Inventory -> Manage Inventory|Inventory (Main Menu)|Listing delete', ['only' => ['index', 'show']]);
        $this->middleware('role_or_permission:Listing create|Inventory -> Manage Inventory', ['only' => ['create', 'store']]);
        $this->middleware('role_or_permission:Listing edit', ['only' => ['edit', 'update']]);
        $this->middleware('role_or_permission:Listing delete|Inventory -> Manage Inventory', ['only' => ['destroy']]);
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
        if ($this->tokenIsExpired($this->googleService)) {
            $url = $this->googleService->refreshToken($this->googleService->getCredentails()->toArray());
            request()->session()->put('page_url', request()->url());

            return redirect()->to($url);
        }

        $post = $this->googleService->editPost($postId);
        $labels = $post->getLabels();

        $images = [];
        $doc = new \DOMDocument();
        $doc->loadHTML('<meta http-equiv="Content-Type" content="text/html; charset=utf-8">' . $post->content);

        $td = $doc->getElementsByTagName('td');
        $div = $doc->getElementsByTagName('div');

        $a = $doc->getElementsByTagName('a');
        $img = $doc->getElementsByTagName('img');
        $span = $doc->getElementsByTagName('span');

        // $sku = $td->item(3)->textContent ?? '';

        // $publication = $td->item(5)->textContent ?? '';

        $edition_author_lang = explode(',', $td->item(7)->textContent ?? '');
        $author_name = $edition_author_lang[0];
        $edition = $edition_author_lang[1] ?? '';
        $lang = $edition_author_lang[2] ?? '';

        $bindingType = explode(',', $td->item(9)->textContent ?? '');
        $binding = $bindingType[0] ?? '';
        $condition = $bindingType[1] ?? '';

        $page_no = $td->item(11)->textContent ?? '';

        $sku = '';
        $publication = '';
        for ($i = 0; $i < $td->length; $i++) {
            if ($td->item($i)->getAttribute('itemprop') == 'sku') {
                $sku = trim($td->item($i)->textContent);
            }

            if ($td->item($i)->getAttribute('itemprop') == 'color') {
                $publication = trim($td->item($i)->textContent);
            }
        }

        $desc = [];
        for ($i = 0; $i < $div->length; $i++) {
            if ($div->item($i)->getAttribute('class') == 'pbl box dtmoredetail dt_content') {
                $desc[] = trim($div->item($i)->textContent);
            }
        }

        $selling = 0;
        $mrp = 0;
        for ($i = 0; $i < $td->length; $i++) {
            if ($td->item($i)->getAttribute('class') == 'tr-caption') {
                $price = explode('-', trim($td->item($i)->textContent));

                $selling = $price[0];
                $mrp = $price[1];
            }
        }

        $instaUrl = "";
        for ($i = 0; $i < $a->length; $i++) {
            $item = trim($a->item($i)->textContent);
            if ($item == 'BUY AT INSTAMOJO') {
                $instaUrl = $a->item($i)->getAttribute('href');
            }
        }

        $baseimg = $img->item(0)?->getAttribute('src');

        for ($i = 0; $i < $doc->getElementsByTagName("img")->length; $i++) {
            $image = $doc->getElementsByTagName("img")->item($i);
            $images[] = $image->getAttribute('src');
        }
        
        $allInfo = [
            'sku' => trim($sku),
            'publication' => trim($publication),
            'edition' => trim($edition),
            'lang' => trim($lang),
            'author_name' => trim($author_name),
            'page_no' => trim($page_no),
            'desc' => $desc[0]??'',
            'selling' => trim($selling),
            'mrp' => trim($mrp),
            'multiple' => $images,
            'multipleImages' => json_encode($images),
            'baseimg' => $baseimg,
            'url' => trim($instaUrl),
            'binding' => trim($binding),
            'condition' => trim($condition),
        ];
        // dd($allInfo);

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

        return view('listing.live-inventory', compact('googlePosts'));
    }

    /**
     * Inventory Listing
     *
     * @return void
     */
    public function draftedInventory()
    {
        if ($this->tokenIsExpired($this->googleService)) {
            $url = $this->googleService->refreshToken($this->googleService->getCredentails()->toArray());
            request()->session()->put('page_url', request()->url());

            return redirect()->to($url);
        }

        $googlePosts = $this->googleService->posts('draft');

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

    /**
     * Publish Blog
     *
     * @return void
     */
    public function publishBlog($postId)
    {
        $this->googleService->publish($postId);

        session()->flash('success', 'Post Published successfully');

        return redirect()->back();
    }
}
