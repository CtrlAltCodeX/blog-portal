<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ListingController extends Controller
{
    public function index()
    {
        return view('listing.index');
        // $client = new Google_Client(config('google'));
        // $code = $request->get('code');
        // // $token = $client->fetchAccessTokenWithAuthCode($code);
    }
}
