<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use App\Models\User;
use App\Providers\RouteServiceProvider;
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
            $otp = $this->generateOTP(6);

            $user = User::where('email', request()->email)->first();
            $user->update(['otp' => $otp]);

            $userAgent = request()->header('User-Agent');

            if (!$user->verify_browser) $user->update(['verify_browser' => $userAgent]);

            if ($user->verify_browser != $userAgent && $user->verify_browser) {
                session()->flash('success', 'Please contact Admin for OTP');

                $email = 'admin@example.com';
            } else {
                session()->flash('success', 'Please check your registered email for OTP');

                $email = request()->email;
            }

            Mail::to($email)->send(new OtpMail($otp));

            return view('auth.enter-otp');
        } catch (\Exception $e) {
            session()->flash('error', 'Something went wrong please contact Admin');
            \Log::error('Error: ' . $e->getMessage());

            return redirect()->route('login');
        }
    }
}
