<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories with their parent relationships.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $categories = Category::with('parent')->paginate(10);
        return view('categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new category.
     * Includes a list of top-level categories with their children.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $categories = Category::whereNull('parent_id')->with('children')->get();
        return view('categories.create', compact('categories'));
    }

    /**
     * Store a newly created category in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_limit' => 'nullable|integer',
            'preference' => 'nullable|string',
        ]);

        Category::create([
            'name' => $request->name,
            'parent_id' => $request->parent_id,
            'category_limit' => $request->category_limit,
            'preference' => $request->preference,
        ]);

        return redirect()->route('categories.index')->with('success', 'Category created successfully!');
    }

    /**
     * Display the specified category.
     *
     * @param  string  $id
     * @return \Illuminate\View\View|\Illuminate\Http\Response
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified category.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\View\View
     */
    public function edit(Category $category)
    {
        $categories = Category::whereNull('parent_id')->with('children')->get();
        return view('categories.edit', compact('category', 'categories'));
    }

    /**
     * Update the specified category in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_limit' => 'nullable|integer',
            'preference' => 'nullable|string',
        ]);

        $category->update($request->only(['name', 'parent_id', 'category_limit', 'preference']));

        return redirect()->route('categories.index')->with('success', 'Category updated successfully!');
    }

    /**
     * Remove the specified category from the database.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Category deleted successfully!');
    }
}
