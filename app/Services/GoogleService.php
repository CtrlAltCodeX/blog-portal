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

            $credExists = GoogleCredentail::latest()->first();

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
            $credential = GoogleCredentail::latest()->first();

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
        $credential = GoogleCredentail::latest()->first();

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
            $credential = GoogleCredentail::latest()->first();

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
            $credential = GoogleCredentail::latest()->first();

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
        try {
            $credential = GoogleCredentail::latest()->first();

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
            $credential = GoogleCredentail::latest()->first();

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
     * Process Image
     *
     * @param Request $request
     * @return void
     */
    public function processImage(Request $request)
    {
        $manager = new ImageManager(
            new Driver()
        );
        // Step 1: Generate a background image
        $background = $manager->canvas(555, 555, '#ffffff'); // Change dimensions as needed
dd($background);
        // Step 2: Upload the user's image
        $uploadedImage = $request->file('image');
        $userImage = Image::make($uploadedImage);

        // Step 3: Merge both images
        $background->insert($userImage, 'center');

        // Step 4: Save the merged image
        $outputPath = public_path('merged_images/');
        $outputFileName = 'merged_image_' . time() . '.' . $uploadedImage->getClientOriginalExtension();
        $background->save($outputPath . $outputFileName);

        // Step 5: Return the path to the merged image
        return $outputPath . $outputFileName;
    }
}
