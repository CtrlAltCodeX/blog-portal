<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
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
        $googlePosts = $this->googleService->posts();

        return view('listing.index', compact('googlePosts'));
    }
}
