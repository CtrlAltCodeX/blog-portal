<?php

namespace App\Http\Controllers;

use App\Models\GoogleCredentail;

class SettingsController extends Controller
{   
    /**
     * Index function
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $creds = GoogleCredentail::latest()->first();

        return view('settings.index', compact('creds'));
    }
}
