<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\CityCost;
use App\Models\MarketplaceCommission;
use App\Models\FulfilmentType;

class MarketPlaceController extends Controller
{
    public function index()
    {
        $cityCosts = CityCost::all();
        $commissions = MarketplaceCommission::all();
        $fulfilmentTypes = FulfilmentType::all();

        return view('marketplace.calculation', compact('cityCosts', 'commissions', 'fulfilmentTypes'));
    }

    public function calculate(Request $request)
    {
        $mrp = (float)$request->mrp;
        $discountPer = (float)$request->discount;
        $transportationPer = (float)$request->transportation;
        $packagingCost = (float)$request->packaging_cost;
        $courierCharges = (float)$request->courier_charges;
        $preDefinedShipping = (float)$request->pre_defined_shipping;
        
        // Stage 1: Basic Costing
        $purchasePrice = $mrp - ($mrp * ($discountPer / 100)) - ($mrp * ($transportationPer / 100));
        $netCost = $purchasePrice + $packagingCost + $courierCharges;

        // Marketplace Commission Slab Logic
        $commission = 0;
        $slab = MarketplaceCommission::where('min_range', '<=', $netCost)
            ->where(function($query) use ($netCost) {
                $query->where('max_range', '>=', $netCost)
                      ->orWhereNull('max_range');
            })
            ->first();

        if ($slab) {
            $minC = (float)$slab->min_commission;
            $maxC = (float)$slab->max_commission;
            $minR = (float)$slab->min_range;
            $maxR = (float)$slab->max_range;

            if ($maxR > $minR && $netCost > $minR) {
                $commission = $minC + (($netCost - $minR) / ($maxR - $minR)) * ($maxC - $minC);
            } else {
                $commission = $minC;
            }
        }

        $minProfitPer = 2; // Fixed 2% profit as per requirement
        $finalCosting = $netCost + $commission + (($netCost + $commission) * ($minProfitPer / 100));
        $finalCostingRounded = ceil($finalCosting);

        // Stage 2
        $minPrice1 = $finalCostingRounded;
        $minPrice2 = $finalCostingRounded - $preDefinedShipping;

        // Competitor Logic (Passed from frontend or calculated here)
        $sellerPrice = (float)$request->seller_price;
        $sellerShipping = (float)$request->seller_shipping;
        $fulfilmentId = $request->fulfilment_id;
        $fulfilmentType = FulfilmentType::find($fulfilmentId);
        $fulfilmentDiff = $fulfilmentType ? (float)$fulfilmentType->difference_amount : 0;

        $competitorPrice = ($sellerPrice + $sellerShipping) - $fulfilmentDiff;

        $yourProductPrice = 0;
        if ($competitorPrice < $minPrice1) {
            $yourProductPrice = $minPrice2;
        } else {
            $yourProductPrice = $competitorPrice - $preDefinedShipping;
        }

        return response()->json([
            'purchase_price' => round($purchasePrice, 2),
            'net_cost' => round($netCost, 2),
            'commission' => round($commission, 2),
            'final_costing' => round($finalCosting, 2),
            'final_costing_rounded' => $finalCostingRounded,
            'min_price_1' => $minPrice1,
            'min_price_2' => $minPrice2,
            'competitor_price' => round($competitorPrice, 2),
            'your_product_price' => round($yourProductPrice, 2),
            'your_shipping_set' => round($preDefinedShipping, 2),
        ]);
    }
}
