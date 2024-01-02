<?php

namespace App\Http\Controllers;

use App\Models\GoogleCredentail;
use Google_Client;
use Illuminate\Http\Request;

class GoogleController extends Controller
{
    /**
     * Redirect to Google
     *
     * @return void
     */
    public function redirectToGoogle()
    {
        $client = new Google_Client(request()->all());
        $client->addScope('https://www.googleapis.com/auth/blogger');
        $authUrl = $client->createAuthUrl();
        $credExists = GoogleCredentail::find(1);

        !$credExists ? GoogleCredentail::create(request()->all()) : '';

        return redirect()->to($authUrl);
    }

    /**
     * Handle CallBack Funciton
     *
     * @param Request $request
     * @return void
     */
    public function handleGoogleCallback(Request $request)
    {
        $client = new Google_Client(config('google'));
        $code = $request->get('code');
        $token = $client->fetchAccessTokenWithAuthCode($code);

        $credExists = GoogleCredentail::find(1);

        $credExists->update([
            'token' => $token
        ]);

        return redirect()->route('setting.index')->with('success', 'Authenticated successfully');

        // Use $token to make requests to the Blogger API
        // $client->setAccessToken($credExists->token);

        // // Initialize the Blogger service
        // $blogger = new \Google_Service_Blogger($client);

        // // Retrieve posts
        // $blogId = '1189594597203978256'; // Replace with your actual Blog ID
        // $posts = $blogger->posts->listPosts($blogId);
// dd($posts);
        // // Process and display the retrieved posts
        // return view('posts', ['posts' => $posts]);
    }
}
