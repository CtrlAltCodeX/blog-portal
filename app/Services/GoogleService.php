<?php

namespace App\Services;

use Google_Client;
use Google_Service_Blogger;
use Google_Service_Blogger_Post;
use App\Models\GoogleCredentail;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
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
    public function posts($status = 'live')
    {
        try {
            $credential = $this->getCredentails();

            $client = $this->createGoogleClient($credential->toArray());
            $client->setAccessToken($credential->token);

            $blogger = new Google_Service_Blogger($client);

            $posts = [];
            $pageToken = null;
            $perPage = 250;
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

                if (request()->query('updated_before') == 1) {
                    $agoDate = $currentDate->subYear();
                } else {
                    // Subtract three months from the current date
                    $agoDate = $currentDate->subMonths(request()->query('updated_before'));
                }
            }

            if (
                request()->route()->getName() == 'inventory.index'
                || request()->route()->getName() == 'inventory.review'
            ) {
                $params['start-index'] = $startIndex;
                $params['max-results'] = $perPage;
                $params['category'] = request()->query('category');

                if (request()->route()->getName() == 'inventory.review') $params['updated-max'] = $agoDate->format('Y-m-d') . "T00:00:00";

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
            } else if (request()->route()->getName() == 'inventory.drafted') {
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
                ['path' => route('inventory.index')]
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
                ['path' => route('inventory.index')]
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

            $data['processed_images'][] = $data['images'];

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
            // $existingPost->setImages('Testing');

            return $blogger->posts->update($credential->blog_id, $postId, $existingPost);
        } catch (\Google_Service_Exception $e) {
            \Log::error('Blogger API Error: ' . $e->getMessage());

            return json_decode($e->getMessage());
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

                $background->insert(Image::make($image)->resize(380, 520), 'center');

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
}
