<?php

namespace App\Services;

use Google_Client;
use Google_Service_Blogger;
use Google_Service_Blogger_Post;
use App\Models\GoogleCredentail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Storage;

class GoogleService
{
    /**
     * Redirect to google for auth
     * 
     * @return string|void
     */
    public function redirectToGoogle(array $data)
    {
        try {
            $client = $this->createGoogleClient($data);

            $authUrl = $client->createAuthUrl();

            $credExists = $this->getCredentails();

            if (!$credExists) {
                GoogleCredentail::create(request()->all());
            }

            return $authUrl;
        } catch (\Exception $e) {
            report($e);
        }
    }

    /**
     * Create google client instance
     * 
     * @return \Google\Client
     */
    private function createGoogleClient(array $data)
    {
        $client = new Google_Client();
        $client->setClientId($data['client_id']);
        $client->setClientSecret($data['client_secret']);
        $client->setRedirectUri($data['redirect_uri']);
        $client->addScope('https://www.googleapis.com/auth/blogger');

        return $client;
    }

    /**
     * Handle google callback
     * 
     * @return void
     */
    public function handleGoogleCallback(array $data)
    {
        try {
            $credential = $this->getCredentails();

            $client = $this->createGoogleClient($credential->toArray());

            $token = $client->fetchAccessTokenWithAuthCode($data['code']);

            $credential->token = json_encode($token);

            $credential->save();
        } catch (\Exception $e) {
            Log::channel('handleGoogleCallBack')->info($e);
        }
    }

    /**
     * Fetch the listing of the resources
     * 
     * @return array
     */
    public function posts()
    {
        $credential = $this->getCredentails();

        $client = $this->createGoogleClient($credential->toArray());
        $client->setAccessToken($credential->token);

        $blogger = new Google_Service_Blogger($client);

        return $blogger->posts->listPosts($credential->blog_id);
    }

    /**
     * Create blog post in blogger
     * 
     * @param array $data
     */
    public function createPost(array $data)
    {
        try {
            $credential = $this->getCredentails();

            $client = new Google_Client();

            $client->setScopes('https://www.googleapis.com/auth/blogger');
            $client->setAccessToken(json_decode($credential->token)->access_token);

            $blogger = new Google_Service_Blogger($client);

            $post = new Google_Service_Blogger_Post();
            $post->title = $data['title'];
            $post->content = view('listing.template', compact('data'))->render();
            $post->setLabels($data['label']);

            return $blogger->posts->insert($credential->blog_id, $post);
        } catch (\Google_Service_Exception $e) {
            return json_decode($e->getMessage());
        }
    }

    /**
     * Edit the post 
     * 
     * @return void
     */
    public function editPost($postId)
    {
        try {
            $credential = $this->getCredentails();

            $client = new Google_Client();

            $client->setScopes('https://www.googleapis.com/auth/blogger');

            $client->setAccessToken(json_decode($credential->token)->access_token);

            $blogger = new Google_Service_Blogger($client);

            // Retrieve the existing post
            return $blogger->posts->get($credential->blog_id, $postId);
        } catch (\Google_Service_Exception $e) {
            // Log the error details for debugging
            \Log::error('Blogger API Error: ' . $e->getMessage());

            // Handle the error as needed
            return json_decode($e->getMessage());
        }
    }

    /**
     * Update the post 
     * 
     * @return void
     */
    public function updatePost($data, $postId)
    {
        $this->processImage($data['images'][0]->getClientOriginalName());


        die;
        try {
            $credential = $this->getCredentails();

            $client = new Google_Client();

            $client->setScopes('https://www.googleapis.com/auth/blogger');

            $client->setAccessToken(json_decode($credential->token)->access_token);

            $blogger = new Google_Service_Blogger($client);

            $existingPost = $blogger->posts->get($credential->blog_id, $postId);

            $existingPost->title = $data['title'];
            $existingPost->content = view('listing.template', compact('data'))->render();
            $existingPost->setLabels($data['label']);

            return $blogger->posts->update($credential->blog_id, $postId, $existingPost);
        } catch (\Google_Service_Exception $e) {
            \Log::error('Blogger API Error: ' . $e->getMessage());

            return json_decode($e->getMessage());
        }
    }

    /**
     * Delete post
     * 
     * @return mixed
     */
    public function deletePost($postId)
    {
        try {
            // Get the latest Google credential
            $credential = $this->getCredentails();

            // Create a new Google client
            $client = new Google_Client();

            // Set the required scope
            $client->setScopes('https://www.googleapis.com/auth/blogger');

            // Set the access token from the credential
            $client->setAccessToken(json_decode($credential->token)->access_token);

            // Create a Blogger service using the client
            $blogger = new Google_Service_Blogger($client);

            // Get the existing post
            $existingPost = $blogger->posts->get($credential->blog_id, $postId);

            // Delete the post
            return $blogger->posts->delete($credential->blog_id, $postId);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Refresh Token
     *
     * @param array $data
     * @return void
     */
    public function refreshToken($data)
    {
        $redirectUri = $this->redirectToGoogle($data);

        return $redirectUri;
    }

    /**
     * Get Credentails
     *
     * @return void
     */
    public function getCredentails()
    {
        return  GoogleCredentail::latest()->first();
    }

    /**
     * Process Image
     *
     * @param Request $request
     * @return void
     */
    public function processImage($fileName)
    {
        $manager = new ImageManager(
            new Driver()
        );

        // open an image file
        $image = $manager->read(public_path('background.jpg'));

        // resize image instance
        $image->resize(width: 555, height: 555);

        // insert a watermark
        $image->place($fileName, 'center');

        // encode edited image
        $encoded = $image->toJpg();

        // save encoded image
        $encoded->save(public_path($fileName));

        return '' . $fileName;
    }
}
