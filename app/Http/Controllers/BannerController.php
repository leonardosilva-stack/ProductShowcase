<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Http\Requests\BannerRequest;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BannerController extends Controller
{
    public function index()
    {
        try {
            $banners = Banner::all();
            return response()->json($banners);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while retrieving banners', 'message' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $banner = Banner::findOrFail($id);
            return response()->json($banner);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Banner not found'], 404);
        }
    }


    public function store(BannerRequest $request)
    {
        try {

            $banner = Banner::create([
                'title'          => $request->title,
                'description'    => $request->description,
                'link'           => $request->link,
                'status'         => true,
                'expirationDate' => $request->expirationDate ?? null,
                'desktopImage'   => null,
                'tabletImage'    => null,
                'mobileImage'    => null,
            ]);


            $imagePaths = $this->processImages($request, $banner->id);

            $banner->update([
                'desktopImage' => $imagePaths['desktopImage'],
                'tabletImage'  => $imagePaths['tabletImage'],
                'mobileImage'  => $imagePaths['mobileImage'],
            ]);

            return response()->json(['message' => 'Banner created successfully', 'banner' => $banner], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while creating the banner', 'message' => $e->getMessage()], 500);
        }
    }



    public function update(BannerRequest $request, $id)
    {
        try {

            $banner = Banner::findOrFail($id);


            $banner->update([
                'title'          => $request->title ?? $banner->title,
                'description'    => $request->description ?? $banner->description,
                'link'           => $request->link ?? $banner->link,
                'expirationDate' => $request->expirationDate ?? $banner->expirationDate,
            ]);


            $imagePaths = $this->processImages($request, $banner->id);


            $banner->update([
                'desktopImage' => $imagePaths['desktopImage'] ?? $banner->desktopImage,
                'tabletImage'  => $imagePaths['tabletImage']  ?? $banner->tabletImage,
                'mobileImage'  => $imagePaths['mobileImage']  ?? $banner->mobileImage,
            ]);

            return response()->json(['message' => 'Banner updated successfully', 'banner' => $banner], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while updating the banner', 'message' => $e->getMessage()], 500);
        }
    }


    protected function processImages(BannerRequest $request, $bannerId)
    {
        $images = [
            'desktopImage' => ['name' => 'desktop', 'width' => 1920, 'height' => 780],
            'tabletImage'  => ['name' => 'tablet',  'width' => 768,  'height' => 450],
            'mobileImage'  => ['name' => 'mobile',  'width' => 425,  'height' => 450],
        ];

        $imagePaths = [];
        $bannerDir = "banners/{$bannerId}";

        if (!file_exists(storage_path("app/public/{$bannerDir}"))) {
            mkdir(storage_path("app/public/{$bannerDir}"), 0755, true);
        }

        $manager = new ImageManager(new Driver());

        foreach ($images as $key => $info) {
            if ($request->hasFile($key)) {
                $image = $request->file($key);

                // Validating the dimensions
                list($width, $height) = getimagesize($image);
                if ($width != $info['width'] || $height != $info['height']) {
                    throw new \Exception("The {$info['name']} image must be exactly {$info['width']}x{$info['height']} pixels.");
                }

                $fileName = "{$info['name']}.{$image->getClientOriginalExtension()}";
                $path = "{$bannerDir}/{$fileName}";

                // Saving the image in the correct directory
                $manager->read($image->getPathname())
                    ->resize($info['width'], $info['height'])
                    ->save(storage_path("app/public/{$path}"));

                $imagePaths[$key] = $path;
            }
        }

        return $imagePaths;
    }
}
