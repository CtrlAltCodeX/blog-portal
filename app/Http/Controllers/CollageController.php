<?php

namespace App\Http\Controllers;

use App\Generators\CustomThreeImage;
use Illuminate\Support\Facades\Response;
use Intervention\Image\Facades\Image;
use Tzsk\Collage\Generators\FourImage;
use Tzsk\Collage\Generators\OneImage;
use Tzsk\Collage\Generators\TwoImage;
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
        $validated = $this->validate(request(), [
            'file'   => 'required|array',
            'file.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $processedImages = [];

        foreach ($validated['file'] as $file) {
            $processedImages[] = Image::make($file);
        }

        $image = (new MakeCollage())->with([
            1 => OneImage::class,
            2 => TwoImage::class,
            3 => CustomThreeImage::class,
            4 => FourImage::class,
        ])
            ->make(400, 400)->padding(10)
            ->background('#fff')
            ->from($processedImages, function($alignment) use($processedImages) {
            $imageCount = count($processedImages);

            if ($imageCount == 2) {
                $alignment->vertical();
            } else if ($imageCount == 3) {
                $alignment->twoTopOneBottom();
            } else if ($imageCount == 4) {
                $alignment->grid();
            }
        });

        $filename = 'collage_' . time() . '.png';

        $image->save(public_path('storage/uploads/' . $filename));

        return Response::make(file_get_contents(public_path('storage/uploads/' . $filename)), 200, [
            'Content-Type'        => 'image/png',
            'Content-Disposition' => 'attachment; filename=' . $filename,
        ]);
    }
}
