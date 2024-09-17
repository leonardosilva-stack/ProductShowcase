<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\ConfigurationController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/brands', [BrandController::class, 'index']);
Route::get('/brands/{id}', [BrandController::class, 'show']);
Route::post('/brands', [BrandController::class, 'store']);
Route::put('/brands/{id}', [BrandController::class, 'update']);


Route::get('/banners', [BannerController::class, 'index']);
Route::get('/banners/{id}', [BannerController::class, 'show']);
Route::post('/banners', [BannerController::class, 'store']);
Route::put('/banners/{id}', [BannerController::class, 'update']);


Route::get('/configurations', [ConfigurationController::class, 'index'])->name('configurations.index');
Route::get('/configurations/{id}', [ConfigurationController::class, 'show'])->name('configurations.show');
Route::post('/configurations', [ConfigurationController::class, 'store'])->name('configurations.store');
Route::put('/configurations/{id}', [ConfigurationController::class, 'update'])->name('configurations.update');


Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);
Route::post('/products', [ProductController::class, 'store']);
Route::put('/products/{id}', [ProductController::class, 'update']);
