<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConfigurationRequest;
use App\Models\Configuration;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ConfigurationController extends Controller
{
    public function index()
    {
        try {
            $config = Configuration::first(); // Assuming there's only one configuration document
            return response()->json($config);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while fetching the configuration', 'message' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $config = Configuration::findOrFail($id);
            return response()->json($config);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Configuration not found', 'message' => $e->getMessage()], 404);
        }
    }

    public function store(ConfigurationRequest $request)
    {
        try {
            $validatedData = $request->validated();

            // Handle image processing
            if ($request->hasFile('image')) {
                $imagePath = $this->processImage($request->file('image'), 'site', 600, 360);
                $validatedData['image'] = $imagePath;
            }

            // Handle logo processing
            if ($request->hasFile('logo')) {
                $logoPath = $this->processImage($request->file('logo'), 'logos', 214, 60);
                $validatedData['logo'] = $logoPath;
            }

            $config = Configuration::create($validatedData);
            return response()->json(['message' => 'Configuration created successfully', 'config' => $config], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while creating the configuration', 'message' => $e->getMessage()], 500);
        }
    }

    public function update(ConfigurationRequest $request, $id)
    {
        try {
            $config = Configuration::findOrFail($id);
            $validatedData = $request->validated();

            // Handle image processing if a new image is uploaded
            if ($request->hasFile('image')) {
                $imagePath = $this->processImage($request->file('image'), 'site', 600, 360);
                $validatedData['image'] = $imagePath;
            }

            // Handle logo processing if a new logo is uploaded
            if ($request->hasFile('logo')) {
                $logoPath = $this->processImage($request->file('logo'), 'logos', 214, 60);
                $validatedData['logo'] = $logoPath;
            }

            $config->update($validatedData);
            return response()->json(['message' => 'Configuration updated successfully', 'config' => $config], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while updating the configuration', 'message' => $e->getMessage()], 500);
        }
    }

    protected function processImage($image, $dir, $width, $height)
    {
        $path = storage_path("app/public/{$dir}");
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }

        $manager = new ImageManager(new Driver());

        // Validating the dimensions
        list($imageWidth, $imageHeight) = getimagesize($image);
        if ($imageWidth != $width || $imageHeight != $height) {
            throw new \Exception("The image must be exactly {$width}x{$height} pixels.");
        }

        $fileName = uniqid() . '.' . $image->getClientOriginalExtension();
        $filePath = "{$dir}/{$fileName}";

        // Saving the image in the correct directory
        $manager->read($image->getPathname())
            ->resize($width, $height)
            ->save(storage_path("app/public/{$filePath}"));

        return $filePath;
    }
}
