<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserListingCount;

class GraphicalDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $users = User::all();

        $cardUserId = $request->get('card_user_id') ?? auth()->id();
        $graphUserId = $request->get('graph_user_id') ?? auth()->id();

        $cardTotals = null;
        $graphTotals = null;

        if ($cardUserId) {
            $listingCounts = UserListingCount::where('user_id', $cardUserId)->get();

            $cardTotals = [
                'approved' => $listingCounts->sum('approved_count'),
                'rejected' => $listingCounts->sum('reject_count'),
                'deleted' => $listingCounts->sum('delete_count'),
                'created' => $listingCounts->sum('create_count'),
            ];
        }

        if ($graphUserId) {
            $listingCounts = UserListingCount::where('user_id', $graphUserId)->get();

            $graphTotals = [
                'approved' => $listingCounts->sum('approved_count'),
                'rejected' => $listingCounts->sum('reject_count'),
                'deleted' => $listingCounts->sum('delete_count'),
                'created' => $listingCounts->sum('create_count'),
            ];
        }

        return view('dashboard.graphical', compact('users', 'cardTotals', 'graphTotals', 'cardUserId', 'graphUserId'));
    }
}
