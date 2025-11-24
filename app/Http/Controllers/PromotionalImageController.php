<?php

namespace App\Http\Controllers;

use App\Models\PromotionalImage;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PromotionalImageController extends Controller
{

        public function index()
{
    $PromotionalImage = PromotionalImage::get();

    return view('promotional.index', compact('PromotionalImage'));
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
  $lastBatch = PromotionalImage::max('batch_id');
    if (!$lastBatch) {
        $lastBatch = 0;
    }
    
        foreach ($request->rows as $row) {
         $lastBatch++;
        $batchId = str_pad($lastBatch, 7, '0', STR_PAD_LEFT);

            $attachmentImage = null;
            $attachmentDocs = null;

            // Image Upload
            if (isset($row['attach_image'])) {
                $attachmentImage = $row['attach_image']->store('uploads/promotional/image', 'public');
            }

            PromotionalImage::create([
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

    return view('promotional.single-row', [
        'categories' => $categories,
        'index' => $request->index
    ]);
}

}
