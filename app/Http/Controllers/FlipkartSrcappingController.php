<?php

namespace App\Http\Controllers;

use Goutte;
use Illuminate\Support\Facades\Http;

class FlipkartSrcappingController extends Controller
{
    /**
     * Find Products 
     *
     * @return void
     */
    public function find()
    {
        return view('flipkart.find');
    }

    /**
     * Find Products from Amazon
     *
     * @param int $id
     * @return void
     */
    public function findProducts()
    {
        try {
            $keyword = request()->search;

            if (strpos($keyword, "flipkart.com")) {
                $productCrawler = Goutte::request('GET', $keyword);
            } else {
                $crawler = Goutte::request('GET', 'https://www.flipkart.com/search?q=' . $keyword);

                $productUrl = $crawler->filter('a')->eq(7)->attr('href');

                $productCrawler = Goutte::request('GET', 'https://www.flipkart.com' . $productUrl);
            }

            $title = $productCrawler->filter('.B_NuCI')->html();
            // $baseImage = $productCrawler->filter('#landingImage')->attr('src');
            $desc = $productCrawler->filter('._1mXcCf')->eq(1)->html();
            $selling = $productCrawler->filter('._30jeq3')->html();
            $mrp = $productCrawler->filter('._3I9_wc')->html();

            $keys = $productCrawler->filter('._2418kt ul li')->each(function ($node) {
                return $node->html();
            });

            $specifications = [];

            foreach ($keys as $value) {
                $data = explode(": ", $value);
                $specifications[$data[0]] = $data[1];

                if ($data[0] == 'ISBN') {
                    $isbn = explode(", ", $data[1]);
                    $specifications[$data[0]] = $isbn;
                }
            }

            // $images = $productCrawler->filter('#altImages ul li')->each(function ($node) {
            //     return $node->filter('span > span')->html();
            // });

            $allDetails['title'] = str_replace(["<!-- -->", ","], "", $title);
            // $allDetails['baseImage'] = $baseImage;
            // $allDetails['image'] = $images;
            $allDetails['desc'] = $desc;
            $allDetails['selling'] = (int) str_replace(["₹", ","], "", $selling);
            $allDetails['mrp'] = (int) str_replace(["₹<!-- -->", ","], "", $mrp);;
            $allDetails['specifications'] = $specifications;

            return view('flipkart.show', compact('allDetails'));
        } catch (\Exception $e) {
            if ($e->getMessage()) {
                session()->flash('message', 'Currently Facing some issues. Please try again later');

                return view('settings.error');
            }
        }
    }

    /**
     * Store
     *
     * @return void
     */
    public function StoreFindProducts()
    {
        if (!$url = $this->getSiteBaseUrl()) {
            session()->flash('message', 'Please complete your Site Setting Then Continue');

            return view('settings.error');
        }

        $response = Http::get($url . '/feeds/posts/default?alt=json');

        $categories = $response->json()['feed']['category'];

        $product = json_decode(request()->details);

        return view('flipkart.create', compact('categories', 'product'));
    }
}
