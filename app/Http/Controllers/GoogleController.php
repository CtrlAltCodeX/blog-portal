<?php

namespace App\Http\Controllers;

use App\Services\GoogleService;
use Illuminate\Http\Request;
use App\Models\GoogleCredentail;

class GoogleController extends Controller
{
    /**
     * Construct
     *
     * @param GoogleService $googleService
     */
    public function __construct(protected GoogleService $googleService)
    {
        $this->middleware('role_or_permission:Configure Blog Update', ['only' => ['redirectToGoogle']]);
    }

    /**
     * Redirect to Google
     *
     * @return void
     */
    public function redirectToGoogle()
    {
        $credExists = GoogleCredentail::where('scope', request()->scope)
            ->first();

        if ($credExists) {
            GoogleCredentail::where('scope', request()->scope)
                ->delete();

            return redirect()->route('settings.blog')->with('success', 'Removed successfully');
        } else {
            $redirectUri = $this->googleService->redirectToGoogle(request()->all());

            return redirect()->to($redirectUri);
        }
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

        if (request()->session()->get('page_url')) {
            return redirect()->to(request()->session()->get('page_url'));
        }

        return redirect()->route('settings.blog')->with('success', 'Authenticated successfully');
    }

    /**
     * Refresh The Tokenn
     *
     * @return void
     */
    public function refreshGoogle()
    {
        $url = $this->googleService->refreshToken(request()->all());

        return response()->json($url, 200);
    }

    /**
     * List Products from Google
     *
     * @return void
     */
    public function listProducts()
    {
        $scope = 'Merchant';

        if ($this->tokenIsExpired($this->googleService, $scope)) {

            if (!$this->googleService->getCredentails($scope)) return view('settings.authenticate');

            $url = $this->googleService->refreshToken($this->googleService->getCredentails($scope)->toArray());
            request()->session()->put('page_url', request()->url());

            return redirect()->to($url);
        }

        $products = collect($this->googleService->googleMerchantCenter());

        return view('google.index', compact('products'));
    }
}
