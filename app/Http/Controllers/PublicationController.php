<?php
namespace App\Http\Controllers;

use App\Models\Publication;
use Illuminate\Http\Request;

class PublicationController extends Controller
{
    public function getpublishers(Request $request)
    {
        $query = $request->get('query');
        $suggestions = Publication::where('publication_name', 'LIKE', "%{$query}%")
            ->take(10)
            ->pluck('publication_name');

        return response()->json($suggestions);
    }

    public function getpublications(Request $request)
    {
        $query = $request->get('query');
        $publications = Publication::where('publication_name', 'LIKE', "%{$query}%")->get();
        return response()->json($publications);
    }
}
