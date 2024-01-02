<?php

namespace App\Http\Controllers;

use App\Models\GoogleCredentail;
use Illuminate\Http\Request;

class SettingsController extends Controller
{   
    /**
     * Index function
     *
     * @return void
     */
    public function index()
    {
        $creds = GoogleCredentail::find(1);

        return view('settings.index', compact('creds'));
    }
}
