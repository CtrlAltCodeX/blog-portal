<?php

namespace App\Http\Controllers;

use App\Services\GoogleService;
use Illuminate\Http\Request;
use App\Models\GoogleCredentail;

class GoogleController extends Controller
{
    public function __construct(protected GoogleService $googleService)
    {
    }

    /**
     * Redirect to Google
     *
     * @return void
     */
    public function redirectToGoogle()
    {
        $credExists = GoogleCredentail::latest()->first();
                
        if ($credExists) {
            GoogleCredentail::latest()->delete();
            
            return redirect()->route('setting.index')->with('success', 'Removed successfully');
        } 

        $redirectUri = $this->googleService->redirectToGoogle(request()->all());

        return redirect()->to($redirectUri);
    }

    /**
     * Handle CallBack Funciton
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function handleGoogleCallback(Request $request)
    {
        $this->googleService->handleGoogleCallback($request->all());

        return redirect()->route('setting.index')->with('success', 'Authenticated successfully');
    }
}
