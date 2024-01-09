<?php

namespace App\Http\Controllers;

use App\Models\GoogleCredentail;
use App\Models\SiteSetting;

class SettingsController extends Controller
{
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
            request()->file('logo')->storePubliclyAs('public/logo.jpg');
        }

        $data = [
            'url' => request()->url,
            'logo' => 'logo.jpg'
        ];

        if (!$siteSettings) SiteSetting::create($data);

        if ($siteSettings) $siteSettings->update($data);

        session()->flash('success', 'Settings updated successfully');

        return redirect()->back();
    }
}
