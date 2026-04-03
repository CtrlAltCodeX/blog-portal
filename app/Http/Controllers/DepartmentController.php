<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    private function getType()
    {
        return request()->is('admin/official-departments*') ? 'official' : 'task';
    }

    private function getViewPath()
    {
        return $this->getType() === 'official' ? 'official_departments' : 'departments';
    }

    private function getRedirectRoute()
    {
        return $this->getType() === 'official' ? 'official-departments.index' : 'departments.index';
    }

    public function index()
    {
        $type = $this->getType();
        $departments = Department::where('type', $type)->get();
        return view($this->getViewPath() . '.index', compact('departments', 'type'));
    }

    public function create()
    {
        return view($this->getViewPath() . '.create');
    }

    public function store(Request $request)
    {
        $type = $this->getType();
        $request->validate([
            'name' => 'required|unique:departments,name,NULL,id,type,' . $type,
            'email' => 'nullable|email',
        ]);

        $data = $request->all();
        $data['type'] = $type;
        Department::create($data);

        return redirect()->route($this->getRedirectRoute())->with('success', 'Department Created Successfully.');
    }

    public function edit($id)
    {
        $department = Department::findOrFail($id);
        return view($this->getViewPath() . '.edit', compact('department'));
    }

    public function update(Request $request, $id)
    {
        $type = $this->getType();
        $request->validate([
            'name' => 'required|unique:departments,name,' . $id . ',id,type,' . $type,
            'email' => 'nullable|email',
        ]);

        $department = Department::findOrFail($id);
        $department->update($request->all());

        return redirect()->route($this->getRedirectRoute())->with('success', 'Department Updated Successfully.');
    }

    public function destroy($id)
    {
        $department = Department::findOrFail($id);
        $department->delete();

        return redirect()->route($this->getRedirectRoute())->with('success', 'Department Deleted Successfully.');
    }
}