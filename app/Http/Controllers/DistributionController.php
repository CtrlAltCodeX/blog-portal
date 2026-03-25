<?php

namespace App\Http\Controllers;

use App\Models\WeightVSCourier;

class DistributionController extends Controller
{
    public function index()
    {
        $weightVsCouriers = WeightVSCourier::whereNotNull('logo_url')->get();

        return view('distribution.index', compact('weightVsCouriers'));
    }
}
