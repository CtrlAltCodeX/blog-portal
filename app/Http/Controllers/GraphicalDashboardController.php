<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserListingCount;
use Carbon\Carbon;

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
        $count = app('App\Models\UserListingCount');
        $fromDate = Carbon::now()->subDays(6)->startOfDay(); // 6 din pehle se leke
        $toDate = Carbon::now()->endOfDay(); // aaj tak
    
        if ($cardUserId) {
            $listingCounts = UserListingCount::where('user_id', $cardUserId)
                ->whereBetween('created_at', [$fromDate, $toDate])
                ->get();
    
            $cardTotals = [
                'approved' => $listingCounts->sum('approved_count'),
                'rejected' => $listingCounts->sum('reject_count'),
                'deleted' => $listingCounts->sum('delete_count'),
                'created' => $listingCounts->sum('create_count'),
            ];
        }
    
        if ($graphUserId) {
            $listingCounts = UserListingCount::where('user_id', $graphUserId)
                ->whereBetween('created_at', [$fromDate, $toDate])
                ->get();
    
            $graphTotals = [
                'approved' => $listingCounts->sum('approved_count'),
                'rejected' => $listingCounts->sum('reject_count'),
                'deleted' => $listingCounts->sum('delete_count'),
                'created' => $listingCounts->sum('create_count'),
            ];
        }

     

    $startOfWeek = Carbon::now()->subDays(8);
    $endOfWeek = Carbon::now()->subDays(1);

    $currentWeekDataCreated = $count->whereBetween('created_at', [$startOfWeek, $endOfWeek])
        ->where('user_id', $cardUserId)
        ->sum('create_count');
    
        return view('dashboard.graphical', compact('users', 'cardTotals', 'graphTotals', 'cardUserId', 'graphUserId','currentWeekDataCreated'));
    }
    
}
