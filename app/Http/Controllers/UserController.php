<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Mail\OtpMail;
use App\Mail\RegisterOtpMail;
use App\Mail\UserMail;
use App\Models\User;
use App\Models\UserListingCount;
use App\Models\UserSession;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{

    public function __invoke($file)
    {
        dd('ad');
        abort_if(auth()->guest(), Response::HTTP_FORBIDDEN);
    }
    /**
     * Initiate the class instance
     *
     * @return void
     */
    function __construct()
    {
        $this->middleware('role_or_permission:User Details (Main Menu)|User create|User Details -> All Users List -> Edit|User delete', ['only' => ['index', 'show']]);
        $this->middleware('role_or_permission:User create', ['only' => ['create', 'store']]);
        $this->middleware('role_or_permission:User Details -> All Users List -> Edit', ['only' => ['edit', 'update']]);
        $this->middleware('role_or_permission:User delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::orderBy('id', 'asc')->paginate(10);

        $allUser = User::count();

        $active = User::where('status', 1)->count();

        $inactive = User::where('status', 0)->count();

        return view('accounts.users.index', compact('users', 'allUser', 'active', 'inactive'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::get();

        return view('accounts.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        User::create($request->validated())?->syncRoles(request()->input('roles'));

        Mail::to($request->email)->send(new UserMail($request->all()));

        session()->flash('success', __('User created successfully.'));

        return redirect()->route('users.index');
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

    public function registerOTP()
    {
        $otp = $this->generateOTP(6);

        session()->flash('otp', $otp);

        Mail::to('abhishek86478@gmail.com')->send(new RegisterOtpMail($otp, request()->name));

        session()->flash('success', __('OTP Send to Admin'));

        return view('auth.enter-otp', ['register' => true]);
    }

    public function register()
    {
        if (session('otp') == request()->otp) {
            User::create(request()->all())?->syncRoles(request()->input('roles'));

            session()->flash('success', __('You have Registered Successfully...'));

            return redirect()->route('login');
        } else {
            session()->flash('success', __('Invalid OTP'));

            return view('auth.enter-otp', ['register' => true]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): \Illuminate\View\View
    {
        $user = User::find($id);

        $roles = Role::get();

        return view('accounts.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, string $id)
    {
        $user = User::find($id);

        $user->update($request->validated());

        if (request()->input('roles')) {
            $user->syncRoles(request()->input('roles'));
        }

        session()->flash('success', __('User updated successfully.'));

        return redirect()->route('users.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);

        $user?->delete();

        session()->flash('success', __('User delete successfully.'));

        return redirect()->route('users.index');
    }

    /**
     * Get verified Users
     *
     * @return void
     */
    public function verified()
    {
        $users = User::orderBy('id', 'asc')
            ->paginate(10);

        return view('accounts.users.approved', compact('users'));
    }

    /**
     * Update the Password
     *
     * @return void
     */
    public function updatePassword()
    {
        try {
            if (!request()->current) {
                return view('accounts.users.change-password');
            }

            $this->validate(request(), [
                'current' => 'required',
                'new'     => 'required',
            ]);

            $hashedPassword = Hash::make(request()->new);

            $user = Auth::user();

            // Check if the entered password matches the hashed password
            if (Hash::check(request()->current, $user->password)) {
                $user->update([
                    'password' => $hashedPassword,
                    'plain_password' => request()->new
                ]);

                session()->flash('success', __('Password updated successfully'));

                return redirect()->route('dashboard');
            }
            session()->flash('success', __('Please check your current password'));

            return redirect()->back();
        } catch (\Exception $e) {
            session()->flash('success', __('An error occurred on the server. Please try again later'));

            return redirect()->back();
        }
    }

    /**
     * Edit Status
     *
     * @return void
     */
    public function editStatus($id)
    {
        $roles = Role::get();

        $user = User::find($id);

        return view('accounts.users.update', compact('roles', 'user'));
    }

    /**
     * Update Status
     *
     * @return void
     */
    public function updateStatus($id)
    {
        $user = User::find($id);

        $user->update(request()->all());

        $user->syncRoles(request()->input('roles'));

        session()->flash('success', __('User updated successfully.'));

        return redirect()->route('verified.users');
    }

    /**
     * Set Session
     *
     * @return void
     */
    public function setSessionId()
    {
        $sessionId = UserSession::where('user_id', auth()->user()->id)
            ->orderBy('id', 'DESC')
            ->first();

        $currentDateTime = Carbon::now();
        $sessionExpireDateTime = $currentDateTime->addMinutes(env('SESSION_LIFETIME'));

        $sessionId->update([
            'expire_at' => $sessionExpireDateTime
        ]);

        session()->put('session_id', $sessionId->session_id);

        return true;
    }

    /**
     * Sessions Delete
     */
    public function deleteSessionId($id)
    {
        UserSession::where('session_id', $id)->delete();

        if (request()->ajax()) {
            return response()->json([
                'status' => true
            ]);
        }

        session()->flash('success', 'Session Deleted Succesfully');

        return redirect()->back();
    }

    public function userCounts()
    {
        // Query for 'Created' status
        $countCreated = UserListingCount::with('user')
            ->where('status', 'Created');

        if (request()->has('start_date') && request()->has('end_date')) {
            $startDate = Carbon::parse(request()->start_date);
            $endDate = Carbon::parse(request()->end_date);

            $countCreated->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()]);
        }

        if (request()->user_id) {
            $countCreated->where('user_id', request()->user_id);
        }

        if (!auth()->user()->hasRole('Super Admin')) {
            $countCreated->where('user_id', auth()->user()->id);
        }

        $countCreated = $countCreated->get();

        // Query for 'Edited' status
        $countEdited = UserListingCount::with('user')
            ->where('status', 'Edited');

        if (request()->has('start_date') && request()->has('end_date')) {
            $startDate = Carbon::parse(request()->start_date);
            $endDate = Carbon::parse(request()->end_date);

            $countEdited->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()]);
        }

        if (request()->user_id) {
            $countEdited->where('user_id', request()->user_id);
        }

        if (!auth()->user()->hasRole('Super Admin')) {
            $countEdited->where('user_id', auth()->user()->id);
        }

        $countEdited = $countEdited->get();

        // Get all users
        $users = User::all();

        // Pass data to the view
        return view('counts', compact('countCreated', 'countEdited', 'users'));
    }
}
