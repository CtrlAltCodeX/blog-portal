<?php

namespace App\Http\Controllers;

use App\Services\GoogleService;

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
        $posts = $this->googleService->posts();

        dd($posts);
        return view('listing.index', compact('posts'));
    }
}
