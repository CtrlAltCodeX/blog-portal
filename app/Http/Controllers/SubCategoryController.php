<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class SubCategoryController extends Controller
{
    public function index()
    {
        $subcategories = Category::whereNotNull('parent_id')->with('parent')->paginate(10);
        return view('subcategories.index', compact('subcategories'));
    }

    public function create()
    {
        $categories = Category::whereNull('parent_id')->get();
        return view('subcategories.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'required|exists:categories,id',
            'category_limit' => 'nullable|integer',
            'preference' => 'nullable|string',
        ]);

        Category::create($request->only(['name', 'parent_id', 'category_limit', 'preference']));

        return redirect()->route('subcategories.index')->with('success', 'Sub-Category created successfully!');
    }

    public function edit(Category $subcategory)
    {
        $categories = Category::whereNull('parent_id')->get();
        return view('subcategories.edit', compact('subcategory', 'categories'));
    }

    public function update(Request $request, Category $subcategory)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'required|exists:categories,id',
            'category_limit' => 'nullable|integer',
            'preference' => 'nullable|string',
        ]);

        $subcategory->update($request->only(['name', 'parent_id', 'category_limit', 'preference']));

        return redirect()->route('subcategories.index')->with('success', 'Sub-Category updated successfully!');
    }

    public function destroy(Category $subcategory)
    {
        $subcategory->delete();
        return redirect()->route('subcategories.index')->with('success', 'Sub-Category deleted successfully!');
    }
}
