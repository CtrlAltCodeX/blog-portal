<?php

namespace App\Http\Controllers;

use App\Models\FieldValidation;
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
        $this->middleware('role_or_permission:Settings -> Configure Blog', ['only' => ['blog']]);
        $this->middleware('role_or_permission:Settings -> Site Access', ['only' => ['site']]);
    }

    /**
     * Index function
     *
     * @return \Illuminate\View\View
     */
    public function blog()
    {
        $bloggerCreds = GoogleCredentail::where('scope', 'Blogger')->first();

        $merchantCreds = GoogleCredentail::where('scope', 'Merchant')->first();

        return view('settings.index', compact('bloggerCreds', 'merchantCreds'));
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
            'logo' => $logoImage ?? $siteSettings->logo ?? '',
            'homepage_image' => $fileName ?? $siteSettings->homepage_image ?? '',
            'product_background_image' => 'custom_image.jpg',
            'watermark_text' => request()->watermark_text
        ];

        if (!$siteSettings) SiteSetting::create($data);

        if ($siteSettings) $siteSettings->update($data);

        session()->flash('success', 'Settings updated successfully');

        return redirect()->back();
    }

    /**
     * Fields Validation View
     */
    public function fieldsValidations()
    {
        $notAllowedNames = FieldValidation::where('name', "!=", '')
            ->where('status', 1)
            ->get();

        $notAllowedlinks = FieldValidation::where('links', "!=", '')
            ->where('status', 1)
            ->get();

        return view('settings.fields-validations', compact('notAllowedNames', 'notAllowedlinks'));
    }

    /**
     * Save Keywords and Links 
     */
    public function keywordsNotAllowed()
    {
        if (request()->name) {
            FieldValidation::create([
                'name' => request()->name,
                'status' => 1
            ]);
        } else if (request()->link) {
            FieldValidation::create([
                'links' => request()->link,
                'allowed' => request()->allow ? 1 : 0,
                'status' => 1
            ]);
        }

        session()->flash('success', 'Updated successfully');

        return redirect()->back();
    }

    /**
     * Update Keywords 
     */
    public function updateKeywords($id)
    {
        $row = FieldValidation::find($id);

        $row->update([
            'name' => request()->name ?? '',
            'links' => request()->link,
        ]);

        session()->flash('success', 'Updated successfully');

        return redirect()->back();
    }

    /**
     * Delete Keywords and Links 
     */
    public function keywordsDelete($id)
    {
        FieldValidation::find($id)->delete();

        session()->flash('success', 'Deleted successfully');

        return redirect()->back();
    }

    /**
     * Validate Fields
     */
    public function fieldsValidate()
    {
        return FieldValidation::select('name', 'links', 'allowed')->get();
    }
}
