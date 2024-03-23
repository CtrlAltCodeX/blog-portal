<?php

namespace App\Http\Controllers;

use App\Models\SiteSetting;
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
            // 'title' => 'required|string|max:255',
            'file' => 'required|file|mimes:jpeg,png,jpg,gif',
        ]);
    
        $siteSettings = SiteSetting::first();
    
        $file = $request->file('file');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->storeAs('public/uploads/', $filename);
    
        $image = Image::make(storage_path('app/public/uploads/' . $filename));
    
        $fontSize = min($image->width(), $image->height()) / 20;
    
        // Calculate center coordinates of the image
        $centerX = $image->width() / 2;
        $centerY = $image->height() / 2;
    
        // Calculate watermark position based on the image size
        $watermarkText = $siteSettings->watermark_text ?? 'Exam 360';
        $watermarkLength = strlen($watermarkText);
        $numPoints = max($watermarkLength, 10); // Ensure we have at least as many points as characters in the watermark text
    
        $radius = min($image->width(), $image->height()) / 2;
    
        for ($i = 0; $i < $numPoints; $i++) {
            $angle = $i * (360 / $numPoints);
    
            // Calculate the position for the watermark text on the circle
            $x = $centerX + $radius * cos(deg2rad($angle));
            $y = $centerY + $radius * sin(deg2rad($angle));
    
            // Add watermark text
            $image->text($watermarkText, $x, $y, function ($font) use ($fontSize) {
                $font->file(public_path('anta.ttf'));
                $font->color([128, 128, 128, 0.5]);
                $font->size($fontSize);
                $font->angle(45);
                $font->align('center');
                $font->valign('center');
            });
        }
    
        // Add watermark text at the center of the image
        $image->text($watermarkText, $centerX, $centerY, function ($font) use ($fontSize) {
            $font->file(public_path('anta.ttf'));
            $font->color([128, 128, 128, 0.5]);
            $font->size($fontSize);
            $font->angle(45);
            $font->align('center');
            $font->valign('center');
        });
    
        $image->save();
    
        $imageContent = Storage::get("public/uploads/{$filename}");
    
        session()->put('watermarkFileUrl', $filename);
    
        return Response::make($imageContent, 200, [
            'Content-Type' => 'image/jpeg',
            'Content-Disposition' => 'attachment; filename=' . $filename,
        ]);
    }
}
