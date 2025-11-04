<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    
public function index()
{
    $categories = Category::with('parent')->paginate(10);
    return view('categories.index', compact('categories'));
}


public function create()
{
    $categories = Category::whereNull('parent_id')->with('children')->get();
    return view('categories.create', compact('categories'));
}



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
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
public function edit(Category $category)
{
    $categories = Category::whereNull('parent_id')->with('children')->get();
    return view('categories.edit', compact('category', 'categories'));
}


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

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Category deleted successfully!');
    }
}
