<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\Page;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PageController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::whereNull('parent_id')
            ->with('children.subChildren')
            ->get();

        $pages = Page::with([
            'category',
            'subCategory',
            'subSubCategory',
            'creator'
        ])->orderBy('id', 'DESC');

        // FILTERS
        if ($category_id = $request->get('category_id')) {
            $pages->where('category_id', $category_id);
        }

        if ($subcat_id = $request->get('sub_category_id')) {
            $pages->where('sub_category_id', $subcat_id);
        }

        if ($subsubcat_id = $request->get('sub_sub_category_id')) {
            $pages->where('sub_sub_category_id', $subsubcat_id);
        }

        $pages = $pages->paginate(10)->appends($request->query());

        return view('pages.index', compact(
            'pages',
            'categories',
            'category_id',
            'subcat_id',
            'subsubcat_id'
        ));
    }

    public function create()
    {
        $categories = Category::whereNull('parent_id')
            ->with('children.subChildren')
            ->get();

        return view('pages.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required',
            'sub_category_id' => 'required',
            'sub_sub_category_id' => 'required',
            'title' => 'required'
        ]);

        // Get latest batch ID from both tables
        $latestContentBatch = Content::max('batch_id');
        $latestPageBatch = Page::max('batch_id');

        $lastBatch = max($latestContentBatch, $latestPageBatch);
        if (!$lastBatch) {
            $lastBatch = 0;
        }

        // Next batch
        $lastBatch++;
        $batchId = str_pad($lastBatch, 7, '0', STR_PAD_LEFT);

        Page::create([
            'batch_id'            => $batchId,
            'user_id'             => Auth::id(),
            'title'               => $request->title,
            'description'         => $request->description,
            'category_id'         => $request->category_id,
            'sub_category_id'     => $request->sub_category_id,
            'sub_sub_category_id' => $request->sub_sub_category_id,
            'status'              => 'open'
        ]);
        return redirect()
            ->route('pages.index')
            ->with('success', 'Page created successfully.');
    }


    public function edit($id)
    {
        $page = Page::findOrFail($id);

        $categories = Category::whereNull('parent_id')
            ->with('children.subChildren')
            ->get();

        return view('pages.edit', compact('page', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $page = Page::findOrFail($id);

        $request->validate([
            'category_id' => 'required',
            'sub_category_id' => 'required',
            'sub_sub_category_id' => 'required',
            'title' => 'required'
        ]);

        $page->update([
            'title'               => $request->title,
            'description'         => $request->description,
            'category_id'         => $request->category_id,
            'sub_category_id'     => $request->sub_category_id,
            'sub_sub_category_id' => $request->sub_sub_category_id,
            'status'              => $request->status
        ]);

        return redirect()
            ->route('pages.index')
            ->with('success', 'Page updated successfully.');
    }

    public function destroy($id)
    {
        Page::findOrFail($id)->delete();

        return redirect()
            ->back()
            ->with('success', 'Page deleted successfully.');
    }
}
