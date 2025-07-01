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
        if($request->has('user_id')){
            $user = User::find($request->input('user_id'));
            if($user){
                if($user->api_key != $request->input('api_key')){
                    return response()->json(['error' => 'Unauthorized'], 401);
                }
            }else{
                return response()->json(['error' => 'User not found'], 401);
            }
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

}
