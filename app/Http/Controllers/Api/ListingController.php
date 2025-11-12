<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BackupListing;
use App\Models\User;

class ListingController extends Controller
{
    public function index(Request $request)
    {
        // Validate required authentication fields
        $userId = $request->input('user_id');
        $apiKey = $request->input('api_key');
    
        if (!$userId || !$apiKey) {
            return response()->json(['error' => 'user_id and api_key are required.'], 400);
        }
    
        $user = User::find($userId);
    
        if (!$user) {
            return response()->json(['error' => 'User not found.'], 401);
        }
    
        if ($user->api_key !== $apiKey) {
            return response()->json(['error' => 'Unauthorized.'], 401);
        }
        
        $query = BackupListing::query();

        if ($request->has('product_id')) {
            $query->where('product_id', $request->input('product_id'));
        }

        if ($request->has('title')) {
            $query->where('title', 'LIKE', '%' . $request->input('title') . '%');
        }

        if ($request->has('isbn_10')) {
            $query->where('isbn_10', $request->input('isbn_10'));
        }

        if ($request->has('isbn_13')) {
            $query->where('isbn_13', $request->input('isbn_13'));
        }

        $listings = $query->paginate(request('per_page', 10));

        return response()->json($listings);
    }

    public function getListingData($term)
    {
        $listing = BackupListing::orWhere('title', $term)
            ->orWhere('isbn_13', $term)
            ->orWhere('isbn_10', $term)
            ->first();

        if (!$listing) return response()->json(['error' => 'Listing not found.'], 200);

        return response()->json($listing);
    }
}
