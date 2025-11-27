<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class SubCategoryController extends Controller
{
    /**
     * Display a listing of all subcategories (categories with parent_id).
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $subcategories = Category::whereNotNull('parent_id')->whereNull("sub_parent_id")
            ->with('parent')
            ->paginate(10);

        return view('subcategories.index', compact('subcategories'));
    }

    /**
     * Show the form for creating a new subcategory.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $categories = Category::whereNull('parent_id')->get();

        return view('subcategories.create', compact('categories'));
    }

    /**
     * Store a newly created subcategory in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'parent_id'      => 'required|exists:categories,id',
            'category_limit' => 'nullable|integer',
            'preference'     => 'nullable|string',
        ]);

        Category::create($request->only(['name', 'parent_id', 'category_limit', 'preference']));

        return redirect()
            ->route('subcategories.index')
            ->with('success', 'Sub-Category created successfully!');
    }

    /**
     * Show the form for editing the specified subcategory.
     *
     * @param  \App\Models\Category  $subcategory
     * @return \Illuminate\View\View
     */
    public function edit(Category $subcategory)
    {
        $categories = Category::whereNull('parent_id')->get();

        return view('subcategories.edit', compact('subcategory', 'categories'));
    }

    /**
     * Update the specified subcategory in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category      $subcategory
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Category $subcategory)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'parent_id'      => 'required|exists:categories,id',
            'category_limit' => 'nullable|integer',
            'preference'     => 'nullable|string',
        ]);

        $subcategory->update($request->only(['name', 'parent_id', 'category_limit', 'preference']));

        return redirect()
            ->route('subcategories.index')
            ->with('success', 'Sub-Category updated successfully!');
    }

    /**
     * Remove the specified subcategory from the database.
     *
     * @param  \App\Models\Category  $subcategory
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Category $subcategory)
    {
        $subcategory->delete();

        return redirect()
            ->route('subcategories.index')
            ->with('success', 'Sub-Category deleted successfully!');
    }
}
