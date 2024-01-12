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
        try {
            $keyword = request()->search;

            if (strpos($keyword, "amazon.in")) {
                $productCrawler = Goutte::request('GET', $keyword);
            } else {
                $crawler = Goutte::request('GET', 'https://www.amazon.in/s?k=' . $keyword);


                $productUrl = $crawler
                    ->filter('.s-product-image-container > .rush-component a')
                    ->attr('href');

                $productCrawler = Goutte::request('GET', 'https://www.amazon.in' . $productUrl);
            }
            $title = $productCrawler->filter('#productTitle')->html();

            $baseImage = $productCrawler->filter('#landingImage')->attr('src');
            $desc = $productCrawler->filter('#pInfoTabsContainer span')->html();
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
