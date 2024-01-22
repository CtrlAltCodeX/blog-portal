<?php

namespace App\Http\Controllers;

use Goutte;
use Illuminate\Support\Facades\Http;

class AmazonSrcappingController extends Controller
{
    /**
     * Find Products 
     *
     * @return void
     */
    public function find()
    {
        return view('find-products.find');
    }

    /**
     * Find Products from Amazon
     *
     * @param int $id
     * @return void
     */
    public function findProducts()
    {
        $keyword = request()->search;

        if (strpos($keyword, "amazon.in")) {
            $productCrawler = Goutte::request('GET', $keyword);

            return $this->findAmazonProducts($productCrawler);
        } else if (strpos($keyword, "flipkart.com")) {
            $productCrawler = Goutte::request('GET', $keyword);

            return $this->findFlipkartProducts($productCrawler);
        }
    }

    /**
     * Find Products from Amazon
     *
     * @param Object $productCrawler
     * @return void
     */
    public function findAmazonProducts($productCrawler)
    {
        try {
            $title = $productCrawler->filter('#productTitle')->html();

            $baseImage = $productCrawler->filter('#landingImage')->attr('src');
            $desc = $productCrawler->filter('.a-expander-content span')->html();
            $selling = $productCrawler->filter('#price')->html();
            $mrp = $productCrawler->filter('#listPrice')->html();
            // $desc = $productCrawler->filter('#bookDescription_feature_div')->html();

            $keys = $productCrawler->filter('#detailBullets_feature_div ul li')->each(function ($node) {
                $data = $node->filter('.a-text-bold')->each(function ($innerNode) {
                    $trimedHtml = trim($innerNode->html());
                    $specifications = preg_replace('/[^\p{L}\p{N}\s]/u', '', $trimedHtml);

                    return $specifications;
                });

                return trim($data[0] ?? 0);
            });

            $specificationsValues = $productCrawler->filter('#detailBullets_feature_div ul li')->each(function ($node) {
                $data = $node->filter('.a-list-item > span')->eq(1)->each(function ($innerNode) {
                    return $innerNode->html();
                });

                return $data[0] ?? 0;
            });

            $specifications = [];

            foreach ($keys as $index => $key) {
                $specifications[$key] = $specificationsValues[$index];

                if ($key == 'Paperback') {
                    $specifications[$key] = (int) str_replace([" pages", ""], "", $specificationsValues[$index]);
                } else if ($key == 'Country of Origin') {
                    $specifications[str_replace([" ", "_"], "_", $key)] = $specificationsValues[$index];
                }
            }

            $images = $productCrawler->filter('#altImages ul li')->each(function ($node) {
                return $node->filter('span > span')->html();
            });

            $allDetails['title'] = $title;
            $allDetails['baseImage'] = $baseImage;
            $allDetails['image'] = $images;
            $allDetails['desc'] = $desc;
            $allDetails['selling'] = (int) str_replace(["₹", ","], "", $selling);
            $allDetails['mrp'] = (int) str_replace(["₹", ","], "", $mrp);;
            $allDetails['specifications'] = $specifications;

            return view('find-products.show', compact('allDetails'));
        } catch (\Exception $e) {
            if ($e->getMessage()) {
                session()->flash('message', 'Currently Facing some issues. Please try again later');

                return view('settings.error');
            }
        }
    }

    /**
     * Find Products from Flipkart
     *
     * @return void
     */
    public function findFlipkartProducts($productCrawler)
    {
        try {
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

            return view('find-products.show', compact('allDetails'));
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

        return view('find-products.create', compact('categories', 'product'));
    }
}
