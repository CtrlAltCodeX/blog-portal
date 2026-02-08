<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OnDemandListing;
use Illuminate\Support\Facades\Storage;

class OnDemandListingController extends Controller
{
    public function index()
    {
        return view('on-demand.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'category' => 'required|in:Create,Update'
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('on-demand', 'public');
                
                OnDemandListing::create([
                    'requested_by' => auth()->id(),
                    'image' => $path,
                    'category' => $request->category,
                    'status' => 'Requested'
                ]);
            }
        }

        session()->flash('success', 'Images uploaded successfully.');
        return redirect()->back();
    }

    public function verify()
    {
        $requestedCreate = OnDemandListing::with('requestedBy')
            ->where('category', 'Create')
            ->where('status', 'Requested')
            ->get();

        $requestedUpdate = OnDemandListing::with('requestedBy')
            ->where('category', 'Update')
            ->where('status', 'Requested')
            ->get();

        $completed = OnDemandListing::with(['requestedBy', 'completedBy'])
            ->where('status', 'Completed')
            ->get();

        return view('on-demand.verify', compact('requestedCreate', 'requestedUpdate', 'completed'));
    }

    public function complete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:on_demand_listings,id'
        ]);

        OnDemandListing::whereIn('id', $request->ids)->update([
            'status' => 'Completed',
            'completed_by' => auth()->id(),
            'completed_at' => now()
        ]);

        return response()->json(['success' => true]);
    }

    public function uncomplete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:on_demand_listings,id'
        ]);

        OnDemandListing::whereIn('id', $request->ids)->update([
            'status' => 'Requested',
            'completed_by' => null,
            'completed_at' => null
        ]);

        return response()->json(['success' => true]);
    }
}
