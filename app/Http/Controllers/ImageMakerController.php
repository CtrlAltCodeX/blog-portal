<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
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

    /**
     * Image Gallery
     *
     * @return void
     */
    public function imageGallery()
    {
        $files = [];
        foreach (File::glob(storage_path('app/public/uploads') . '/*') as $key => $path) {
            $filepath = explode('/', $path);

            foreach ($filepath as $name) {
                if (strpos($name, '.jpg') !== false || strpos($name, '.png') !== false) {
                    $files[$key]['name'] = $name;

                    $filePath = @exif_read_data(storage_path('/app/public/uploads/' . $name))['FileDateTime'];
                    $dateTime = Carbon::createFromTimestamp($filePath);
                    $files[$key]['datetime'] = $dateTime->toDateTimeString();
                }
            }
        }

        // Custom sorting function for descending order
        usort($files, function ($a, $b) {
            return strtotime($b['datetime']) - strtotime($a['datetime']);
        });

        return view('image-creation.image-gallery', compact('files'));
    }

    /**
     * Refresh the URLs
     */
    public function refreshURL()
    {
        if (request()->session)
            return session()->get(request()->session);
    }
}
