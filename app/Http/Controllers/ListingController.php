<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GoogleService;
use App\Http\Requests\BlogRequest;
use App\Models\Publication;
use App\Models\SiteSetting;
use App\Models\WeightVSCourier;
use App\Models\PurchesPriceWeightCourier;
use Illuminate\Support\Facades\Http;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Exports\InventoryReviewExport;
use Maatwebsite\Excel\Facades\Excel;


class ListingController extends Controller
{
    /**
     * Create a new controller instance.
     * 
     * @return void
     */
    public function __construct(protected GoogleService $googleService)
    {
        $this->middleware('role_or_permission:Listing (Main Menu)|Listing create|Inventory -> Manage Inventory -> Edit|Inventory -> Manage Inventory|Inventory (Main Menu)|Inventory -> Manage Inventory -> Delete', ['only' => ['index', 'show']]);
        $this->middleware('role_or_permission:Listing create|Inventory -> Manage Inventory', ['only' => ['create', 'store']]);
        $this->middleware('role_or_permission:Inventory -> Manage Inventory -> Edit', ['only' => ['edit', 'update']]);
        $this->middleware('role_or_permission:Inventory -> Manage Inventory -> Delete|Inventory -> Manage Inventory', ['only' => ['destroy']]);
        $this->middleware('role_or_permission:Listing -> Search Listing', ['only' => ['search']]);
        $this->middleware('role_or_permission:Inventory -> Manage Inventory', ['only' => ['inventory']]);
        $this->middleware('role_or_permission:Inventory -> Drafted Inventory', ['only' => ['draftedInventory']]);
        $this->middleware('role_or_permission:Inventory -> Under Review Inventory', ['only' => ['reviewInventory']]);
    }

    /**
     * Display the listing of the resources
     * 
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
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

        $response = Http::withoutVerifying()->get($url . '/feeds/posts/default?alt=json');

        $categories = $response->json()['feed']['category'];

        $siteSetting = SiteSetting::first();
        $publications  = WeightVSCourier::all();

        return view('listing.create', compact('categories', 'siteSetting','publications'));
    }

    public function getPriceRecords(Request $request)
    {
        $price = $request->query('price');
    
        if (!$price) {
            return response()->json(['error' => 'Price parameter is required'], 400);
        }
    
        // Ensure price is a valid number
        if (!is_numeric($price)) {
            return response()->json(['error' => 'Price must be a numeric value'], 400);
        }
    
        // Check if table and columns exist
        try {
            $records = PurchesPriceWeightCourier::where('min', '<=', $price)
                ->where('max', '>=', $price)
                ->get();
                
            return response()->json($records);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    /**
     * Store the resources
     * 
     * @param \Illuminate\Http\Request
     */
    public function store(BlogRequest $request)
    {
        $user = auth()->user()->id;

        if (isset(request()->created_by) && (request()->created_by != auth()->user()->id)) {
            $user = request()->created_by;
        }

        $result = $this->googleService->createPost($request->all(), null, $user, $user);

        if ($message = $result?->error?->message) {
            session()->flash('error', $message);

            return redirect()->route('inventory.index', ['startIndex' => 1, 'category' => 'Product']);
        }

        session()->flash('success', 'Post created successfully');

        if ($request->database) {
            return redirect()->route('database-listing.index', ['status' => 0, 'startIndex' => 1, 'category' => '', 'user' => 'all']);
        }

        return redirect()->route('listing.create', ['startIndex' => 1, 'category' => 'Product']);
    }

