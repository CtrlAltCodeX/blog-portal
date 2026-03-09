<?php

namespace App\Http\Controllers;

use App\Models\MarketPlaceCalculationSetting;
use App\Imports\MarketPlaceCalculationSettingImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MarketPlaceCalculationSettingsController extends Controller
{
    public function index()
    {
        $settings = MarketPlaceCalculationSetting::all();
        return view('marketplace.settings', compact('settings'));
    }

    public function upload(Request $request)
    {
        if ($request->hasFile('settings_file')) {
            $file = $request->file('settings_file');
            $fileName = "site/marketplace_calculation_settings.xlsx";
            $file->storeAs("/public/" . $fileName);

            MarketPlaceCalculationSetting::truncate();
            Excel::import(new MarketPlaceCalculationSettingImport, $file);

            return redirect()->back()->with('success', 'Marketplace calculation settings updated successfully.');
        }

        return redirect()->back()->with('error', 'Please upload a valid Excel file.');
    }

    public function downloadSample()
    {
        $filePath = public_path('storage/site/purches_price_weight.xlsx');
        if (file_exists($filePath)) {
            return response()->download($filePath);
        }
        return redirect()->back()->with('error', 'Sample file not found.');
    }
}
