<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Brand;
use App\Http\Requests\ProductRequest;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductController extends Controller
{
    public function index()
    {
        try {
            $products = Product::all();
            return response()->json(['products' => $products], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while retrieving products', 'message' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $product = Product::findOrFail($id);
            return response()->json(['product' => $product], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Product not found'], 404);
        }
    }

    public function store(ProductRequest $request)
    {
        try {
            // Creating the product with images initially set to null
            $product = Product::create([
                'title'            => $request->title,
                'nutritionalTable' => $request->nutritionalTable,
                'brand_id'         => $request->brandId,
                'status'           => true,
                'image'            => null,
            ]);

            // Processing and saving the images
            $imagePath = $this->processImage($request, $product->id);

            // Updating the product with the image paths
            $product->update([
                'image' => $imagePath ?? null,
            ]);

            // Update the brand associated with the new product
            $brand = Brand::findOrFail($request->brandId);

            // Converting the collection to an array and adding the new product
            $productsArray = $brand->products->toArray();
            $productsArray[] = $product->_id;

            $brand->products = $productsArray;
            $brand->save();

            return response()->json(['message' => 'Product created successfully', 'product' => $product], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while creating the product', 'message' => $e->getMessage()], 500);
        }
    }

    public function update(ProductRequest $request, $id)
    {
        try {
            // Finding the existing product
            $product = Product::findOrFail($id);

            // Updating the provided fields
            $product->update([
                'title'            => $request->title ??  $product->title,
                'nutritionalTable' => $request->nutritionalTable ?? $product->nutritionalTable,
                'brand_id'         => $request->brandId  ?? $product->brand_id,
                'status'           => $request->status  ?? $product->status,
            ]);


            // Processing new images, if any
            if ($request->hasFile('image')) {
                $imagePath = $this->processImage($request, $id);
                $product->update(['image' => $imagePath]);
            }

            // Update the associated brand if the brand_id changes
            if ($request->brandId != $product->brand_id) {
                $oldBrand = Brand::find($product->brand_id);
                if ($oldBrand) {
                    $productsArray = array_diff($oldBrand->products->toArray(), [$product->id]);
                    $oldBrand->products = $productsArray;
                    $oldBrand->save();
                }

                $newBrand = Brand::findOrFail($request->brandId);
                $productsArray = $newBrand->products->toArray();
                $productsArray[] = $product->id;
                $newBrand->products = $productsArray;
                $newBrand->save();
            }

            return response()->json(['message' => 'Product updated successfully', 'product' => $product], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while updating the product', 'message' => $e->getMessage()], 500);
        }
    }

    protected function processImage(ProductRequest $request, $productId)
    {
        $imageInfo = [
            'width' => 700,
            'height' => 700,
        ];

        $imageDir = 'products';

        $path = storage_path("app/public/{$imageDir}");
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }

        $manager = new ImageManager(new Driver());

        if ($request->hasFile('image')) {
            $image = $request->file('image');

            // Validating the dimensions
            list($width, $height) = getimagesize($image);
            if ($width != $imageInfo['width'] || $height != $imageInfo['height']) {
                throw new \Exception("The product image must be exactly {$imageInfo['width']}x{$imageInfo['height']} pixels.");
            }


            $fileName = "{$productId}_product.{$image->getClientOriginalExtension()}";
            $filePath = "{$imageDir}/{$fileName}";

            // Saving the image in the correct directory
            $manager->read($image->getPathname())
                ->resize($imageInfo['width'], $imageInfo['height'])
                ->save(storage_path("app/public/{$filePath}"));

        }

        return $filePath;
    }
}
