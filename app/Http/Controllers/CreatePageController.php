<?php

namespace App\Http\Controllers;

use App\Models\CreatePage;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CreatePageController extends Controller
{
    public function index()
    {
        $pages = CreatePage::with(['category', 'subCategory', 'user'])->latest()->paginate(10);
        return view('createpages.index', compact('pages'));
    }

    public function create()
    {
        $categories = Category::whereNull('parent_id')->with('children')->get();
        return view('createpages.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required',
            'any_preferred_date' => 'required',
            'upload' => 'nullable|mimes:jpg,jpeg,png,pdf,doc,docx',
        ]);


        $batchId = str_pad(0000001, 7, '0', STR_PAD_LEFT);
        if ($last = CreatePage::latest()->first()) {
            $batchId = str_pad(++$last->batch_id, 7, '0', STR_PAD_LEFT);
        }

        $filePath = null;
        if ($request->hasFile('upload')) {
            $filePath = $request->file('upload')->store('uploads', 'public');
        }

        CreatePage::create([
            'user_id' => Auth::id(),
            'category_id' => $request->category_id,
            'sub_category_id' => $request->sub_category_id,
            'any_preferred_date' => $request->any_preferred_date,
            'date' => $request->any_preferred_date === 'Yes' ? $request->date : null,
            'upload' => $filePath,
            'batch_id' => $batchId,
            'status' => 'pending',
        ]);

        return redirect()->route('createpages.index')->with('success', 'Create Page entry added successfully!');
    }
}
