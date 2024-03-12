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
        $images = [];
        foreach (File::glob(public_path('images') . '/*') as $path) {
            $filepath = explode('/', $path);
            foreach ($filepath as $name) {
                if (strpos($name, '.jpg') !== false || strpos($name, '.png') !== false) {
                    $images[] = $name;
                }
            }
        }

        return view('image-creation.single', compact('images'));
    }

    /**
     * Show the create view.
     */
    public function comboImage()
    {
        $images = [];
        foreach (File::glob(storage_path('app/public/uploads') . '/*') as $path) {
            $filepath = explode('/', $path);
            foreach ($filepath as $name) {
                if (str_contains($name, '.jpg')) {
                    $images[] = $name;
                }
            }
        }

        return view('image-creation.combo', compact('images'));
    }
}
