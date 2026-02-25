<?php

namespace App\Http\Controllers;

use App\Models\IssueType;
use Illuminate\Http\Request;

class IssueTypeController extends Controller
{
    public function index()
    {
        $issueTypes = IssueType::all();
        return view('issue_types.index', compact('issueTypes'));
    }

    public function create()
    {
        return view('issue_types.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:issue_types,name',
        ]);

        IssueType::create($request->only('name'));

        return redirect()->route('issue-types.index')->with('success', 'Issue Type Created Successfully.');
    }

    public function edit($id)
    {
        $issueType = IssueType::findOrFail($id);
        return view('issue_types.edit', compact('issueType'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:issue_types,name,' . $id,
        ]);

        $issueType = IssueType::findOrFail($id);
        $issueType->update($request->only('name', 'status'));

        return redirect()->route('issue-types.index')->with('success', 'Issue Type Updated Successfully.');
    }

    public function destroy($id)
    {
        $issueType = IssueType::findOrFail($id);
        $issueType->delete();

        return redirect()->route('issue-types.index')->with('success', 'Issue Type Deleted Successfully.');
    }
}
