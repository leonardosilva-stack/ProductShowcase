<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Http\Requests\BrandRequest;
use Illuminate\Validation\ValidationException;


class BrandController extends Controller
{
    public function index()
    {
        try {
            $brands = Brand::all();
            return response()->json($brands);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while fetching brands', 'message' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $brand = Brand::findOrFail($id);
            return response()->json($brand);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Brand not found', 'message' => $e->getMessage()], 404);
        }
    }

    public function store(BrandRequest $request)
    {
        try {
            $validatedData = $request->validated();
            $brand = Brand::create($validatedData);
            return response()->json(['message' => 'Brand created successfully', 'brand' => $brand], 201);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while creating the brand', 'message' => $e->getMessage()], 500);
        }
    }


    public function update(BrandRequest $request, $id)
    {
        try {
            $brand = Brand::findOrFail($id);

            $validatedData = $request->validated();

            $brand->update($validatedData);

            return response()->json(['message' => 'Brand updated successfully', 'brand' => $brand], 200);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while updating the brand', 'message' => $e->getMessage()], 500);
        }
    }
}
