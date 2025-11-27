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

    public function index()
    {
        $contents = Content::get();
        $worktypes = WorkType::all();
        return view('content.index', compact('contents', 'worktypes'));
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
                'category'           => $row['category_id'],
                'sub_category'       => $row['sub_category_id'],
                'sub_sub_category'   => $row['sub_sub_category_id'],
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

    public function approvalList()
    {
        $contents = Content::with([
            'creator',
            'workType',
            'verifiedUser'
        ])
            ->whereNotNull('verified_by')  // filter applied
            ->orderBy('id', 'DESC')
            ->get();

        $promos = Promotional::with([
            'creator',
            'workType',
            'verifiedUser'
        ])
            ->whereNotNull('verified_by')  // filter applied
            ->orderBy('id', 'DESC')
            ->get();

        return view('approval.index', compact('contents', 'promos'));
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
