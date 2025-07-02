<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeveloperRequest;
use App\Mail\UserMail;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Mail;
use Str;

class DeveloperController extends Controller
{
    /**
     * Initiate the class instance
     *
     * @return void
     */
    function __construct()
    {
        $this->middleware('role_or_permission:User Details (Main Menu)|User create|User Details -> All Users List -> Edit|User delete', ['only' => ['index', 'show']]);
        $this->middleware('role_or_permission:User New create', ['only' => ['create', 'store']]);
        $this->middleware('role_or_permission:User Details -> All Users List -> Edit', ['only' => ['edit', 'update']]);
        $this->middleware('role_or_permission:User delete', ['only' => ['destroy']]);
        $this->middleware('role_or_permission:Allot User Roles', ['only' => ['verified']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::whereHas('roles', function($q){
            $q->where('name', 'Developer');
        })
        ->orderBy('id', 'asc')
        ->paginate(request()->users);
        return view('accounts.users.developers.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::get();

        return view('accounts.users.developers.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DeveloperRequest $request)
    {
        $user = User::create($request->validated());

        $user->assignRole('Developer'); // Assign Developer role directly
        $user->api_key = Str::random(20);
        $user->save();
        // Mail::to($request->email)->send(new UserMail($request->all()));

        session()->flash('success', __('Developer created successfully.'));

        return redirect()->route('developers.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): \Illuminate\View\View
    {
        $user = User::find($id);

        $roles = Role::get();

        return view('accounts.users.developers.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(string $id)
    {
        $user = User::find($id);
        $data = request()->except('disable_api_key');
        if(request()->filled('disable_api_key')){
            $user->api_key = null;
            $user->save();
        }
        $user->update($data);

        if (request()->input('roles')) {
            $user->syncRoles(request()->input('roles'));
        }

        session()->flash('success', __('User updated successfully.'));
        return redirect()->route('developers.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);

        $user?->delete();

        session()->flash('success', __('User delete successfully.'));

        return redirect()->route('developers.index');
    }

    public function keyRegenerate(User $user)
    {
        $user->api_key = Str::random(20);
        $user->save();
        session()->flash('success', __('Api key regenerate successfully'));

        return redirect()->route('developers.index');
    }
}
