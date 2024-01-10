<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\GoogleService;
use Illuminate\Http\Request;

class DashboardController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $active = User::where('status', 1)->count();

        $inactive = User::where('status', 0)->count();

        $allUser = User::count();

        $allGooglePosts = $this->googleService->posts();

        $productStats = [];

        foreach ($allGooglePosts as $allGooglePost) {
            if (isset($allGooglePost->labels) && in_array('Stk_o', $allGooglePost->labels)) {
                $productStats['out_stock'] = $allGooglePost;
            } else if (isset($allGooglePost->labels) && in_array('Stk_d', $allGooglePost->labels)) {
                $productStats['on_demand'] = $allGooglePost;
            } else if (isset($allGooglePost->labels) && in_array('Stk_b', $allGooglePost->labels)) {
                $productStats['pre_booking'] = $allGooglePost;
            } else if (isset($allGooglePost->labels) && in_array('Stk_l', $allGooglePost->labels)) {
                $productStats['low_stock'] = $allGooglePost;
            } else {
                $productStats['in_stock'] = $allGooglePost;
            }
        }

        dd($productStats);

        return view('dashboard.index', compact('allUser', 'inactive', 'active'));
    }
}
