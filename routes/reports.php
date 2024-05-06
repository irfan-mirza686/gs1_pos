<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Reports\PosSaleReportController;
use App\Http\Controllers\Reports\SaleReturnReportController;
use App\Http\Controllers\Reports\PurchaseReportController;
use App\Http\Controllers\Reports\PurchaseReturnReportController;
use App\Http\Controllers\Reports\CustomerReportController;
use App\Http\Controllers\Reports\SupplierReportController;
use App\Http\Controllers\Reports\StockReportController;
use App\Http\Controllers\Reports\SoldItemController;

Route::
        namespace('Reports')->prefix('Report')->name('report.')->group(function () {
            Route::get('sales',[PosSaleReportController::class,'index'])->name('sales');
            Route::post('sales/list',[PosSaleReportController::class,'List'])->name('sales.list');

            Route::get('sale/items',[SoldItemController::class,'index'])->name('sale.items');
            Route::post('sale/items/list',[SoldItemController::class,'saleItemsList'])->name('sale.items.list');

            Route::get('SaleReturns',[SaleReturnReportController::class,'index'])->name('sale.returns');

            Route::get('Purchase',[PurchaseReportController::class,'index'])->name('purchase');

            Route::get('PurchaseReturns',[PurchaseReturnReportController::class,'index'])->name('purchase.returns');

            Route::get('Customers',[CustomerReportController::class,'index'])->name('customers');

            Route::get('Suppliers',[SupplierReportController::class,'index'])->name('suplliers');

            Route::get('Stock',[StockReportController::class,'index'])->name('stock');
            Route::post('stock/list',[StockReportController::class,'stockList'])->name('stock.list');
        });
