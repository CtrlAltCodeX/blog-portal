<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GoogleService;
use App\Http\Requests\BlogRequest;
use Illuminate\Support\Facades\Http;

class ListingController extends Controller
{

    /**
     * Create a new controller instance.
     * 
     * @return void
     */
    public function __construct(protected GoogleService $googleService)
    {
    }

    /**
     * Display the listing of the resources
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $googlePosts = $this->googleService->posts();

        return view('listing.index', compact('googlePosts'));
    }

    /**
     * Show the create page of list page
     * 
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $response = Http::get('https://publication.exam360.in/feeds/posts/default?alt=json');

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

            return redirect()->route('listing.index');
        }

        session()->flash('success', 'Post created successfully');

        return redirect()->route('listing.index');
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

        $doc = new \DOMDocument();
        $doc->loadHTML($post->content);
        $sku = $doc->getElementById('sku')->textContent;
        $publication = $doc->getElementById('publication')->textContent;
        $author = $doc->getElementById('author')->textContent;
        $author_name = $doc->getElementById('author_name')->textContent;
        $page_no = $doc->getElementById('page_no')->textContent;
        $weight = $doc->getElementById('weight')->textContent;
        $search_key = $doc->getElementById('search_key')->textContent;
        $desc = $doc->getElementById('desc')->textContent;
        $edition = $doc->getElementById('edition')->textContent;
        $selling = $doc->getElementById('selling')->textContent;
        $mrp = $doc->getElementById('mrp')->textContent;

        for ($i = 0; $i < 3; $i++) {
            $image = $doc->getElementsByTagName("img")->item($i);
            $images[] = $image->getAttribute('src');
        }

        $allInfo = [
            'sku' => $sku,
            'publication' => $publication,
            'author' => $author,
            'author_name' => $author_name,
            'page_no' => $page_no,
            'weight' => $weight,
            'search_key' => $search_key,
            'desc' => $desc,
            'edition' => $edition,
            'selling' => $selling,
            'mrp' => $mrp,
            'image1' => $images,
        ];

        $response = Http::get('https://publication.exam360.in/feeds/posts/default?alt=json');

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

        return redirect()->route('listing.index');
    }

    /**
     * Delete Posts
     *
     * @param int $postId
     * @return \Illumindate\View\View
     */
    public function destroy($postId)
    {
        $post = $this->googleService->deletePost($postId);

        session()->flash('success', 'Post delete successfully');

        return redirect()->route('listing.index');
    }
}
