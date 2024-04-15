<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\UserListingCount;
use App\Models\UserSession;
use App\Services\GoogleService;
use Carbon\Carbon;
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
        $this->middleware('role_or_permission:Dashboard', ['only' => ['index']]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        if ($this->tokenIsExpired($this->googleService)) {
            session()->flash('message', 'Please authenticate with Google');

            return view('settings.error');
        }

        if (!$url = $this->getSiteBaseUrl()) {
            session()->flash('message', 'Please complete your Site Setting Then Continue');

            return view('settings.error');
        }

        $userSessionsCount = UserSession::where("user_id", auth()->user()->id)
            ->get();

        $approved = UserListingInfo::where('approved_by', '!=', '');

        $pending = Listing::where('status', 0);

        $rejected = Listing::where('status', 2);

        if (auth()->user()->hasRole('Super Admin')) {
            $approved = $approved->count();

            $pending = $pending->count();

            $rejected = $rejected->count();
        } else {
            $approved = $approved
                ->where('created_by', auth()->user()->id)
                ->count();

            $pending = $pending
                ->where('created_by', auth()->user()->id)
                ->count();

            $rejected = $rejected
                ->where('created_by', auth()->user()->id)
                ->count();
        }

        return view('dashboard.index', compact('userSessionsCount', 'approved', 'rejected', 'pending'));
    }

    /**
     * Get Statistics
     *
     * @param string $category
     * @return int
     */
    public function getStats()
    {
        $url = $this->getSiteBaseUrl();

        $category = request()->category;

        $agoDate = Carbon::now();

        if (request()->filled('updated_before')) {
            // Get the current date
            $currentDate = Carbon::now();

            if (
                request()->query('updated_before') == 1
                || request()->query('updated_before') == 2
                || request()->query('updated_before') == "3Y"
            ) {
                $updateBefore = request()->query('updated_before');
                if (request()->query('updated_before') == "3Y") $updateBefore = 3;
                $agoDate = $currentDate->subYear($updateBefore);
            } else {
                // Subtract three months from the current date
                $agoDate = $currentDate->subMonths(request()->query('updated_before'));
            }
        }

        $response = Http::get($url . '/feeds/posts/default?alt=json&category=' . $category . "&updated-max=" . $agoDate?->format('Y-m-d') . "T00:00:00");

        return $response->json()['feed']['openSearch$totalResults']['$t'];
    }
}
