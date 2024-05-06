<?php

use Illuminate\Support\Facades\Route;


use App\Http\Controllers\UsersController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SaleReturnController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\PurchaseReturnController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SuplierController;
use App\Http\Controllers\ClaimController;

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\UserProfileController;

// Frontend Controllers....
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Gs1AppsController;






/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('user.auth.login');
// });

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth'])->name('dashboard');

require __DIR__ . '/auth.php';


// Route::get('/',[HomeController::class,'index'])->name('index');

Route::
        namespace('Auth')->middleware('guest')->group(function () {

            Route::get('/', [AuthenticatedSessionController::class, 'create'])->name('login');
            Route::post('/', [AuthenticatedSessionController::class, 'store'])->name('userlogin');
            Route::post('/check/email', [AuthenticatedSessionController::class, 'checkEmail'])->name('check.email');
            Route::post('/login/member', [AuthenticatedSessionController::class, 'loginMember'])->name('login.member');
        });

// Subscriber Routes Start Here.......
// Route::get('apps',[Gs1AppsController::class,'index'])->name('apps');
// Route::middleware(['auth'])->group(function () {
    Route::get('dashboard', [HomeController::class, 'index'])->name('dashboard');
    Route::get('view/used/items', [HomeController::class, 'viewUsedItems'])->name('view.used.items');
    Route::get('view/new/items', [HomeController::class, 'viewNewItems'])->name('view.new.items');
    // Route::get('profile', [UserProfileController::class, 'index'])->name('profile');
    // Route::post('profile/update/{id}', [UserProfileController::class, 'profileUpdate'])->name('profile.update');
    // Route::post('update/password', [UserProfileController::class, 'updatePassword']);


    /* Users Routes Start Here */
    Route::get('users', [UsersController::class, 'index'])->name('users');
    Route::post('users/list', [UsersController::class, 'List'])->name('unit.list');
    Route::get('profile', [UsersController::class, 'profile'])->name('profile');
    Route::post('profile/update', [UsersController::class, 'profileUpdate'])->name('profile.update');
    Route::post('update/password', [UsersController::class, 'updateCurrentUserPassword']);

    Route::get('user/create', [UsersController::class, 'create'])->name('user.create');
    Route::post('user/store', [UsersController::class, 'store'])->name('user.store');
    Route::get('user/edit/{id}', [UsersController::class, 'edit'])->name('user.edit');
    Route::post('user/update/{id}', [UsersController::class, 'update'])->name('user.update');
    Route::post('user/password/update/{id}', [UsersController::class, 'userPassUpdate'])->name('user.pass.update');
    Route::delete('user/delete/{id}', [UsersController::class, 'delete'])->name('user.delete');
    /* Users Routes Start Here */

    /* Roles Routes Start Here */
    Route::get('roles', [GroupController::class, 'index'])->name('roles');
    Route::post('roles/list', [GroupController::class, 'List'])->name('roles.list');
    Route::get('role/create', [GroupController::class, 'create'])->name('role.create');
    Route::post('role/store', [GroupController::class, 'store'])->name('role.store');
    Route::get('role/edit/{id}', [GroupController::class, 'edit'])->name('role.edit');
    Route::post('role/update/{id}', [GroupController::class, 'update'])->name('role.update');
    Route::delete('role/delete/{id}', [GroupController::class, 'delete'])->name('role.delete');
    /* Roles Routes Start Here */

    /* User Products Routes Start Here */
    Route::get('products', [ProductController::class, 'index'])->name('products');
    Route::post('product/list', [ProductController::class, 'List'])->name('product.list');
    Route::get('load/units', [ProductController::class, 'loadUnits']);
    Route::get('product/create', [ProductController::class, 'create'])->name('product.create');
    Route::post('product/store', [ProductController::class, 'store'])->name('product.store');
    // Route::get('product/edit/{id}', [ProductController::class, 'edit'])->name('product.edit');
    Route::get('product/view/{slug?}', [ProductController::class, 'view'])->name('product.view');
    Route::get('product/items/{slug?}', [ProductController::class, 'items'])->name('product.items');
    Route::get('product/edit', [ProductController::class, 'edit'])->name('product.edit');
    Route::post('product/update', [ProductController::class, 'update'])->name('product.update');
    Route::get('product/delete/{id}', [ProductController::class, 'delete'])->name('product.delete');
    Route::get('autocomplete/product', [ProductController::class, 'autocompleteProduct']);
    // Route::get('load/products',[ProductController::class,'loadProducts']); // not in used for now.
    Route::post('product/items_list', [ProductController::class, 'productItems']);
    Route::get('products/stock/list', [ProductController::class, 'productStockList'])->name('products.stock');
    Route::post('product/stock_list', [ProductController::class, 'productStock']);
    Route::post('update/item/selling/price/{itemID}', [ProductController::class, 'updateItemSellingPrice'])->name('update.item.selling.price');


    Route::post('get-gpc-based-on-productname',[ProductController::class,'getGpcBasedOnProductName']);
    Route::post('hscodes-based-on-selected-gpc-productname',[ProductController::class,'getHscodesBasedOnGpcProductName']);
    /* User Products Routes End Here */


    /* Unit Routes Ends Here */

    /* Sales Routes Start Here */
    Route::get('sales', [SaleController::class, 'index'])->name('sales');
    Route::post('sales/list', [SaleController::class, 'List'])->name('sale.list');
    Route::get('pos/print_invoice_pos/{invoice_no}', [SaleController::class, 'view'])->name('sale.view');
    Route::get('pos', [SaleController::class, 'pos'])->name('pos');
    Route::get('find/product/by/barcode', [SaleController::class, 'findProduct']);
    Route::post('sale/store', [SaleController::class, 'store'])->name('sale.store');
    Route::get('sale/edit/{id}', [SaleController::class, 'edit'])->name('sale.edit');
    Route::post('sale/update/{id}', [SaleController::class, 'update'])->name('sale.update');
    Route::delete('sale/delete/{id}', [SaleController::class, 'delete'])->name('sale.delete');

    /* Sales Routes Ends Here */



// });
Route::post('log-out', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
