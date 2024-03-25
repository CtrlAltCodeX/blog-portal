<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use App\Models\User;
use App\Models\UserSession;
use App\Providers\RouteServiceProvider;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        if (($user = auth()->user()->sessions->where('expire_at', '<', now())->first())
            || (auth()->user()->allow_sessions
                && $user = auth()->user()->sessions->first())
            || (!auth()->user()->allow_sessions
                && $user = auth()->user()->sessions->where('session_id', session()->get('session_id'))->first())
        ) {
            $user->delete();
        }

        $this->guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        if ($response = $this->loggedOut($request)) {
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect('/');
    }

    /**
     * Logout the user
     */
    protected function loggedOut(Request $request)
    {
        return redirect()->route('login');
    }

    /**
     * Validate credentials
     */
    protected function credentials(Request $request)
    {
        return [
            'email'    => request()->email,
            'password' => request()->password,
            'status'   => 1
        ];
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        $user = User::where('email', request()->email)->first();

        // if (
        //     $user
        //     && $user->allow_sessions
        //     && $user->sessions->where('expire_at', '>', now())->first()
        // ) {
        //     session()->flash('error', 'You are already Logged in other Device');

        //     return redirect()->route('login');
        // } else if (
        //     $user
        //     && !$user->allow_sessions
        //     && $user = $user->sessions->where('expire_at', '<', now())->first()
        // ) {
        //     $user->delete();
        // }

        if (request()->otp) {
            $verifyOtp = User::where('email', request()->email)
                ->where('otp', request()->otp)
                ->first();

            if (!$verifyOtp) {
                session()->flash('success', 'Invalid OTP');

                return view('auth.enter-otp');
            }
        }

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (
            method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)
        ) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            if ($request->hasSession()) {
                $request->session()->put('auth.password_confirmed_at', time());
            }

            $user = User::where('email', request()->email)->first();

            $userAgent = request()->header('User-Agent');

            if (!$user->verify_browser) $user->update(['verify_browser' => $userAgent]);

            $currentDateTime = Carbon::now();
            $sessionExpireDateTime = $currentDateTime->addMinutes(env('SESSION_LIFETIME'));
            $sessionId = session()->getId();
            session()->put('sessionId', $sessionId);

            if ($user->allow_sessions) {
                if ($userSession = UserSession::where('user_id', $user->id)->first()) {
                    $userSession->update([
                        'user_id' => $user->id,
                        'session_id' => $sessionId,
                        'expire_at' => $sessionExpireDateTime
                    ]);
                } else {
                    UserSession::create([
                        'user_id' => $user->id,
                        'session_id' => $sessionId,
                        'expire_at' => $sessionExpireDateTime
                    ]);
                }
            } else if (!$user->allow_sessions) {
                $data = [
                    'user_id' => $user->id,
                    'session_id' => $sessionId,
                    'expire_at' => $sessionExpireDateTime
                ];

                UserSession::create($data);
            }

            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Generate Random Digits
     *
     * @return int
     */
    public function generateOTP($n)
    {
        $generator = "1357902468";
        $result = "";

        for ($i = 1; $i <= $n; $i++) {
            $result .= substr($generator, rand() % strlen($generator), 1);
        }

        // Returning the result
        return $result;
    }

    /**
     * Authenticate OTP
     *
     * @return void
     */
    public function authenticateOTP()
    {
        try {
            $user = User::where('email', request()->email)
                ->where('plain_password', request()->password)
                ->first();

            if ($user->allow_sessions) {
                $sessionExists = UserSession::where('user_id', $user->id)
                    ->first();

                if ($sessionExists) {
                    session()->flash('error', 'Session already Running wants to <a id="continue" href="" >Continue</a>');
                    session()->put('session_id', $sessionExists->session_id);
                    session()->put('session_email', request()->email);
                    session()->put('session_password', request()->password);

                    return redirect()->route('login');
                }
            }

            if ($user->allow_sessions && $user->sessions->where('expire_at', '>', now())->first()) {
                session()->flash('error', 'You are already LoggedIn in other Device');

                return redirect()->route('login');
            }

            if (!$user) {
                session()->flash('error', 'Opps! Email Or Password Mismatch');

                return redirect()->route('login');
            }

            if (!$user->status) {
                session()->flash('error', 'Oops!!!! your account is not active');

                return redirect()->back();
            }

            $otp = $this->generateOTP(6);

            $user->update(['otp' => $otp]);

            $userAgent = request()->header('User-Agent');

            if ((($user->verify_browser != $userAgent) && $user->verify_browser) || !$user->verify_browser) {
                session()->flash('success', 'Please get OTP From System Admin');

                $loginUser = $user->name;

                $adminUsers = User::role('admin')->get();

                foreach ($adminUsers as $user) {
                    $email = $user->email;

                    Mail::to($email)->send(new OtpMail($otp, $msg = true, $loginUser));
                }
            } else {
                session()->flash('success', 'Please Check the OTP in Registered Email');

                $email = request()->email;
                $name = request()->name;

                Mail::to($email)->send(new OtpMail($otp, null, $name));
            }

            return view('auth.enter-otp');
        } catch (\Exception $e) {
            session()->flash('error', 'Something went wrong please contact Admin');
            \Log::error('Error: ' . $e->getMessage());

            return redirect()->route('login');
        }
    }
}
