<?php

namespace App\Http\Controllers;

use App\Models\IssueType;
use Illuminate\Http\Request;

class IssueTypeController extends Controller
{
    private function getType()
    {
        return request()->is('admin/official-issue-types*') ? 'official' : 'task';
    }

    private function getViewPath()
    {
        return $this->getType() === 'official' ? 'official_issue_types' : 'issue_types';
    }

    private function getRedirectRoute()
    {
        return $this->getType() === 'official' ? 'official-issue-types.index' : 'issue-types.index';
    }

    public function index()
    {
        $type = $this->getType();
        $issueTypes = IssueType::where('type', $type)->get();
        return view($this->getViewPath() . '.index', compact('issueTypes', 'type'));
    }

    public function create()
    {
        return view($this->getViewPath() . '.create');
    }

    public function store(Request $request)
    {
        $type = $this->getType();
        $request->validate([
            'name' => 'required|unique:issue_types,name,NULL,id,type,' . $type,
        ]);

        $data = $request->only('name');
        $data['type'] = $type;
        IssueType::create($data);

        return redirect()->route($this->getRedirectRoute())->with('success', 'Issue Type Created Successfully.');
    }

    public function edit($id)
    {
        $issueType = IssueType::findOrFail($id);
        return view($this->getViewPath() . '.edit', compact('issueType'));
    }

    public function update(Request $request, $id)
    {
        $type = $this->getType();
        $request->validate([
            'name' => 'required|unique:issue_types,name,' . $id . ',id,type,' . $type,
        ]);

        $issueType = IssueType::findOrFail($id);
        $issueType->update($request->only('name', 'status'));

        return redirect()->route($this->getRedirectRoute())->with('success', 'Issue Type Updated Successfully.');
    }

    public function destroy($id)
    {
        $issueType = IssueType::findOrFail($id);
        $issueType->delete();

        return redirect()->route($this->getRedirectRoute())->with('success', 'Issue Type Deleted Successfully.');
    }
}