<?php

namespace App\Http\Controllers;

use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Intervention\Image\ImageManager;

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
            'file' => 'required|file|mimes:jpeg,png,jpg',
        ]);

        $siteSettings = SiteSetting::first();

        $file = $request->file('file');
        $files = [];
        $lastNumber = 0;
        
        foreach (File::glob(storage_path('app/public/uploads') . '/*') as $key => $path) {
            $filepath = explode('/', $path);
            $name = (int) explode('.', $filepath[8])[0];
            
            if ($name > $lastNumber) {
                $lastNumber = $name;
            }
            
            $files[$key]['name'] = $filepath[8];
            $files[$key]['number'] = $name;
        }
        

        $countIncrease = $lastNumber + 1;
        
        $filename = $countIncrease . "." . $file->getClientOriginalExtension();
        // $filename = time() . '_' . $file->getClientOriginalName();
        $file->storeAs('public/uploads/', $filename);
        
        $background = (new ImageManager())->canvas(555, 555, '#ffffff');

        $background->insert(Image::make(storage_path('app/public/uploads/' . $filename))->resize(390, 520), 'center');

        // $image = Image::make(storage_path('app/public/uploads/' . $filename))->fit(555, 555);
        
        if (request()->with_watermark) {
            $fontSize = min($background->width(), $background->height()) / 20;
    
            // Calculate center coordinates of the image
            $centerX = $background->width() / 2;
            $centerY = $background->height() / 2;
    
            // Calculate watermark position based on the image size
            $watermarkText = $siteSettings->watermark_text ?? 'Exam 360';
            $watermarkLength = strlen($watermarkText);
            $numPoints = max($watermarkLength, 1); // Ensure we have at least as many points as characters in the watermark text
    
            $radius = min($background->width(), $background->height()) / 2;
    
            for ($i = 1; $i < $numPoints; $i++) {
                $angle = $i * (360 / $numPoints) + 55;
    
                // Calculate the position for the watermark text on the circle
                $x = $centerX + $radius * cos(deg2rad($angle));
                $y = $centerY + $radius * sin(deg2rad($angle));
    
                // Add watermark text
                $background->text($watermarkText, $x, $y, function ($font) use ($fontSize) {
                    $font->file(public_path('arial.ttf'));
                    $font->color([128, 128, 128, 0.5]);
                    $font->size($fontSize);
                    $font->angle(45);
                    $font->align('center');
                    $font->valign('center');
                });
            }
    
            // Add watermark text at the center of the image
            $background->text($watermarkText, $centerX, $centerY, function ($font) use ($fontSize) {
                $font->file(public_path('arial.ttf'));
                $font->color([128, 128, 128, 0.5]);
                $font->size($fontSize);
                $font->angle(45);
                $font->align('center');
                $font->valign('center');
            });
        }

        $background->save(storage_path('app/public/uploads/' . $filename));

        $imageContent = Storage::get("public/uploads/{$filename}");

        session()->put('watermarkFileUrl', $filename);

        return Response::make($imageContent, 200, [
            'Content-Type' => 'image/jpeg',
            'Content-Disposition' => 'attachment; filename=' . $filename,
        ]);
    }
}