    /**
     * Return the edit of post
     * 
     * @return \Illumindate\View\View
     */
    public function edit($postId)
    {
        if (!$this->tokenIsExpired($this->googleService)) {
            $url = $this->googleService->refreshToken($this->googleService->getCredentails()->toArray());
            request()->session()->put('page_url', request()->url());

            return redirect()->to($url);
        }

        $post = $this->googleService->editPost($postId);
        $labels = $post->getLabels();

        $images = [];
        $doc = new \DOMDocument();
        @$doc->loadHTML('<meta http-equiv="Content-Type" content="text/html; charset=utf-8">' . $post->content);

        $td = $doc->getElementsByTagName('td');
        $div = $doc->getElementsByTagName('div');

        $a = $doc->getElementsByTagName('a');
        $img = $doc->getElementsByTagName('img');
        $span = $doc->getElementsByTagName('span');

        $edition_author_lang = explode(',', $td->item(7)->textContent ?? '');
        // $author_name = $edition_author_lang[0];
        // $edition = $edition_author_lang[1] ?? '';
        // $lang = $edition_author_lang[2] ?? '';

        $bindingType = explode(',', $td->item(9)->textContent ?? '');
        $binding = $bindingType[0] ?? '';
        $condition = $bindingType[1] ?? '';

        $page_no = $td->item(11)->textContent ?? '';

        $sku = '';
        $publication = '';
        $isbn10 = '';
        $isbn13 = '';
        $publishyear = '';
        $weight = '';
        $age = '';
        $origin = '';
        $genre = '';
        $manufacturer = '';
        $importer = '';
        $packer = '';
        $lang = '';
        $edition = '';
        $author_name = '';

        if (count($edition_author_lang) > 1) {
            $author_name = $edition_author_lang[0];
            $edition = $edition_author_lang[1] ?? '';
            $lang = $edition_author_lang[2] ?? '';
        }

        if (count($bindingType) > 1) {
            $binding = $bindingType[0] ?? '';
            $condition = $bindingType[1] ?? '';
        }

        for ($i = 0; $i < $td->length; $i++) {
            if ($td->item($i)->getAttribute('itemprop') == 'sku') {
                $sku = trim($td->item($i)->textContent);
            }

            if ($td->item($i)->getAttribute('itemprop') == 'color') {
                $publication = trim($td->item($i)->textContent);
            }

            if ($td->item($i)->getAttribute('itemprop') == 'isbn10') {
                $isbn10 = trim($td->item($i)->textContent);
            }

            if ($td->item($i)->getAttribute('itemprop') == 'isbn13') {
                $isbn13 = trim($td->item($i)->textContent);
            }

            if ($td->item($i)->getAttribute('itemprop') == 'publishyear') {
                $publishyear = trim($td->item($i)->textContent);
            }

            if ($td->item($i)->getAttribute('itemprop') == 'weight') {
                $weight = trim($td->item($i)->textContent);
            }

            if ($td->item($i)->getAttribute('itemprop') == 'age') {
                $age = trim($td->item($i)->textContent);
            }

            if ($td->item($i)->getAttribute('itemprop') == 'origin') {
                $origin = trim($td->item($i)->textContent);
            }

            if ($td->item($i)->getAttribute('itemprop') == 'genre') {
                $genre = trim($td->item($i)->textContent);
            }

            if ($td->item($i)->getAttribute('itemprop') == 'manufacturer') {
                $manufacturer = trim($td->item($i)->textContent);
            }

            if ($td->item($i)->getAttribute('itemprop') == 'importer') {
                $importer = trim($td->item($i)->textContent);
            }

            if ($td->item($i)->getAttribute('itemprop') == 'packer') {
                $packer = trim($td->item($i)->textContent);
            }

            if ($td->item($i)->getAttribute('itemprop') == 'language') {
                $lang = trim($td->item($i)->textContent);
            }

            if ($td->item($i)->getAttribute('itemprop') == 'edition') {
                $edition = trim($td->item($i)->textContent);
            }

            if ($td->item($i)->getAttribute('itemprop') == 'author') {
                $author_name = trim($td->item($i)->textContent);
            }

            if ($td->item($i)->getAttribute('itemprop') == 'condition') {
                $condition = trim($td->item($i)->textContent);
            }

            if ($td->item($i)->getAttribute('itemprop') == 'binding') {
                $binding = trim($td->item($i)->textContent);
            }
        }

        $desc = [];
        for ($i = 0; $i < $div->length; $i++) {
            if ($div->item($i)->getAttribute('class') == 'pbl box dtmoredetail dt_content') {
                // Access the individual DOMNode
                $node = $div->item($i);

                // Get the inner HTML content of the node (without the <div> tag)
                $innerHTML = '';
                foreach ($node->childNodes as $child) {
                    $innerHTML .= $node->ownerDocument->saveHTML($child);
                }

                // Add the inner HTML content to the $desc array
                $desc[] = $innerHTML;
            }
        }

        $description = implode(' ', $desc);

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
            'desc' => $description ?? '',
            'selling' => trim($selling),
            'mrp' => trim($mrp),
            'multiple' => $images,
            'multipleImages' => json_encode($images),
            'baseimg' => $baseimg,
            'url' => trim($instaUrl),
            'binding' => trim($binding),
            'condition' => trim($condition),
            'isbn_10' => trim($isbn10),
            'isbn_13' => trim($isbn13),
            'publish_year' => trim($publishyear),
            'weight' => trim($weight),
            'reading_age' => trim($age),
            'country_origin' => trim($origin),
            'genre' => trim($genre),
            'manufacturer' => trim($manufacturer),
            'importer' => trim($importer),
            'packer' => trim($packer),
        ];

