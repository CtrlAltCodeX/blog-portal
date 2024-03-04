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

        $numPoints = 10;

        $radius = min($image->width(), $image->height()) / 2;

        for ($i = 0; $i < $numPoints; $i++) {
            $angle = $i * (360 / $numPoints);

            $x = $image->width() / 2 + $radius * cos(deg2rad($angle));

            $y = $image->height() / 2 + $radius * sin(deg2rad($angle));

            $image->text($request->input('title'), $x, $y, function ($font) use ($fontSize) {
                $font->file(public_path('anta.ttf'));
                $font->color([255, 255, 255, 0.5]);
                $font->size($fontSize);
                $font->angle(45);
            });
        }

        $image->save();

        $imageContent = Storage::get("uploads/{$filename}");

        return Response::make($imageContent, 200, [
            'Content-Type' => 'image/jpeg',
            'Content-Disposition' => 'attachment; filename=' . $filename,
        ]);
    }
}