<?php

namespace App\Http\Controllers;

use App\Generators\CustomThreeImage;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
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
            'file'              => 'required|array',
            'file.*'            => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            // 'title'             => 'required_if:is_with_watermark,1',
        ], [
            'title.required_if' => 'The title field is required when adding a watermark.',
        ]);

        $processedImages = [];

        foreach ($validated['file'] as $file) {
            $processedImages[] = Image::make($file)->fit(555,555);
        }

        $image = (new MakeCollage())->with([
            1 => OneImage::class,
            2 => TwoImage::class,
            3 => CustomThreeImage::class,
            4 => FourImage::class,
        ])
            ->make(555, 555)->padding(10)
            ->background('#fff')
            ->from($processedImages, function ($alignment) use ($processedImages) {
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

        $path = 'storage/uploads/' . $filename;

        $image->save($path);

        $imageContent = file_get_contents($path);

        if (request()->input('is_with_watermark')) {
            $siteSettings = SiteSetting::first();

            $imageContent = $this->addWaterMark($image, $siteSettings->watermark_text);
        }

        Session::put('fileurl', $filename);

        return Response::make($imageContent, 200, [
            'Content-Type'        => 'image/png',
            'Content-Disposition' => 'attachment; filename=' . $filename,
        ]);
    }

    public function addWaterMark($image, $title)
    {
        $fontSize = min($image->width(), $image->height()) / 20;

        $centerX = $image->width() / 2;
        $centerY = $image->height() / 2;

        $watermarkLength = strlen($title);
        $numPoints = max($watermarkLength, 10);

        $radius = min($image->width(), $image->height()) / 2;

        for ($i = 0; $i < $numPoints; $i++) {
            $angle = $i * (360 / $numPoints);

            $x = $centerX + $radius * cos(deg2rad($angle));
            $y = $centerY + $radius * sin(deg2rad($angle));

            $image->text($title, $x, $y, function ($font) use ($fontSize) {
                $font->file(public_path('anta.ttf'));
                $font->color([128, 128, 128, 0.5]);
                $font->size($fontSize);
                $font->angle(45);
                $font->align('center');
                $font->valign('center');
            });
        }

        $image->text($title, $centerX, $centerY, function ($font) use ($fontSize) {
            $font->file(public_path('anta.ttf'));
            $font->color([128, 128, 128, 0.5]);
            $font->size($fontSize);
            $font->angle(45);
            $font->align('center');
            $font->valign('center');
        });

        $image->save();

        return $image->encoded;
    }
}
