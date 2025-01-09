<?php

namespace App\Services;

use Google_Client;
use Google_Service_Blogger;
use Google_Service_Blogger_Post;
use App\Models\GoogleCredentail;
use App\Models\Listing;
use App\Models\SiteSetting;
use App\Models\UserListingCount;
use App\Models\UserListingInfo;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Intervention\Image\ImageManager;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon;
use Google\Service\ShoppingContent;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Http;
use ZipArchive;

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
            if ($data['scope'] == 'Blogger') {
                $info = request()->except('merchant_id');
                $scope = 'https://www.googleapis.com/auth/blogger';
            } else {
                $info = request()->except('blog_id');
                $scope = 'https://www.googleapis.com/auth/content';
            }

            $client = $this->createGoogleClient($data, $scope);

            $authUrl = $client->createAuthUrl();

            $credExists = $this->getCredentails($data['scope']);

            if (!$credExists) {
                GoogleCredentail::create($info);
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
    private function createGoogleClient(array $data, $scope = "https://www.googleapis.com/auth/blogger")
    {
        $client = new Google_Client();
        $client->setClientId($data['client_id']);
        $client->setClientSecret($data['client_secret']);
        $client->setRedirectUri($data['redirect_uri']);
        $client->addScope($scope);

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
            $data['scope'] == 'https://www.googleapis.com/auth/blogger' ? $scope = 'Blogger' : $scope = 'Merchant';

            $credential = $this->getCredentails($scope);

            $client = $this->createGoogleClient($credential->toArray());

            $client->setHttpClient(new \GuzzleHttp\Client(['verify' => false]));

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
    public function posts($status = 'live', $paging = 150)
    {
        try {
            $posts = [];
            $pageToken = null;
            $perPage = $paging;
            $startIndex = request()->startIndex;

            $params = [
                'orderBy' => 'updated',
                'status' => $status,
                'pageToken' => $pageToken,
                'alt' => 'json'
            ];

            if (request()->filled('updated_before')) {
                // Get the current date
                $currentDate = Carbon::now();

                if (
                    request()->query('updated_before') == 1
                    || request()->query('updated_before') == 2
                    || request()->query('updated_before') == "3Y"
                ) {
                    $updateBefore = request()->query('updated_before');
                    if (request()->query('updated_before') == "3Y") $updateBefore = 3;
                    $agoDate = $currentDate->subYear($updateBefore);
                } else {
                    // Subtract three months from the current date
                    $agoDate = $currentDate->subMonths(request()->query('updated_before'));
                }
            }

            if (
                (request()->route() && (request()->route()->getName() == 'inventory.index'
                    || request()->route()->getName() == 'inventory.review'
                    || request()->route()->getName() == 'listing.search'))
                || App::runningInConsole()
            ) {
                $params['start-index'] = $startIndex;
                $params['max-results'] = $perPage;
                $params['category'] = request()->query('category');
                $params['q'] = request()->query('q');

                if ((request()->route() && request()->route()->getName() == 'inventory.review')
                    && !App::runningInConsole()
                ) {
                    $params['updated-max'] = $agoDate->format('Y-m-d') . "T00:00:00";
                }

                if (SiteSetting::first()->url) {
                    $response = Http::withoutVerifying()->get(SiteSetting::first()->url . '/feeds/posts/default', $params);
                    $response = json_decode(json_encode($response->json()))->feed;
                    $posts = $response->entry ?? [];
                    $filteredPost = $response->entry ?? [];

                    $allCategories = [];
                    if (isset(request()->category)) {
                        $filteredPost = [];
                        foreach ($posts as $key => $post) {
                            foreach ($post->category as $category) {
                                $allCategories[$key][] = $category->term;
                            }

                            if (in_array(request()->category, $allCategories[$key])) {
                                $filteredPost[$key] = (array) $post;
                                $filteredPost[$key]['category'] = $allCategories[$key];
                            }
                        }
                    }
                }
            } else if (request()->route()->getName() == 'inventory.drafted') {
                $credential = $this->getCredentails();

                $client = $this->createGoogleClient($credential->toArray());
                $client->setAccessToken($credential->token);

                $blogger = new Google_Service_Blogger($client);

                $params['maxResults'] = 250;
                $response = $blogger->posts->listPosts($credential->blog_id, $params);
                $posts = $response->items ?? [];
                $filteredPost = $response->items ?? [];
            }

            $nextPageToken = $response->nextPageToken ?? null;
            $prevPageToken = $response->prevPageToken ?? null;

            $paginator = new LengthAwarePaginator(
                json_decode(json_encode($filteredPost)),
                count($response->entry ?? []),
                1,
                count($response->entry ?? []),
                ['path' => route('inventory.index', ['startIndex' => 1, 'category' => 'Product'])]
            );

            return [
                'paginator' => $paginator,
                'nextPageToken' => $nextPageToken,
                'prevPageToken' => $prevPageToken,
                'startIndex' => $startIndex + $perPage,
                'prevStartIndex' => $startIndex - $perPage
            ];
        } catch (\Exception $e) {
            $paginator = new LengthAwarePaginator(
                [],
                count([]),
                1,
                count([]),
                ['path' => route('inventory.index', ['startIndex' => 1, 'category' => 'Product'])]
            );

            return [
                'paginator' => $paginator,
                'nextPageToken' => null,
                'prevPageToken' => null,
                'startIndex' => null,
                'prevStartIndex' => null
            ];
        }
    }

    public function exportData($status = 'live')
    {
        $posts = [];
        $pageToken = null;
        $perPage = 150;
        $startIndex = request()->startIndex;

        $params = [
            'orderBy' => 'updated',
            'status' => $status,
            'pageToken' => $pageToken,
            'alt' => 'json'
        ];

        $params['start-index'] = $startIndex;
        $params['max-results'] = $perPage;
        $params['category'] = request()->query('category');
        $params['q'] = request()->query('q');

        if (request()->filled('updated_before')) {
            // Get the current date
            $currentDate = Carbon::now();

            if (
                request()->query('updated_before') == 1
                || request()->query('updated_before') == 2
                || request()->query('updated_before') == "3Y"
            ) {
                $updateBefore = request()->query('updated_before');
                if (request()->query('updated_before') == "3Y") $updateBefore = 3;
                $agoDate = $currentDate->subYear($updateBefore);
            } else {
                // Subtract three months from the current date
                $agoDate = $currentDate->subMonths(request()->query('updated_before'));
            }
        }

        $params['updated-max'] = $agoDate->format('Y-m-d') . "T00:00:00";

        if (SiteSetting::first()->url) {
            $response = Http::withoutVerifying()->get(SiteSetting::first()->url . '/feeds/posts/default', $params);
            $response = json_decode(json_encode($response->json()))->feed;
            $posts = $response->entry ?? [];
            $filteredPost = $response->entry ?? [];

            $allCategories = [];
            if (isset(request()->category)) {
                $filteredPost = [];
                foreach ($posts as $key => $post) {
                    foreach ($post->category as $category) {
                        $allCategories[$key][] = $category->term;
                    }

                    if (in_array(request()->category, $allCategories[$key])) {
                        $filteredPost[$key] = (array) $post;
                        $filteredPost[$key]['category'] = $allCategories[$key];
                    }
                }
            }
        }

        return $filteredPost;
    }

    /**
     * Create blog post in blogger
     * 
     * @param array $data
     */
    public function createPost(array $data, $draft = null, $userId, $currentUser)
    {
        try {
            $credential = $this->getCredentails();

            $client = new Google_Client();

            $client->setScopes('https://www.googleapis.com/auth/blogger');
            $client->setAccessToken(json_decode($credential->token)->access_token);
            $client->setHttpClient(new \GuzzleHttp\Client(['verify' => false]));

            $data['multiple_images'] = [];
            $data['processed_images'] = [];

            $data['processed_images'] = $data['images'];

            if (isset($data['multipleImages'])) {
                foreach ($data['multipleImages'] as $image) {
                    if ($image instanceof UploadedFile) {
                        $data['multiple_images'][] = $this->processImage($image);
                    } else {
                        $data['multiple_images'][] = $image;
                    }
                }
            }

            $blogger = new Google_Service_Blogger($client);

            $post = new Google_Service_Blogger_Post();
            $post->title = $data['title'];
            $post->content = view('listing.template', compact('data'))->render();
            $post->setLabels($data['label']);

            $isDraft = [];
            if (request()->isDraft) $isDraft = ['isDraft' => request()->isDraft];

            if ($draft == 4) $isDraft = ['isDraft' => 1];
            
            $created = $blogger->posts->insert($credential->blog_id, $post, $isDraft);

            if (isset($data['id']) || isset($data['database'])) {
                $id = isset($data['id']) ? $data['id'] : $data['database'];

                $listing = Listing::find($id);

                $this->updateTheCount('Created', $userId);

                $additionalInfo = UserListingInfo::where('listings_id', $listing->id)
                    ->first();

                $listing->delete();

                $additionalInfo->update([
                    'status' => 1,
                    'approved_by' => $currentUser,
                    'approved_at' => now()
                ]);
            }

            return $created;
        } catch (\Google_Service_Exception $e) {
            return json_decode($e->getMessage());
        }
    }

    public function updateTheCount($status, $userId)
    {
        // Get the current date
        $currentDate = Carbon::now()->toDateString(); // This will give you 'YYYY-MM-DD' format

        // Check if a record exists for the current date and user
        $userListingCount = UserListingCount::where('user_id', $userId)
            ->where('status', $status)
            ->whereDate('created_at', $currentDate)
            ->first();

        if ($userListingCount) {
            // If record exists, increment the approved_count
            $userListingCount->increment('approved_count');
            $userListingCount->status = $status; // Update status if needed
            $userListingCount->save();
        } else {
            // If no record exists, create a new record
            UserListingCount::create([
                'user_id' => $userId,
                'approved_count' => 1,
                'status' => $status,
            ]);
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

            $client->setHttpClient(new \GuzzleHttp\Client(['verify' => false]));

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
    public function updatePost($data, $postId, $userId, $currentUser)
    {
        try {
            $credential = $this->getCredentails();

            $client = new Google_Client();

            $client->setScopes('https://www.googleapis.com/auth/blogger');

            $client->setAccessToken(json_decode($credential->token)->access_token);

            $client->setHttpClient(new \GuzzleHttp\Client(['verify' => false]));

            $blogger = new Google_Service_Blogger($client);

            $existingPost = $blogger->posts->get($credential->blog_id, $postId);

            $data['processed_images'] = [];
            $data['multiple_images'] = [];

            $data['processed_images'] = $data['images'];

            // if (isset($data['images'])) {
            //     foreach ($data['images'] as $image) {
            //         if ($image instanceof UploadedFile) {
            //             $data['processed_images'][] = $this->processImage($image);
            //         } else {
            //             $data['processed_images'][] = $image;
            //         }
            //     }
            // }

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
            $currentDateTime = Carbon::now()->toIso8601String();
            $existingPost->published = $currentDateTime;

            $bloggerData = $blogger->posts->update($credential->blog_id, $postId, $existingPost);

            if (isset($data['product_id'])) {
                $listing = Listing::where('product_id', $data['product_id'])
                    ->first();

                $additionalInfo = UserListingInfo::where('listings_id', $listing->id)
                    ->first();

                $additionalInfo->update([
                    'status' => 1,
                    'approved_by' => $currentUser,
                    'approved_at' => now()
                ]);

                $this->updateTheCount('Edited', $userId);

                $listing->delete();
            }

            return $bloggerData;
        } catch (\Google_Service_Exception $e) {
            \Log::error('Blogger API Error: ' . $e->getMessage());

            return response()->json([
                'message' => json_decode($e->getMessage()),
            ], 500);
        }
    }

    /**
     * Get Drafted Data 
     *
     * @return void
     */
    public function draftedInventory()
    {
        try {
            $credential = $this->getCredentails();

            $client = $this->createGoogleClient($credential->toArray());
            $client->setAccessToken($credential->token);

            $blogger = new Google_Service_Blogger($client);

            $params['maxResults'] = 500;
            $params['status'] = 'draft';
            $blogs = $blogger->posts->listPosts($credential->blog_id, $params);

            // Array to store blogs
            $blogData = [];

            // Loop through blogs
            while ($blogs->valid()) {
                $blog = $blogs->next();
                // Store blog details in array
                $blogData[] = [
                    'id' => 'data',
                ];
            }

            // Return the array or do further processing
            return $blogData;
        } catch (\Exception $e) {
            return [];
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
    public function getCredentails($scope = 'Blogger')
    {
        return  GoogleCredentail::where('scope', $scope)->first();
    }

    /**
     * Process Image
     *
     * @param Request $request
     * @return string
     */
    public function processImage($image)
    {
        $background = (new ImageManager())->canvas(555, 555, '#ffffff');

        $background->insert(Image::make($image)->resize(380, 520), 'center');

        $outputFileName = 'images/merged_image_' . $image->getClientOriginalName() . time() . '.' . $image->getClientOriginalExtension();

        $background->save(public_path($outputFileName));

        return config('app.url') . '/public/' . $outputFileName;
    }

    /**
     * Process Image
     *
     * @param Request $request
     * @return void
     */
    public function processImageAndDownload()
    {
        if ($thirdPartyUrl = request()->input('url')) {
            $fileContents = Http::get($thirdPartyUrl)->body();

            // Set the appropriate headers for download
            $headers = [
                'Content-Type' => 'application/octet-stream',
                'Content-Disposition' => 'attachment; filename=' . request()->image . '',
            ];

            // Return the file contents with headers
            return response($fileContents, 200, $headers);
        }
    }

    /**
     * Process Image
     *
     * @param Request $request
     * @return void
     */
    public function downloadProcessedImage()
    {
        $zip = new ZipArchive;
        $zipFileName = 'download.zip';

        if ($zip->open(public_path($zipFileName), ZipArchive::CREATE) === true) {
            foreach (request()->multipleImages as $image) {
                $background = (new ImageManager())->canvas(555, 555, '#ffffff');

                $background->insert(Image::make($image)->resize(360, 520), 'center');

                $outputFileName = 'images/merged_image_' . $image->getClientOriginalName() . time() . '.' . $image->getClientOriginalExtension();

                $background->save(($outputFileName));

                $zip->addFile($outputFileName);
            }

            // Close the zip archive
            $zip->close();

            // Return the zip file as a downloadable response
            return response()->download(public_path($zipFileName))->deleteFileAfterSend(true);
        }
    }

    /**
     * Publish Drafted Blog
     *
     * @param int $postId
     * @return void
     */
    public function publish($postId)
    {
        try {
            $credential = $this->getCredentails();

            $client = new Google_Client();

            $client->setScopes('https://www.googleapis.com/auth/blogger');

            $client->setAccessToken(json_decode($credential->token)->access_token);

            $blogger = new Google_Service_Blogger($client);

            // Retrieve the existing post
            return $blogger->posts->publish($credential->blog_id, $postId);
        } catch (\Google_Service_Exception $e) {
            // Log the error details for debugging
            \Log::error('Blogger API Error: ' . $e->getMessage());

            // Handle the error as needed
            return json_decode($e->getMessage());
        }
    }

    /**
     * Take backup of data to Database
     *
     * @param string $status
     * @param integer $startIndex
     * @return void
     */
    public function backupToDatabse($startIndex = 1, $status = 'live')
    {
        try {
            $posts = [];
            $pageToken = null;
            $perPage = 150;
            $startIndex = $startIndex;

            $params = [
                'orderBy' => 'updated',
                'status' => $status,
                'pageToken' => $pageToken,
                'alt' => 'json',
            ];

            if (App::runningInConsole()) {
                $params['start-index'] = $startIndex;
                $params['max-results'] = $perPage;

                if (SiteSetting::first()->url) {
                    $response = Http::get(SiteSetting::first()->url . '/feeds/posts/default', $params);
                    $response = json_decode(json_encode($response->json()))->feed;
                    $posts = $response->entry ?? [];
                    $filteredPost = $response->entry ?? [];

                    $allCategories = [];
                    if (isset(request()->category)) {
                        $filteredPost = [];
                        foreach ($posts as $key => $post) {
                            foreach ($post->category as $category) {
                                $allCategories[$key][] = $category->term;
                            }

                            if (in_array(request()->category, $allCategories[$key])) {
                                $filteredPost[$key] = (array) $post;
                                $filteredPost[$key]['category'] = $allCategories[$key];
                            }
                        }
                    }
                }
            }

            $nextPageToken = $response->nextPageToken ?? null;
            $prevPageToken = $response->prevPageToken ?? null;

            $paginator = new LengthAwarePaginator(
                json_decode(json_encode($filteredPost)),
                count($response->entry ?? []),
                1,
                count($response->entry ?? []),
                ['path' => route('inventory.index', ['startIndex' => 1, 'category' => 'Product'])]
            );

            return [
                'paginator' => $paginator,
                'nextPageToken' => $nextPageToken,
                'prevPageToken' => $prevPageToken,
                'startIndex' => $startIndex + $perPage,
                'prevStartIndex' => $startIndex - $perPage
            ];
        } catch (\Exception $e) {
            $paginator = new LengthAwarePaginator(
                [],
                count([]),
                1,
                count([]),
                ['path' => route('inventory.index', ['startIndex' => 1, 'category' => 'Product'])]
            );

            return [
                'paginator' => $paginator,
                'nextPageToken' => null,
                'prevPageToken' => null,
                'startIndex' => null,
                'prevStartIndex' => null
            ];
        }
    }
}
