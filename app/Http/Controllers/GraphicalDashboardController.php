<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserListingCount;
use Carbon\Carbon;
use App\Models\UserSession;

class GraphicalDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $users = User::where('status', 1)
            ->get();

        // Filter values
        $selectedUserId = $request->get('user_id', auth()->id());
        $range = $request->get('range', '7');
        $fromDate = $request->get('from_date');
        $toDate = $request->get('to_date');

        // Handle dynamic date range
        [$startDate, $endDate] = $this->getDateRange($range, $fromDate, $toDate);

        // Fetch data only if user is selected
        $listingCounts = UserListingCount::where('user_id', $selectedUserId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        // User creation date
        $selectedUser = User::find($selectedUserId);
        $createdAt = $selectedUser ? $selectedUser->created_at : now();
        $daysSinceCreation = Carbon::parse($createdAt)->diffInDays(Carbon::now());

        // Prepare totals for cards and chart
        $cardTotals = [
            'approved' => $listingCounts->sum('approved_count'),
            'rejected' => $listingCounts->sum('reject_count'),
            'deleted'  => $listingCounts->sum('delete_count'),
            'created'  => $listingCounts->sum('create_count'),
        ];

        $createdCount = $listingCounts->where('status', 'Created')->sum('create_count');
        $editedCount = $listingCounts->where('status', 'Edited')->sum('create_count');
        $sumOfBoth = $createdCount + $editedCount;

        // Created this week for performance badge
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        // dd([$startOfWeek, $endOfWeek]);
        $currentWeekDataCreated = UserListingCount::where('user_id', $selectedUserId)
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->sum('create_count');

        // Send same data for chart
        $graphTotals = $cardTotals;
        $userSessionsCount = UserSession::where("user_id", $selectedUserId)
            ->get();

        // for pie chat
        $now = Carbon::now();
        $startOfThisMonth = $now->copy()->startOfMonth();
        $endOfThisMonth = $now->copy()->endOfMonth();

        $startOfLastMonth = $now->copy()->subMonth()->startOfMonth();
        $endOfLastMonth = $now->copy()->subMonth()->endOfMonth();

        $createdLastMonth = UserListingCount::where('user_id', $selectedUserId)
            ->where('status', 'Created')
            ->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])
            ->sum('create_count');

        $createdThisMonth = UserListingCount::where('user_id', $selectedUserId)
            ->where('status', 'Created')
            ->whereBetween('created_at', [$startOfThisMonth, $endOfThisMonth])
            ->sum('create_count');

        $editedLastMonth = UserListingCount::where('user_id', $selectedUserId)
            ->where('status', 'Edited')
            ->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])
            ->sum('create_count');

        $editedThisMonth = UserListingCount::where('user_id', $selectedUserId)
            ->where('status', 'Edited')
            ->whereBetween('created_at', [$startOfThisMonth, $endOfThisMonth])
            ->sum('create_count');

        $totalLastMonth = $createdLastMonth + $editedLastMonth;
        $totalThisMonth = $createdThisMonth + $editedThisMonth;


        // for expected earning code with pie chat 
        $currentMonthDataCreated = UserListingCount::where('user_id', $selectedUserId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('create_count');

        if ($selectedUser->posting_rate) {
            $rate = (int) $selectedUser->posting_rate;
        } else {
            $rate = 4;
        }

        $expectedEarning = (int) $currentMonthDataCreated * $rate;

        $thisMonthDataCreated = UserListingCount::where('user_id', $selectedUserId)
            ->whereBetween('created_at', [$startOfThisMonth, $endOfThisMonth])
            ->sum('create_count');

        $lastMonthDataCreated = UserListingCount::where('user_id', $selectedUserId)
            ->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])
            ->sum('create_count');


        $thisMonthExpectedEarning = $thisMonthDataCreated * $rate;

        $lastMonthExpectedEarning = $lastMonthDataCreated * $rate;

        $totalDays = Carbon::parse($startDate)->diffInDays(Carbon::parse($endDate));

        $totalDays = max($totalDays, 1);

        $safeLimit = 25 * $totalDays;
        $averageLimit = 50 * $totalDays;
        $goodLimit = 75 * $totalDays;
        $bestLimit = 100 * $totalDays;
        $excellentLimit = 125 * $totalDays;


        $progressPercentage = ($sumOfBoth / $excellentLimit) * 125;
        $progressPercentage = min($progressPercentage, 125);

        $categories = [
            ['label' => 'POOR', 'limit' => 25, 'color' => 'red', 'width' => 20],
            ['label' => 'SAFE ZONE', 'limit' => 50, 'color' => 'orange', 'width' => 40],
            ['label' => 'AVERAGE', 'limit' => 75, 'color' => 'yellow', 'width' => 60],
            ['label' => 'GOOD', 'limit' => 100, 'color' => 'green', 'width' => 80],
            ['label' => 'Excellent', 'limit' => 125, 'color' => 'blue', "width" => 100],
        ];

        $filledSegments = [];
        $remainingPercentage = $progressPercentage;
        
        // dd($remainingPercentage);

        // Detect current zone
        $currentZone = 'Unknown';
        foreach ($categories as $index => $category) {
            $start = $index === 0 ? 0 : $categories[$index - 1]['limit'];
            $end = $category['limit'];
            $segmentWidth = $end - $start;

            if ($remainingPercentage >= $segmentWidth) {
                $filledSegments[] = [
                    'width' => 20,
                    'color' => $category['color'],
                    'label' => $category['label'],
                    'percentage' => 100,
                ];
                $remainingPercentage -= $segmentWidth;
                $currentZone = $category['label'];
            } elseif ($remainingPercentage > 0) {
                $percentage = ($remainingPercentage / $segmentWidth) * 100;
                $filledSegments[] = [
                    'width' => 20,
                    'color' => $category['color'],
                    'label' => $category['label'],
                    'percentage' => round($percentage, 1),
                ];
                $currentZone = $category['label'];
                $remainingPercentage = 0;
            } else {
                break;
            }
        }
        
        // dd($filledSegments);

        $totalCreated = $progressPercentage; 
        $createdEditedCount = $createdCount + $editedCount;

        return view('dashboard.graphical', compact(
            'users',
            'cardTotals',
            'graphTotals',
            'currentWeekDataCreated',
            'range',
            'createdCount',
            'editedCount',
            'sumOfBoth',
            'daysSinceCreation',
            'createdLastMonth',
            'createdThisMonth',
            'editedLastMonth',
            'editedThisMonth',
            'totalLastMonth',
            'totalThisMonth',
            'userSessionsCount',
            'expectedEarning',
            'thisMonthExpectedEarning',
            'lastMonthExpectedEarning',
            'progressPercentage',
            'totalDays',
            'filledSegments',
            'currentZone',
            'createdCount',
            'categories',
            'createdEditedCount'
        ));
    }

    private function getDateRange($range, $fromDate, $toDate)
    {
        switch ($range) {
            case 'today':
                return [Carbon::today(), Carbon::today()->endOfDay()];
            case '3':
            case '7':
            case '15':
            case '30':
            case '60':
            case '90':
                return [Carbon::now()->subDays($range), Carbon::now()->endOfDay()];
            case 'all':
                return [Carbon::createFromTimestamp(0), Carbon::now()->endOfDay()];
            case 'custom':
                $from = $fromDate ? Carbon::parse($fromDate)->startOfDay() : Carbon::now()->startOfDay();
                $to = $toDate ? Carbon::parse($toDate)->endOfDay() : Carbon::now()->endOfDay();
                return [$from, $to];
            default:
                return [Carbon::now()->subDays(7), Carbon::now()->endOfDay()];
        }
    }
}
