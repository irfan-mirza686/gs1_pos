<?php

use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\SaleController;
use App\Http\Controllers\Api\UnitController;
use App\Http\Controllers\Api\ZatcaController;
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
// Route::middleware('auth:api')->group(function(){

// });

Route::get('/csrf-token', function () {
    return response()->json(['csrfToken' => csrf_token()]);
})->middleware(['web']);

Route::post('login',[LoginController::class,'index']);
Route::get('brands',[BrandController::class,'index']);
Route::post('brand/create',[BrandController::class,'store']);
Route::get('brand/{id}',[BrandController::class,'edit']);
Route::post('brand/update/{id}',[BrandController::class,'update']);
Route::delete('brand/delete/{id}',[BrandController::class,'delete']);

Route::get('units',[UnitController::class,'index']);
Route::post('unit/create',[UnitController::class,'store']);
Route::get('unit/{id}',[UnitController::class,'edit']);
Route::post('unit/update/{id}',[UnitController::class,'update']);
Route::delete('unit/delete/{id}',[UnitController::class,'delete']);

Route::get('customers',[CustomerController::class,'index']);
Route::post('customer/create',[CustomerController::class,'store']);
Route::get('customer/{id}',[CustomerController::class,'edit']);
Route::post('customer/update/{id}',[CustomerController::class,'update']);
Route::delete('customer/delete/{id}',[CustomerController::class,'delete']);
Route::get('search/customer',[CustomerController::class,'searchCustomer']);

Route::get('products',[ProductController::class,'index']);
Route::post('product/create',[ProductController::class,'store']);
Route::get('product/{id}',[ProductController::class,'edit']);
Route::post('product/update/{id}',[ProductController::class,'update']);
Route::delete('product/delete/{id}',[ProductController::class,'delete']);

Route::get('find/product',[SaleController::class,'findProduct']);
Route::post('save/sale',[SaleController::class,'saveSale']);


Route::post('post/zatca',[ZatcaController::class,'postZatcaData']);

