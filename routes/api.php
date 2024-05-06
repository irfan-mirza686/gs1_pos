<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\JournalMasterController;


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

Route::post('journal/master/{email?}',[JournalMasterController::class,'journalMaster']);
Route::post('journal/details/create',[JournalMasterController::class,'storeJournalDetails']);
Route::post('login',[App\Http\Controllers\Api\Auth\LoginController::class,'store']);
Route::post('update/pasword',[App\Http\Controllers\Api\Auth\LoginController::class,'updatePassword']);
Route::post('update/profile',[App\Http\Controllers\Api\UpdateProfileController::class,'updateProfile']);
Route::post('products/list',[App\Http\Controllers\Api\ProductApiController::class,'productsList']);
Route::post('product/search/description',[App\Http\Controllers\Api\ProductApiController::class,'productSearchDescription']);
Route::post('product/search/gtin',[App\Http\Controllers\Api\ProductApiController::class,'productSearchGTIN']);
Route::post('product/search/sku',[App\Http\Controllers\Api\ProductApiController::class,'productSearchSKU']);
Route::post('update/product',[App\Http\Controllers\Api\ProductApiController::class,'UpdateProduct']);

