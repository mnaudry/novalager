<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::get('/products',[ProductController::class,'index'])->name('product_index');


Route::get('/products/{product}',[ProductController::class, 'show'])->name('product_show');

Route::get('/products/{slug}/product',[ProductController::class, 'showSlug'])->name('product_show_slug');


Route::post('/products',[ProductController::class, 'store'])->name('product_store');