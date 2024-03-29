<?php

namespace App\Http\Middleware;

use App\Models\UserSession;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckSessionValidity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user) {
            $session = UserSession::where('session_id', session()->get('sessionId'))
                ->where('user_id', $user->id)
                ->first();

            if (!$session) {
                Auth::logout();
                return redirect()->route('login')->with('error', 'Your session has expired. Please log in again.');
            }
        }

        return $next($request);
    }
}
