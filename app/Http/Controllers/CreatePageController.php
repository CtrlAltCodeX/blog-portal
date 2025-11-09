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
        $pages = CreatePage::with(['category', 'subCategory', 'user'])
            ->latest()
            ->paginate(10);

        $total = CreatePage::count();
        $now = now();

        $pendingQuery = CreatePage::where('status', 'pending');


        $pending = (clone $pendingQuery)
            ->where('created_at', '>', $now->copy()->subDays(3))
            ->count();

        $lastDayAction = (clone $pendingQuery)
            ->where('created_at', '<=', $now->copy()->subDays(3))
            ->where('created_at', '>', $now->copy()->subDays(7))
            ->count();

        $autoRejected = (clone $pendingQuery)
            ->where('created_at', '<=', $now->copy()->subDays(7))
            ->count();

        $approved = CreatePage::where('status', 'approved')->count();
        $denied = CreatePage::where('status', 'denied')->count();


        $calc = function ($num) use ($total) {
            return $total > 0 ? round(($num / $total) * 100, 2) : 0;
        };

        $stats = [
            'Pending' => ['count' => $pending, 'percent' => $calc($pending)],
            'Last Day Action' => ['count' => $lastDayAction, 'percent' => $calc($lastDayAction)],
            'No Actions Taken (Auto Rejected)' => ['count' => $autoRejected, 'percent' => $calc($autoRejected)],
            'Approved' => ['count' => $approved, 'percent' => $calc($approved)],
            'Denied' => ['count' => $denied, 'percent' => $calc($denied)],
        ];

        return view('createpages.index', compact('pages', 'stats'));
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


    public function updateSingle(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,denied',
            'official_remark' => 'nullable|string|max:255',
        ]);

        $page = CreatePage::findOrFail($id);
        $page->update([
            'official_remark' => $request->official_remark,
            'remarks_user_id' => Auth::id(),
            'remarks_date' => now(),
            'status' => $request->status,
        ]);

        return response()->json(['success' => true, 'message' => 'Record updated successfully.']);
    }

    public function updateMultiple(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'status' => 'required|in:pending,approved,denied',
            'official_remark' => 'nullable|string|max:255',
        ]);

        CreatePage::whereIn('id', $request->ids)->update([
            'official_remark' => $request->official_remark,
            'remarks_user_id' => Auth::id(),
            'remarks_date' => now(),
            'status' => $request->status,
        ]);

        return response()->json(['success' => true, 'message' => 'Selected records updated successfully.']);
    }

    public function deleteMultiple(Request $request)
    {
        $request->validate(['ids' => 'required|array']);
        CreatePage::whereIn('id', $request->ids)->delete();

        return response()->json(['success' => true, 'message' => 'Selected records deleted successfully.']);
    }

    public function destroy($id)
    {
        CreatePage::findOrFail($id)->delete();
        return response()->json(['success' => true, 'message' => 'Record deleted successfully.']);
    }
}
