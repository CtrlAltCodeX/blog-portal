<?php

namespace App\Http\Controllers;

use App\Models\GoogleCredentail;
use App\Models\SiteSetting;

class SettingsController extends Controller
{
    /**
     * Initiate the class instance
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('role_or_permission:Configure Blog', ['only' => ['blog']]);
        $this->middleware('role_or_permission:Site Access', ['only' => ['site']]);
    }

    /**
     * Index function
     *
     * @return \Illuminate\View\View
     */
    public function blog()
    {
        $creds = GoogleCredentail::latest()->first();

        return view('settings.index', compact('creds'));
    }

    /**
     * Index function
     *
     * @return \Illuminate\View\View
     */
    public function site()
    {
        $siteSettings = SiteSetting::latest()->first();

        return view('settings.site', compact('siteSettings'));
    }

    /**
     * Update site settings
     *
     * @return void
     */
    public function update()
    {
        $siteSettings = SiteSetting::latest()->first();

        if (request()->file('logo')) {
            request()->file('logo')
                ->storePubliclyAs('public/logo.jpg');
        }

        if (request()->file('homepage_image')) {
            request()->file('homepage_image')
                ->storePubliclyAs('public/homepage_image.jpg');
        }

        if (request()->file('product_background_image')) {
            request()->file('product_background_image')
                ->storePubliclyAs('public/product_background_image.jpg');
        }

        $data = [
            'url' => request()->url,
            'logo' => 'logo.jpg',
            'homepage_image' => 'homepage_image.jpg',
            'product_background_image' => 'custom_image.jpg'
        ];

        if (!$siteSettings) SiteSetting::create($data);

        if ($siteSettings) $siteSettings->update($data);

        session()->flash('success', 'Settings updated successfully');

        return redirect()->back();
    }
}
