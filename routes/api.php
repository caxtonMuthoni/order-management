<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderDetailController;
use App\Http\Controllers\SupplierProductController;
use GuzzleHttp\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::group([
//     'middleware' => 'auth:api'
// ], function () {
    // Product routes
    Route::group([
        'prefix' => 'products'
    ], function () {

        Route::get('/all', [ProductController::class, 'index']);
        Route::post('/add', [ProductController::class, 'store']);
        Route::put('/update/{id}', [ProductController::class, 'update']);
        Route::delete('/delete/{id}', [ProductController::class, 'destroy']);
        Route::post('/search', [ProductController::class, 'search']);
    });

    // Supplier routes
    Route::group([
        'prefix' => 'suppliers'
    ], function () {

        Route::get('/all', [SupplierController::class, 'index']);
        Route::post('/add', [SupplierController::class, 'store']);
        Route::get('/supplier/{id}', [SupplierController::class, 'show']);
        Route::put('/update/{id}', [SupplierController::class, 'update']);
        Route::delete('/delete/{id}', [SupplierController::class, 'destroy']);
    });

    // Order routes
    Route::group([
        'prefix' => 'orders'
    ], function () {

        Route::get('/all', [OrderController::class, 'index']);
        Route::get('/order/{id}', [OrderController::class, 'show']);
        Route::post('/add', [OrderController::class, 'store']);
        Route::put('/update/{id}', [OrderController::class, 'update']);
        Route::delete('/delete/{id}', [OrderController::class, 'destroy']);
    });

     // OrderDetail routes
     Route::group([
        'prefix' => 'order/details/'
    ], function () {
        Route::post('/add', [OrderDetailController::class, 'store']);
        Route::delete('/delete/{id}', [OrderDetailController::class, 'destroy']);
    });

    // Supplier Products routes
    Route::group([
        'prefix' => 'supplier/products'
    ], function () {

        Route::get('/all', [SupplierProductController::class, 'index']);
        Route::post('/add', [SupplierProductController::class, 'store']);
        Route::put('/update/{id}', [SupplierProductController::class, 'update']);
        Route::get('/supplier-report', [SupplierProductController::class, 'getSupplierProucts']);
        Route::delete('/delete/{id}', [SupplierProductController::class, 'destroy']);
    });
// });


Route::get('/users',[ProductController::class, 'users']);

Route::fallback(function () {
    return response()->json([
        'status' => false,
        'message' => 'Page Not Found. If error persists, contact us for more info'
    ], 404);
});
