<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Auth;
use App\Models\User;

class AuthOtpCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(Auth::check())
        {
            $auth=Auth::user();
            $check_otp_user=User::where('email',$auth->email)->first();
            if($check_otp_user->otp_feature == 0)
            {
                // dd("ch");
            }
        } 
        return $next($request);
    }
}
