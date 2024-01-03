<?php

namespace App\Http\Controllers;

use App\Services\GoogleService;
use Illuminate\Http\Request;

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
