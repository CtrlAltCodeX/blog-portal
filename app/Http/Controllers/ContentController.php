<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\Category;
use App\Models\WorkType;
use Illuminate\Http\Request;
use App\Models\Promotional;
use Illuminate\Support\Facades\Auth;

class ContentController extends Controller
{
    public function index(Request $request)
    {
        $category_id  = $request->get('category_id');
        $subcat_id    = $request->get('sub_category_id');
        $subsubcat_id    = $request->get('sub_sub_category_id');


        $categories = Category::whereNull('parent_id')->with('children.subChildren')->get();

        $contents = Content::with([
            'category',
            'subCategory',
            'subSubCategory',
            'creator'
        ])->orderBy('id', 'DESC');

        // FILTERS APPLY
        if ($category_id) {
            $contents->where('category_id', $category_id);
        }

        if ($subcat_id) {
            $contents->where('sub_category_id', $subcat_id);
        }

        if ($subsubcat_id) {
            $contents->where('sub_sub_category_id', $subsubcat_id);
        }

        $contents = $contents->paginate(10)->appends($request->query());
        $worktypes = WorkType::all();
        return view('content.index', compact(
            'contents',
            'categories',
            'category_id',
            'subcat_id',
            'subsubcat_id',
            'worktypes'
        ));
    }


    public function create()
    {
        $categories = Category::whereNull('parent_id')
            ->with('children.subChildren')
            ->get();

        return view('content.create', compact('categories'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'rows' => 'required|array',
            'rows.*.category_id' => 'required',
            'rows.*.sub_category_id' => 'required',
            'rows.*.sub_sub_category_id' => 'required',
        ]);
        $lastBatch = Content::max('batch_id');
        if (!$lastBatch) {
            $lastBatch = 0;
        }

        foreach ($request->rows as $row) {
            $lastBatch++;
            $batchId = str_pad($lastBatch, 7, '0', STR_PAD_LEFT);

            $attachmentImage = null;
            $attachmentDocs = null;

            if (isset($row['attach_image'])) {
                $attachmentImage = $row['attach_image']->store('uploads/content/image', 'public');
            }

            if (isset($row['attach_docs'])) {
                $attachmentDocs = $row['attach_docs']->store('uploads/content/docs', 'public');
            }

            Content::create([
                'batch_id'              => $batchId,
                'user_id'               => Auth::id(),
                'category_id'           => $row['category_id'],
                'sub_category_id'       => $row['sub_category_id'],
                'sub_sub_category_id'   => $row['sub_sub_category_id'],
                'title'                 => $row['title'] ?? null,
                'brief_description'     => $row['brief_description'] ?? null,
                'any_preferred_date'    => $row['any_preferred_date'] ?? 'No',
                'preferred_date'        => isset($row['any_preferred_date']) && $row['any_preferred_date'] == 'Yes' ? $row['preferred_date'] : null,
                'attach_image'          => $attachmentImage,
                'attach_docs'           => $attachmentDocs,
                'attach_url'            => $row['attach_url'] ?? null,
            ]);
        }

        return redirect()
            ->back()
            ->with('success', 'Content saved successfully.');
    }

    public function approvalList(Request $request)
    {
        $status = $request->status ?? null;
        $activeTab = $request->tab ?? 'content';

        $contents = Content::with(['creator', 'workType', 'verifiedUser'])
            ->when($status, function ($q) use ($status) {
                if ($status != 'all') {
                    $q->where('status', $status);
                }
            })
            ->whereNotNull('verified_by')
            ->orderBy('id', 'DESC')
            ->get();

        $promos = Promotional::with(['creator', 'workType', 'verifiedUser'])
            ->when($status, function ($q) use ($status) {
                if ($status != 'all') {
                    $q->where('status', $status);
                }
            })
            ->whereNotNull('verified_by')
            ->orderBy('id', 'DESC')
            ->get();

        return view('approval.index', compact('contents', 'promos', 'status', 'activeTab'));
    }

    public function getRow(Request $request)
    {
        $categories = Category::whereNull('parent_id')
            ->with('children')
            ->get();

        return view('components.single-row', [
            'categories' => $categories,
            'index' => $request->index,
            'showDocs' => true
        ]);
    }
}
