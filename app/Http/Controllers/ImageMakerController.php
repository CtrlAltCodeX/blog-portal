<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ImageMakerController extends Controller
{

    /**
     * Invoke Function
     *
     * @param string $file
     * @return void
     */
    public function __invoke($file)
    {
        abort_if(auth()->guest(), Response::HTTP_FORBIDDEN);

        return response()->file(
            storage_path('app/public/uploads/' . $file)
        );
    }

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
                    $fileSize = @exif_read_data(storage_path('/app/public/uploads/' . $name))['FileSize'];

                    $dateTime = Carbon::createFromTimestamp($filePath);
                    $files[$key]['datetime'] = $dateTime->toDateTimeString();
                    $files[$key]['size'] = $fileSize;
                }
            }
        }

        // Custom sorting function for descending order
        usort($files, function ($a, $b) {
            return strtotime($b['datetime']) - strtotime($a['datetime']);
        });

        $files = $this->paginate($files);

        return view('image-creation.image-gallery', compact('files'));
    }

    /**
     * Paginate  All Queries
     *
     * @param array $items
     * @param int $perPage
     * @param int $page
     * @param array $options
     * @return void
     */
    public function paginate($items, $perPage = 15, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);

        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }

    /**
     * Refresh the URLs
     */
    public function refreshURL()
    {
        if (request()->session)
            return session()->get(request()->session);
    }

    /**
     * Delete Images
     *
     * @return void
     */
    public function deleteImage()
    {
        if (request()->ajax()) {
            foreach (request()->formData[1] as $image) {
                if (Storage::exists('/public/uploads/' . $image)) {
                    Storage::delete('/public/uploads/' . $image);
                }
            }

            return true;
        }

        if (Storage::exists('/public/uploads/' . request()->name)) {
            Storage::delete('/public/uploads/' . request()->name);
        }

        session()->flash('success', 'Image Delete Successfully');

        return redirect()->back();
    }
}
