<?php

namespace App\Services;

use Google_Client;
use Google_Service_Blogger;
use Google_Service_Blogger_Post;
use App\Models\GoogleCredentail;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon;

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
    public function posts($status = null)
    {

        $credential = $this->getCredentails();

        $client = $this->createGoogleClient($credential->toArray());
        $client->setAccessToken($credential->token);

        $blogger = new Google_Service_Blogger($client);
        
        $posts = [];
        $pageToken = null;
        $perPage = 250;

        $params = [
            'maxResults' => $perPage,
        ];

        if (request()->has('pageToken')) {
            $params['pageToken']  = request()->query('pageToken');
        }

        if (request()->filled('status')) {
            $params['status'] = request()->query('status');
        }

        if ($type = request()->has('type') && $type = 'search') {
            if (request()->filled('q')) {
                $params['q'] = request()->query('q');
            }
        }

        if (request()->filled('endDate')) {
            $endDate = request()->query('endDate');
            $carbonEndDate = Carbon::parse($endDate);
            $params['endDate'] =  $carbonEndDate->format('Y-m-d\TH:i:sP');
        }

        if (request()->filled('startDate')) {
            $startDate = request()->query('startDate');
            $carbonstartDate = Carbon::parse($startDate);
            $params['startDate'] =  $carbonstartDate->format('Y-m-d\TH:i:sP');
        }

        if ($type == 'search') {
            $response = $blogger->posts->search($credential->blog_id, $params);
        } else {
            $response = $blogger->posts->listPosts($credential->blog_id, $params);
        }

        $posts = $response->getItems();
        $nextPageToken = $response->getNextPageToken();
        $prevPageToken = $response->getPrevPageToken();
    
        $paginator = new LengthAwarePaginator(
            $posts,
            count($response->items ?? []),
            1,
            count($response->items ?? []),
            ['path' => route('inventory.index')]
        );
        
        return [
            'paginator' => $paginator,
            'nextPageToken' => $nextPageToken,
            'prevPageToken' => $prevPageToken,
        ];
    }
    
    // /*
    //  *Get Current page
    //  */
    // public function getCurrentPage($nextPageToken, $perPage)
    // {
    //     if (!$nextPageToken) {
    //         return 1; // If there is no next page token, assume it's the first page
    //     }
    
    //     parse_str(parse_url($nextPageToken, PHP_URL_QUERY), $queryParams);
        
    //     return isset($queryParams['pageToken']) ? $queryParams['pageToken'] / $perPage + 1 : 1;
    // }

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

            $data['multiple_images'] = [];
            $data['processed_images'] = [];

            if (isset($data['images'])) {
                foreach ($data['images'] as $image) {
                    $data['processed_images'][] = $this->processImage($image);
                }
            }

            if (isset($data['multipleImages'])) {
                foreach ($data['multipleImages'] as $image) {
                    $data['multiple_images'][] = $this->processImage($image);
                }
            }

            $blogger = new Google_Service_Blogger($client);

            $post = new Google_Service_Blogger_Post();
            $post->title = $data['title'];
            $post->content = view('listing.template', compact('data'))->render();
            $post->setLabels($data['label']);

            $isDraft = [];
            if (request()->isDraft) $isDraft = ['isDraft' => request()->isDraft];

            return $blogger->posts->insert($credential->blog_id, $post, $isDraft);
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
        try {
            $credential = $this->getCredentails();

            $client = new Google_Client();

            $client->setScopes('https://www.googleapis.com/auth/blogger');

            $client->setAccessToken(json_decode($credential->token)->access_token);

            $blogger = new Google_Service_Blogger($client);

            $existingPost = $blogger->posts->get($credential->blog_id, $postId);

            $data['processed_images'] = [];
            $data['multiple_images'] = [];

            if (isset($data['images'])) {
                foreach ($data['images'] as $image) {
                    if ($image instanceof UploadedFile) {
                        $data['processed_images'][] = $this->processImage($image);
                    } else {
                        $data['processed_images'][] = $image;
                    }
                }
            }

            if (isset($data['multipleImages'])) {
                foreach ($data['multipleImages'] as $image) {
                    if ($image instanceof UploadedFile) {
                        $data['multiple_images'][] = $this->processImage($image);
                    } else {
                        $data['multiple_images'][] = $image;
                    }
                }
            }

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
    public function processImage($image)
    {
        $background = (new ImageManager())->canvas(555, 555, '#ffffff');

        $background->insert(Image::make($image)->resize(300, 425), 'center');

        $outputFileName = 'merged_image_' . $image->getClientOriginalName() . time() . '.' . $image->getClientOriginalExtension();

        $background->save(public_path($outputFileName));

        return config('app.url') . '/public/' . $outputFileName;
    }
}
