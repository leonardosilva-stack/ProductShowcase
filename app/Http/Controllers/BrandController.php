<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;

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

    public function store(Request $request)
    {
        try {
            $brand = Brand::create($request->all());
            return response()->json(['message' => 'Brand created successfully', 'brand' => $brand], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while creating the brand', 'message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $brand = Brand::findOrFail($id);
            $brand->update($request->all());
            return response()->json(['message' => 'Brand updated successfully', 'brand' => $brand], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while updating the brand', 'message' => $e->getMessage()], 500);
        }
    }

}
