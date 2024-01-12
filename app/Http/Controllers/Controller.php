<?php

namespace App\Http\Controllers;

use App\Models\SiteSetting;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Carbon\Carbon;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * If Token is Expired
     *
     * @return void
     */
    public function tokenIsExpired($googleService)
    {
        $credential = $googleService->getCredentails();

        $data = json_decode($credential->token);
        $updatedTime = new Carbon($credential->updated_at);
        $currentTime = Carbon::now();
        $expirationDateTime = $updatedTime->addSeconds($data->expires_in);

        if ($currentTime->greaterThanOrEqualTo($expirationDateTime)) return true;
    }

    /**
     * Get Site Base URL
     *
     * @return void
     */
    public function getSiteBaseUrl()
    {
        $siteSetting = SiteSetting::first();

        if($siteSetting) return $siteSetting->url;
    }
}
