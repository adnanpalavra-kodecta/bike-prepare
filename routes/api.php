<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductTypeController;
use App\Http\Controllers\VariantController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => ['auth:sanctum', 'admin']], function () {
    Route::resource('productType', ProductTypeController::class)->except('index', 'show', 'create', 'edit');
    Route::resource('variant', VariantController::class)->except('index', 'show', 'create', 'edit');
    Route::resource('product', ProductController::class)->except('index', 'show', 'create', 'edit');
    Route::post('product/{product}/activate', [ProductController::class, 'activate']);
    Route::get('product/{product}/draft', [ProductController::class, 'draft']);
    Route::get('product/{product}/delete', [ProductController::class, 'delete']);
});

Route::get('productType', [ProductTypeController::class, 'index']);
Route::get('productType/{productType}', [ProductTypeController::class, 'show']);

Route::get('product', [ProductController::class, 'index']);
Route::get('product/{product}', [ProductController::class, 'show']);

Route::get('variant', [VariantController::class, 'index']);
Route::get('variant/{variant}', [VariantController::class, 'show']);


Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
