<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Goutte;

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

        $crawler = Goutte::request('GET', 'https://www.amazon.in/s?k=' . $keyword);

        $productUrl = $crawler->filter('.s-product-image-container > .rush-component a')->attr('href');

        $productCrawler = Goutte::request('GET', 'https://www.amazon.in' . $productUrl);

        $title = $productCrawler->filter('#productTitle')->html();
dd($title);
        // $moreDetails = $productCrawler->filter('#detailBullets_feature_div ul li')->each(function ($node) {
        //     echo $node->html();
        // });
    }
}
