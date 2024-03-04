<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;
use Intervention\Image\Facades\Image;
use Tzsk\Collage\MakeCollage;

class CollageController extends Controller
{
    /**
     * Show the create view.
     */
    public function create()
    {
        return view('collage.create');
    }

    public function store()
    {
        $this->validate(request(), [
            'file.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $files = request()->except('_token');

        $processedImages = [];

        foreach ($files['file'] as $file) {
            $image = Image::make($file);

            $image->resize(300, 200);

            $processedImages[] = $image;
        }

        $collage = new MakeCollage();

        $image = $collage->make(400, 400)->padding(10)->from($processedImages);

        $filename = 'collage_' . time() . '.png';
        $image->save(public_path('storage/' . $filename));

        $imageContent = file_get_contents(public_path('storage/' . $filename));

        return Response::make($imageContent, 200, [
            'Content-Type' => 'image/png',
            'Content-Disposition' => 'attachment; filename=' . $filename,
        ]);
    }
}