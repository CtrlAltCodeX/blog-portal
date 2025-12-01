<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\Promotional;
use App\Models\WorkType;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PromotionalController extends Controller
{

 
    public function index(Request $request)
    {
        $categories = Category::whereNull('parent_id')->with('children.subChildren')->get();

        $promotionalImage = Promotional::with([
            'category',
            'subCategory',
            'subSubCategory',
            'creator'
        ])->orderBy('id', 'DESC');

        // FILTERS APPLY
        if ($category_id  = $request->get('category_id')) {
            $promotionalImage->where('category_id', $category_id);
        }

        if ($subcat_id  = $request->get('sub_category_id')) {
            $promotionalImage->where('sub_category_id', $subcat_id);
        }

        if ($subsubcat_id  = $request->get('sub_sub_category_id')) {
            $promotionalImage->where('sub_sub_category_id', $subsubcat_id);
        }

        $promotionalImage = $promotionalImage->paginate(10)->appends($request->query());

        $worktypes = WorkType::all();

        return view('promotional.index', compact(
            'promotionalImage',
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

        return view('promotional.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'rows' => 'required|array',
            'rows.*.category_id' => 'required',
            'rows.*.sub_category_id' => 'required',
            'rows.*.sub_sub_category_id' => 'required',
        ]);
        $lastBatch = Promotional::max('batch_id');
        if (!$lastBatch) {
            $lastBatch = 0;
        }

        foreach ($request->rows as $row) {
            $lastBatch++;
            $batchId = str_pad($lastBatch, 7, '0', STR_PAD_LEFT);

            $attachmentImage = null;

            if (isset($row['attach_image'])) {
                $attachmentImage = $row['attach_image']->store('uploads/promotional/image', 'public');
            }

            Promotional::create([
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
                'attach_url'            => $row['attach_url'] ?? null,
            ]);
        }

        return redirect()
            ->back()
            ->with('success', 'Promotional images saved successfully.');
    }

    public function getRow(Request $request)
    {
        $categories = Category::whereNull('parent_id')
            ->with('children')
            ->get();

        return view('components.single-row', [
            'categories' => $categories,
            'index' => $request->index,
            'showDocs' => false
        ]);
    }

    public function submit(Request $r)
    {
        $model = $r->type === 'content' ? Content::class : Promotional::class;

        $item = $model::findOrFail($r->id);

        $item->update([
            'verified_by'        => Auth::id(),
            'verified_date'      => now()->format('Y-m-d'),
            'verified_time'      => now()->format('H:i:s'),
            'status'             => $r->status,
            'rejection_cause'    => $r->status === 'denied' ? $r->rejection_cause : null,
            'worktype_id'        => $r->worktype_id,
            'expected_amount'    => $r->expected_amount,
            'content_report_note' => $r->content_report_note,
            'host_record_note'   => $r->host_record_note,
        ]);

        return back()->with('success', 'Approval Updated Successfully');
    }
}
