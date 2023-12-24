<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Initiate the class instance
     *
     * @return void
     */
    function __construct()
    {
        $this->middleware('role_or_permission:User access|User create|User edit|User delete', ['only' => ['index', 'show']]);
        $this->middleware('role_or_permission:User create', ['only' => ['create', 'store']]);
        $this->middleware('role_or_permission:User edit', ['only' => ['edit', 'update']]);
        $this->middleware('role_or_permission:User delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::orderBy('id', 'asc')->paginate(10);

        return view('accounts.users.index', compact('users'));
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

        $user->syncRoles(request()->input('roles'));

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

        return redirect()->route('users.index');
    }
}
