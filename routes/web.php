<?php

use App\Http\Controllers\ManageStockController;
use App\Http\Controllers\StockTransferController;
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
use App\Http\Controllers\BrandController;






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


Route::get('/',[HomeController::class,'index'])->name('index');

Route::
        namespace('Auth')->middleware('guest')->group(function () {


            Route::match(['get','post'],'/', [AuthenticatedSessionController::class, 'create'])->name('login');
            Route::post('/', [AuthenticatedSessionController::class, 'store'])->name('userlogin');
            Route::post('/check/email', [AuthenticatedSessionController::class, 'checkEmail'])->name('check.email');
            Route::get('/member/activity', [AuthenticatedSessionController::class, 'memberActivity'])->name('member.activity');
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

    /* Customers Routes Start Here */
    Route::get('customers', [CustomerController::class, 'index'])->name('customers');
    Route::post('customers/list', [CustomerController::class, 'List'])->name('customer.list');
    Route::get('customer/create', [CustomerController::class, 'create'])->name('customer.create');
    Route::post('customer/store', [CustomerController::class, 'store'])->name('customer.store');
    Route::get('customer/edit/{id}', [CustomerController::class, 'edit'])->name('customer.edit');
    Route::post('customer/update/{id}', [CustomerController::class, 'update'])->name('customer.update');
    Route::delete('customer/delete/{id}', [CustomerController::class, 'delete'])->name('customer.delete');
    Route::get('autocomplete/customer', [CustomerController::class, 'autocompleteCustomer']);
    Route::post('import/customers', [CustomerController::class, 'importCustomers'])->name('import.customers');
    /* Customers Routes Ends Here */

     /* Brands Routes Start Here */
     Route::get('brands', [BrandController::class, 'index'])->name('brands');
     Route::post('brands/list', [BrandController::class, 'List'])->name('brand.list');
     Route::get('brand/create', [BrandController::class, 'create'])->name('brand.create');
     Route::post('brand/store', [BrandController::class, 'store'])->name('brand.store');
     Route::get('brand/edit/{id}', [BrandController::class, 'edit'])->name('brand.edit');
     Route::post('brand/update/{id}', [BrandController::class, 'update'])->name('brand.update');
     Route::delete('brand/delete/{id}', [BrandController::class, 'delete'])->name('brand.delete');
     /* Brands Routes Ends Here */

      /* Unit Routes Start Here */
    Route::get('units', [UnitController::class, 'index'])->name('units');
    Route::post('units/list', [UnitController::class, 'List'])->name('unit.list');
    Route::get('unit/create', [UnitController::class, 'create'])->name('unit.create');
    Route::post('unit/store', [UnitController::class, 'store'])->name('unit.store');
    Route::get('unit/edit/{id}', [UnitController::class, 'edit'])->name('unit.edit');
    Route::post('unit/update/{id}', [UnitController::class, 'update'])->name('unit.update');
    Route::delete('unit/delete/{id}', [UnitController::class, 'delete'])->name('unit.delete');
    /* Unit Routes Ends Here */

    /* User Products Routes Start Here */
    Route::get('products', [ProductController::class, 'index'])->name('products');
    Route::post('product/list', [ProductController::class, 'List'])->name('product.list');
    Route::get('load/units', [ProductController::class, 'loadUnits']);
    Route::post('product/create', [ProductController::class, 'create'])->name('product.create');
    Route::post('product/store', [ProductController::class, 'store'])->name('product.store');
    // Route::get('product/edit/{id}', [ProductController::class, 'edit'])->name('product.edit');
    Route::get('product/view/{slug?}', [ProductController::class, 'view'])->name('product.view');
    Route::get('product/items/{slug?}', [ProductController::class, 'items'])->name('product.items');
    Route::get('product/edit', [ProductController::class, 'edit'])->name('product.edit');
    Route::post('product/update/{id}', [ProductController::class, 'update'])->name('product.update');
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


    /* Manage Stock Routes Start */
    Route::get('manage/stock', [ManageStockController::class, 'index'])->name('stock');
    Route::post('stock/list', [ManageStockController::class, 'List'])->name('stock.list');
    /* Manage Stock Routes Ends  */

       /* Stock Transfer Routes Start */
       Route::get('stock/transfer/requests', [StockTransferController::class, 'index'])->name('stock.transfer.requests');
       Route::post('stock/transfer/list', [StockTransferController::class, 'List'])->name('stock.transfers.request.list');
       Route::get('search/products', [StockTransferController::class, 'searchProducts'])->name('search.products');
       Route::get('view/stock/request/{id}', [StockTransferController::class, 'viewStockRequest'])->name('view.stock.request');
       Route::post('save/stock/transfer/request', [StockTransferController::class, 'saveStockTransferReq'])->name('save.stock.transfer.req');
       /* Stock Transfer Routes Ends  */



// });
Route::post('log-out', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
