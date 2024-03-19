<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use App\Http\Requests\ProfileRequest;
use App\Models\UserListingCount;
use App\Models\UserListingInfo;
use Intervention\Image\ImageManager;
use Intervention\Image\ImageManagerStatic as Image;

class ProfileController extends Controller
{
    /**
     * Edit the profile
     */
    public function edit()
    {
        $user = auth()->user();

        $userCounts = UserListingCount::where('user_id', auth()->user()->id)
            ->whereDate('date', date("Y-m-d"))
            ->first();

        return view('profile.edit', compact('user', 'userCounts'));
    }

    /**
     * Update Profile
     *
     * @param ProfileRequest $request
     * @return void
     */
    public function update(ProfileRequest $request)
    {
        $user = auth()->user();

        if (
            $request->filled('current_password')
            && !Hash::check($request->current_password, $user->password)
        ) {
            return redirect()->back()->withErrors(['password' => 'The current password is incorrect'])->withInput();
        }

        $validated = $request->validated();

        if ($request->hasFile('profile')) {
            $image = $request->file('profile');

            $background = (new ImageManager())->canvas(555, 555, '#ffffff');

            $background->insert(Image::make($image), 'center');

            $outputFileName = 'profiles_' . $image->getClientOriginalName() . time() . '.' . $image->getClientOriginalExtension();

            $background->save(public_path($outputFileName));

            $validated['profile'] = config('app.url') . $outputFileName;
        }

        if ($request->filled('current_password')) {
            $validated['password'] = Hash::make($request->password);
        }

        $user->update($validated);

        session()->flash('success', __('Profile updated successfully.'));

        return redirect()->route('profile.edit');
    }

    /**
     * Listing
     *
     * @return void
     */
    public function listings()
    {
        if (auth()->user()->hasRole('Admin')) {
            $userListings = UserListingInfo::with('create_user', 'approve')
                ->orderBy('created_at', 'desc')
                ->get();

            return view('profile.listing', compact('userListings'));
        } else {
            $userListings = UserListingInfo::where('created_by', auth()->user()->id)
                ->orderBy('created_at', 'desc')
                ->with('create_user', 'approve')
                ->get();

            return view('profile.listing', compact('userListings'));
        }
    }
}
