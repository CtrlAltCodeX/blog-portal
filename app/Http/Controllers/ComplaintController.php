<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\ComplaintUser;
use Illuminate\Http\Request;

class ComplaintController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $complaints = ComplaintUser::all();
        return view('complaints.index', compact('complaints'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('complaints.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string',
            'phone' => 'required',
        ]);

        // Create a new complaint
        $complaint = new ComplaintUser();
        $complaint->name = $validatedData['name'];
        $complaint->email = $validatedData['email'];
        $complaint->phone = $validatedData['phone'];
        $complaint->save();

        // Redirect to the complaints index page with a success message
        return redirect()->route('complaints-user.index')->with('success', 'Complaint User submitted successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $complaint = ComplaintUser::findOrFail($id);
        return view('complaints.edit', compact('complaint'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string',
            'phone' => 'required',
        ]);

        // Find existing complaint user
        $complaint = ComplaintUser::findOrFail($id);

        // Update values
        $complaint->name = $validatedData['name'];
        $complaint->email = $validatedData['email'];
        $complaint->phone = $validatedData['phone'];

        $complaint->save();

        return redirect()->route('complaints-user.index')
            ->with('success', 'Complaint User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        ComplaintUser::destroy($id);
        return redirect()->route('complaints-user.index')->with('success', 'Complaint User deleted successfully.');
    }
}
