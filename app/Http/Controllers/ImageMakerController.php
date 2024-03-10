<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;

class ImageMakerController extends Controller
{
    /**
     * Show the create view.
     */
    public function singleImage()
    {
        return view('image-creation.single');
    }

    /**
     * Show the create view.
     */
    public function comboImage()
    {
        $images = [];
        foreach (File::glob(storage_path('app/public/uploads') . '/*') as $path) {
            $images[] = explode('/', $path)[3];
        }

        return view('image-creation.combo', compact('images'));
    }
}
