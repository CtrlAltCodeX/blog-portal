<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Mail\UserMail;
use App\Models\User;
use App\Models\UserSession;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
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

        session()->flash('success', __('Session Deleted successfully.'));

        return redirect()->back();
    }
}
