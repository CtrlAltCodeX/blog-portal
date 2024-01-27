<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\GoogleService;
use Illuminate\Support\Facades\Http;

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

        if ($this->tokenIsExpired($this->googleService)) {
            $url = $this->googleService->refreshToken($this->googleService->getCredentails()->toArray());
            request()->session()->put('page_url', request()->url());

            return redirect()->to($url);
        }

        if (!$url = $this->getSiteBaseUrl()) {
            session()->flash('message', 'Please complete your Site Setting Then Continue');

            return view('settings.error');
        }

        $response = Http::get($url . '/feeds/posts/default?alt=json');

        $totalProducts = $response->json()['feed']['openSearch$totalResults']['$t'];

        $allGooglePosts = $this->googleService->posts();

        $allDraftedGooglePosts = $this->googleService->posts('draft')['paginator'];

        $productStats = [];

        foreach ($allGooglePosts['paginator'] as $allGooglePost) {
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

        return view('dashboard.index', compact('allUser', 'inactive', 'active', 'productStats', 'allDraftedGooglePosts', 'allGooglePosts', 'totalProducts'));
    }
}
