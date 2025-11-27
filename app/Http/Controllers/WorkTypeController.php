<?php

namespace App\Http\Controllers;

use App\Models\WorkType;
use Illuminate\Http\Request;

class WorkTypeController extends Controller
{
    public function index()
    {
        $workTypes = WorkType::all();
        return view('worktype.index', compact('workTypes'));
    }

    public function create()
    {
        return view('worktype.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'cause' => 'required',
            'amount' => 'required|numeric'
        ]);

        WorkType::create($request->only('cause', 'amount'));

        return redirect()->route('worktype.index')->with('success', 'Work Type Added');
    }

    public function edit($id)
    {
        $workType = WorkType::findOrFail($id);
        return view('worktype.edit', compact('workType'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'cause' => 'required',
            'amount' => 'required|numeric'
        ]);

        WorkType::findOrFail($id)->update($request->only('cause', 'amount'));

        return redirect()->route('worktype.index')->with('success', 'Work Type Updated');
    }

    public function destroy($id)
    {
        WorkType::findOrFail($id)->delete();
        return redirect()->route('worktype.index')->with('success', 'Deleted Successfully');
    }
}
