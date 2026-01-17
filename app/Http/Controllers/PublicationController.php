<?php

namespace App\Http\Controllers;

use App\Models\Publication;
use Illuminate\Http\Request;
use App\Models\WeightVSCourier;
use DB;

class PublicationController extends Controller
{
    public function getpublishers(Request $request)
    {
        $query = $request->get('query');

        $suggestions = WeightVSCourier::where('pub_name', 'LIKE', "%{$query}%")
            ->take(10)
            ->pluck('pub_name');

        // $suggestions = Publication::where('publication_name', 'LIKE', "%{$query}%")
        //     ->take(10)
        //     ->pluck('publication_name');

        return response()->json($suggestions);
    }

    public function getpublications(Request $request)
    {
        $query = $request->get('query');
        $publications = WeightVSCourier::where('pub_name', 'LIKE', "%{$query}%")
            ->get();

        return response()->json($publications);
    }

    public function details(Request $request)
    {
        $data = DB::table('weight_vs_couriers')->find(request()->publication_id);

        return response()->json($data);
    }
}
