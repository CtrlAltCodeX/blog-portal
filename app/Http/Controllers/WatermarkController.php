<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class WatermarkController extends Controller
{
    /**
     * Show the create view.
     */
    public function create()
    {
        return view('watermark.create');
    }

    /**
     * Store watermark
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'file' => 'required|file|mimes:jpeg,png,jpg,gif',
        ]);

        $file = $request->file('file');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->storeAs('uploads', $filename);

        $image = Image::make(storage_path('app/uploads/' . $filename));

        $fontSize = min($image->width(), $image->height()) / 20;

        $centerX = $image->width() / 2;
        $centerY = $image->height() / 2;

        $image->text($request->input('title'), $centerX, $centerY, function ($font) use ($fontSize) {
            $font->file(public_path('anta.ttf'));
            $font->color([255, 255, 255, 0.5]);
            $font->size($fontSize);
            $font->align('center');
            $font->valign('middle');
            $font->angle(0);
        });

        $image->save();

        $imageContent = Storage::get("uploads/{$filename}");

        return Response::make($imageContent, 200, [
            'Content-Type' => 'image/jpeg',
            'Content-Disposition' => 'attachment; filename=' . $filename,
        ]);
    }
}
