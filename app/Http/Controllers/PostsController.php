<?php

namespace App\Http\Controllers;

use App\Models\CreatePage;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostsController extends Controller
{
    /**
     * Display a listing of pages with optional status filtering.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
 public function index(Request $request)
{
    $statusFilter = $request->get('status');
    $sortOrder    = $request->get('sort', 'desc');
    $category_id  = $request->get('category_id');
    $subcat_id    = $request->get('subcat_id');
    $slaFilter    = $request->get('sla');

  
    $query = CreatePage::with(['category', 'subCategory', 'user']);

  
    $query->orderBy('created_at', $sortOrder === 'asc' ? 'asc' : 'desc');


    if ($statusFilter) {
        $now = now();
        if ($statusFilter === 'auto_rejected') {
            $query->where('status', 'pending')
                ->where('created_at', '<=', $now->copy()->subDays(7));

        } elseif ($statusFilter === 'last_day_action') {
            $query->where('status', 'pending')
                ->where('created_at', '<=', $now->copy()->subDays(3))
                ->where('created_at', '>', $now->copy()->subDays(7));

        } elseif ($statusFilter === 'pending_recent') {
            $query->where('status', 'pending')
                ->where('created_at', '>', $now->copy()->subDays(3));

        } else {
            $query->where('status', $statusFilter);
        }
    }

 
    if (!empty($category_id)) {
        $query->where('category_id', $category_id);
    }

 
    if (!empty($subcat_id)) {
        $query->where('sub_category_id', $subcat_id);
    }

if (!empty($slaFilter)) {

    $now = now();

    if ($slaFilter === 'regular') {
     
        $query->whereBetween('created_at', [
            $now->copy()->subHours(24),
            $now
        ]);
    }

    if ($slaFilter === 'normal') {
      
        $query->whereBetween('created_at', [
            $now->copy()->subHours(48),
            $now->copy()->subHours(24)
        ]);
    }

    if ($slaFilter === 'caution') {
   
        $query->whereBetween('created_at', [
            $now->copy()->subHours(72),
            $now->copy()->subHours(48)
        ]);
    }

    if ($slaFilter === 'breach') {
    
        $query->where('created_at', '<=', $now->copy()->subHours(72));
    }
}


    $pages = $query->paginate(10)->appends($request->all());

    $total = CreatePage::count();
    $now = now();
    $pendingQuery = CreatePage::where('status', 'pending');

    $pending = (clone $pendingQuery)->where('created_at', '>', $now->copy()->subDays(3))->count();
    $lastDayAction = (clone $pendingQuery)
        ->where('created_at', '<=', $now->copy()->subDays(3))
        ->where('created_at', '>', $now->copy()->subDays(7))
        ->count();
    $autoRejected = (clone $pendingQuery)->where('created_at', '<=', $now->copy()->subDays(7))->count();
    $approved = CreatePage::where('status', 'approved')->count();
    $denied = CreatePage::where('status', 'denied')->count();

    $calc = fn($num) => $total > 0 ? round(($num / $total) * 100, 2) : 0;

    $stats = [
        'pending_recent'   => ['label' => 'Pending', 'count' => $pending, 'percent' => $calc($pending)],
        'last_day_action'  => ['label' => 'Last Day Action', 'count' => $lastDayAction, 'percent' => $calc($lastDayAction)],
        'auto_rejected'    => ['label' => 'No Actions Taken (Auto Rejected)', 'count' => $autoRejected, 'percent' => $calc($autoRejected)],
        'approved'         => ['label' => 'Approved', 'count' => $approved, 'percent' => $calc($approved)],
        'denied'           => ['label' => 'Denied', 'count' => $denied, 'percent' => $calc($denied)],
    ];


    $categories = Category::whereNull('parent_id')->get();
    $subcategories = Category::whereNotNull('parent_id')->get();

    return view('posts.index', compact(
        'pages',
        'stats',
        'statusFilter',
        'sortOrder',
        'categories',
        'subcategories',
        'category_id',
        'subcat_id',
        'slaFilter'
    ));
}

public function batchDetails($id)
{
    $page = CreatePage::with(['category', 'subCategory', 'user'])->findOrFail($id);

    return view('posts.batch-details', compact('page'));
}


    /**
     * Show the form for creating a new page entry.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $categories = Category::whereNull('parent_id')->with('children')->get();
        return view('posts.create', compact('categories'));
    }

    /**
     * Store a newly created page entry in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_id'        => 'required',
            'any_preferred_date' => 'required',
            'upload.*' => 'nullable|mimes:jpg,jpeg,png,pdf,doc,docx',
            'url' => 'nullable|url',

        ]);

        $batchId = str_pad(0000001, 7, '0', STR_PAD_LEFT);
        if ($last = CreatePage::latest()->first()) {
            $batchId = str_pad(++$last->batch_id, 7, '0', STR_PAD_LEFT);
        }

                $filePaths = [];

            if ($request->hasFile('upload')) {
                foreach ($request->file('upload') as $file) {
                    $path = $file->store('uploads', 'public');
                    $filePaths[] = $path;
                }
            }

            $finalFiles = implode(',', $filePaths); // comma-separated


        CreatePage::create([
            'user_id'          => Auth::id(),
            'category_id'      => $request->category_id,
            'sub_category_id'  => $request->sub_category_id,
            'any_preferred_date' => $request->any_preferred_date,
            'date'             => $request->any_preferred_date === 'Yes' ? $request->date : null,
           'upload' => $finalFiles,
           'url'                => $request->url, 
            'batch_id'         => $batchId,
            'status'           => 'pending',
        ]);

        return redirect()
            ->route('posts.index')
            ->with('success', 'Create Page entry added successfully!');
    }


    /**
     * Update a single page record (status & official remark).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateSingle(Request $request, $id)
    {
        $request->validate([
            'status'          => 'required|in:pending,approved,denied',
            'official_remark' => 'nullable|string|max:255',
        ]);

        $page = CreatePage::findOrFail($id);

        $page->update([
            'official_remark' => $request->official_remark,
            'remarks_user_id' => Auth::id(),
            'remarks_date'    => now(),
            'status'          => $request->status,
        ]);

        return response()->json(['success' => true, 'message' => 'Record updated successfully.']);
    }

    /**
     * Update multiple page records at once.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'ids'             => 'required|array',
            'status'          => 'required|in:pending,approved,denied',
            'official_remark' => 'nullable|string|max:255',
        ]);

        CreatePage::whereIn('id', $request->ids)->update([
            'official_remark' => $request->official_remark,
            'remarks_user_id' => Auth::id(),
            'remarks_date'    => now(),
            'status'          => $request->status,
        ]);

        return response()->json(['success' => true, 'message' => 'Selected records updated successfully.']);
    }

    /**
     * Delete multiple page records.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
        ]);

        CreatePage::whereIn('id', $request->ids)->delete();

        return response()->json(['success' => true, 'message' => 'Selected records deleted successfully.']);
    }

    /**
     * Delete a single page record by ID.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        CreatePage::findOrFail($id)->delete();

        return response()->json(['success' => true, 'message' => 'Record deleted successfully.']);
    }
}
