<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use App\Http\Requests\ProfileRequest;
use App\Models\Listing;
use App\Models\User;
use App\Models\UserListingCount;
use App\Models\UserListingInfo;
use Carbon\Carbon;
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
        $userListings = UserListingInfo::with('create_user', 'approve')
            ->orderBy('created_at', 'desc');

        if (request()->status == '0' || request()->status == 1 || request()->status == 2) {
            $userListings = $userListings->where('status', request()->status);
        }

        $approved = UserListingInfo::where('approved_by', '!=', '');

        $pending = UserListingInfo::where('status', 0);

        $rejected = UserListingInfo::where('status', 2);

        if (request()->from && request()->to) {
            $from = \DateTime::createFromFormat('d/m/Y', request()->from);
            $fromFormattedDate = $from->format('Y-m-d');

            $to = \DateTime::createFromFormat('d/m/Y', request()->to);
            $toFormattedDate = $to->format('Y-m-d');

            $userListings = $userListings->whereDate('created_at', ">=", $fromFormattedDate)
                ->whereDate('created_at', "<=", $toFormattedDate);

            $approved = $approved->whereDate('created_at', ">=", $fromFormattedDate)
                ->whereDate('created_at', "<=", $toFormattedDate);

            $pending = $pending->whereDate('created_at', ">=", $fromFormattedDate)
                ->whereDate('created_at', "<=", $toFormattedDate);

            $rejected = $rejected->whereDate('created_at', ">=", $fromFormattedDate)
                ->whereDate('created_at', "<=", $toFormattedDate);
        }

        if (auth()->user()->hasRole('Super Admin')) {
            if (request()->user != 'all') {
                $userListings = $userListings
                    ->where('created_by', request()->user)
                    ->get();
            } else {
                $userListings = $userListings->get();
            }

            $approved = $approved->count();

            $pending = $pending->count();

            $rejected = $rejected->count();
        } else {
            $userListings = $userListings
                ->where('created_by', auth()->user()->id)
                ->get();

            $approved = $approved
                ->where('created_by', auth()->user()->id)
                ->count();

            $pending = $pending
                ->where('created_by', auth()->user()->id)
                ->count();

            $rejected = $rejected
                ->where('created_by', auth()->user()->id)
                ->count();
        }

        $users = User::select('id', 'name')
            ->where('status', 1)
            ->get();

        return view('profile.listing', compact('userListings', 'pending', 'rejected', 'approved', 'users'));
    }

    public function delete()
    {
        UserListingInfo::whereIn('id', request()->formData[1])->delete();

        Listing::whereIn('id', request()->formData[1])->delete();

        return true;
    }

    public function singleDelete($id)
    {
        UserListingInfo::find($id)->delete();

        Listing::find($id)->delete();

        session()->flash('success', __('Deleted successfully.'));

        return redirect()->back();
    }
}
