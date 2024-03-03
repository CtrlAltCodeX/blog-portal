<?php

namespace App\Http\Controllers;

use App\Mail\BackupMail;
use App\Models\BackupEmail;
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
            $logoImage = time() . "_logo.jpg";
            request()->file('logo')
                ->storePubliclyAs('public/' . $logoImage);
        }

        if (request()->file('homepage_image')) {
            $fileName = time() . "_homepage_image.jpg";
            request()->file('homepage_image')
                ->storeAs("/public/" . $fileName);
        }

        if (request()->file('product_background_image')) {
            request()->file('product_background_image')
                ->storePubliclyAs('public/product_background_image.jpg');
        }

        $data = [
            'url' => request()->url,
            'logo' => $logoImage ?? $siteSettings->homepage_image,
            'homepage_image' => $fileName ?? $siteSettings->homepage_image,
            'product_background_image' => 'custom_image.jpg'
        ];

        if (!$siteSettings) SiteSetting::create($data);

        if ($siteSettings) $siteSettings->update($data);

        session()->flash('success', 'Settings updated successfully');

        return redirect()->back();
    }
}
