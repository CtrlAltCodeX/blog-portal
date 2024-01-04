<?php

namespace App\Services;

use Google_Client;
use Google_Service_Blogger;
use Google_Service_Blogger_Post;
use App\Models\GoogleCredentail;
use Illuminate\Support\Facades\Log;

class GoogleService {
    /**
     * Redirect to google for auth
     * 
     * @return string|void
     */
    public function redirectToGoogle(array $data) {
        try {
            $client = $this->createGoogleClient($data);

            $authUrl = $client->createAuthUrl();
    
            $credExists = GoogleCredentail::latest()->first();
    
            if (! $credExists) {
                GoogleCredentail::create(request()->all());
            }
    
            return $authUrl;
        } catch(\Exception $e) {
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
            $credential = GoogleCredentail::find(1);
    
            $client = new Google_Client();
            $client->setAccessToken($credential->token);

            $blogger = new Google_Service_Blogger($client);

            $post = new Google_Service_Blogger_Post();
            $post->setTitle($data['title']);
            $post->setContent($data['description']);

            return $blogger->posts->insert($credential->blog_id, $post);
        } catch (\Google_Service_Exception $e) {
            return json_decode($e->getMessage());
        }
    }
}

?>