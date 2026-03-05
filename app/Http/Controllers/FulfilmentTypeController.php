<?php

namespace App\Http\Controllers;

use App\Models\FulfilmentType;
use Illuminate\Http\Request;

class FulfilmentTypeController extends Controller
{
    public function index()
    {
        $fulfilmentTypes = FulfilmentType::all();
        return view('fulfilment.index', compact('fulfilmentTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'difference_amount' => 'required|numeric',
        ]);

        FulfilmentType::create($request->all());

        return redirect()->back()->with('success', 'Fulfilment type added successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'difference_amount' => 'required|numeric',
        ]);

        $type = FulfilmentType::findOrFail($id);
        $type->update($request->all());

        return redirect()->back()->with('success', 'Fulfilment type updated successfully.');
    }

    public function destroy($id)
    {
        $type = FulfilmentType::findOrFail($id);
        $type->delete();

        return redirect()->back()->with('success', 'Fulfilment type deleted successfully.');
    }
}
