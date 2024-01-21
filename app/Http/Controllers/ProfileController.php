<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ProfileRequest;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManagerStatic as Image;

class ProfileController extends Controller
{
    /**
     * Edit the profile
     */
    public function edit()
    {
        $user = auth()->user();
        // dd(asset($user->profile));

        return view('profile.edit', compact('user'));
    }

    public function update(ProfileRequest $request)
    {
        $user = auth()->user();

        if (
            $request->filled('current_password')
            && ! Hash::check($request->current_password, $user->password)
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
            
            $validated['profile'] = config('app.url') . '/public/' . $outputFileName;
        }

        if ($request->filled('current_password')) {
            $validated['password'] = Hash::make($request->password);
        }

        $user->update($validated);

        session()->flash('success', __('Profile updated successfully.'));

        return redirect()->route('profile.edit');
    }
}