        if (!$url = $this->getSiteBaseUrl()) {
            session()->flash('message', 'Please complete your Site Setting Then Continue');

            return view('settings.error');
        }

        $response = Http::withoutVerifying()->get($url . '/feeds/posts/default?alt=json');

        $categories = $response->json()['feed']['category'];

        $siteSetting = SiteSetting::first();

        $publications  = WeightVSCourier::all();
        
        return view('listing.edit', compact('post', 'categories', 'allInfo', 'labels', 'siteSetting', 'publications'));
    }

    /**
     * Return the edit of post
     * 
     * @return \Illumindate\View\View
     */
    public function update(Request $request, $postId)
    {
        $user = auth()->user()->id;

        if (isset(request()->created_by) && (request()->created_by != auth()->user()->id)) {
            $user = request()->created_by;
        }

        $data = $this->googleService->updatePost($request->all(), $postId, $user, $user);

        if (method_exists($data, 'getStatusCode') && $data->getStatusCode() == 500) {
            session()->flash('error', json_decode($data->getContent())->message->error->message);

            return redirect()->route('inventory.index', ['startIndex' => 1, 'category' => 'Product']);
        }

        if ($request->edit) {
            session()->flash('success', 'Post updated successfully');

            return redirect()->route('publish.pending', ['status' => 0, 'startIndex' => 1, 'category' => '', 'user' => 'all']);
        }

        session()->flash('success', 'Post updated successfully');

        return redirect()->route('inventory.index', ['startIndex' => 1, 'category' => 'Product']);
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
    public function inventory(Request $request)
    {
        // if ($this->tokenIsExpired($this->googleService)) {

        if (!$this->googleService->getCredentails()) return view('settings.authenticate');

        //     $url = $this->googleService->refreshToken($this->googleService->getCredentails()->toArray());
        //     request()->session()->put('page_url', request()->url());

        //     return redirect()->to($url);
        // }
        // dd($request->all());
        $googlePosts = $this->googleService->posts('live', $request->paging);

        return view('listing.live-inventory', compact('googlePosts'));
    }

    /**
     * Review Listing
     *
     * @return void
     */
    public function reviewInventory(Request $request)
    {
        // dd($request->all());
        $status = 'live';
        // if ($this->tokenIsExpired($this->googleService)) {

        if (!$this->googleService->getCredentails()) return view('settings.authenticate');

        //     $url = $this->googleService->refreshToken($this->googleService->getCredentails()->toArray());
        //     request()->session()->put('page_url', request()->url());

        //     return redirect()->to($url);
        // }

        $googlePosts = $this->googleService->posts($status, $request->paging);
        return view('listing.review-inventory', compact('googlePosts'));
    }

    /**
     * Inventory Listing
     *
     * @return void
     */
    public function draftedInventory()
    {
        if ($this->tokenIsExpired($this->googleService)) {
            // if (!$this->googleService->getCredentails()) return view('settings.authenticate');

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

    /**
     * Search
     */
    public function search()
    {
        $googlePosts = [];

        if (request()->q) {
            $googlePosts = $this->googleService->posts();
        }

        $publications = [];

        if (request()->p) {
            $publications = WeightVSCourier::where('pub_name', request()->p)
                ->get();
        }

        return view('listing.search', compact('googlePosts', 'publications'));
    }

    public function inventoryReviewExport()
    {
        return Excel::download(new InventoryReviewExport($this->getInventoryReviewExportFile()), 'review-inventory.xlsx');
    }

    public function getInventoryReviewExportFile()
    {
        $allDataArr = [];

        $googlePosts = $this->googleService->exportData();
        foreach ($googlePosts as $key => $googlePost) {
            $productId = explode('-', ((array)$googlePosts[$key]->id)['$t'])[2];
            $productTitle = ((array)$googlePosts[$key]->title)['$t'];
            $allDataArr[$key][] = $productId;
            $allDataArr[$key][] = $productTitle;
        }
        $heading = [
            'Product Id',
            'Book Name',
        ];

        array_unshift($allDataArr, $heading);

        return $allDataArr;
    }
}
