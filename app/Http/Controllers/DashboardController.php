<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\GoogleService;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     * 
     * @return void
     */
    public function __construct(protected GoogleService $googleService)
    {
        $this->middleware('role_or_permission:Dashboard Access', ['only' => ['index']]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $active = User::where('status', 1)->count();

        $inactive = User::where('status', 0)->count();

        $allUser = User::count();

        if ($this->tokenIsExpired($this->googleService))
            return view('settings.authenticate');

        $allGooglePosts = $this->googleService->posts();
        
        $allDraftedGooglePosts = $this->googleService->posts('draft');

        $productStats = [];

        foreach ($allGooglePosts as $allGooglePost) {
            if (isset($allGooglePost->labels) && in_array('Stk_o', $allGooglePost->labels)) {
                $productStats['out_stock'][] = $allGooglePost;
            } else if (isset($allGooglePost->labels) && in_array('Stk_d', $allGooglePost->labels)) {
                $productStats['on_demand'][] = $allGooglePost;
            } else if (isset($allGooglePost->labels) && in_array('Stk_b', $allGooglePost->labels)) {
                $productStats['pre_booking'][] = $allGooglePost;
            } else if (isset($allGooglePost->labels) && in_array('Stk_l', $allGooglePost->labels)) {
                $productStats['low_stock'][] = $allGooglePost;
            } else if (isset($allGooglePost->labels) && in_array('Stk_l', $allGooglePost->labels)) {
                $productStats['low_stock'][] = $allGooglePost;
            } else {
                $productStats['in_stock'][] = $allGooglePost;
            }
        }

        return view('dashboard.index', compact('allUser', 'inactive', 'active', 'productStats', 'allDraftedGooglePosts', 'allGooglePosts'));
    }
}
