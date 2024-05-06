<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Stock;
use Illuminate\Http\Request;

class StockReportController extends Controller
{
    /****************************************************/
    public function index()
    {
        $pageTitle = "Stock Report";
        $products = Product::select('id', 'name')->get();
        return view('user.reports.stock.index', compact('pageTitle', 'products'));

    }
    /****************************************************/
    public function stockList(Request $request)
    {
        if ($request->ajax()) {
            $data = $request->all();
            if ($data['product_id'] == 'all') {
                $products = Product::select('id', 'name')->get()->toArray();


                $html['thsource'] = '<th class="text-center">Product Name</th>';
                $html['thsource'] .= '<th class="text-center">Barcode</th>';
                $html['thsource'] .= '<th class="text-center">Unit Price</th>';
                $html['thsource'] .= '<th class="text-center">Sale Price</th>';
                $html['thsource'] .= '<th class="text-center">Current Stock</th>';
                $html['thsource'] .= '<th class="text-center">Stock Value</th>';

                $html['tdsource'] = null;
                $totalAmount = 0;
                foreach ($products as $mainProduct) {
                    $mainProductID = $mainProduct['id'];
                    $mainProductName = $mainProduct['name'];

                    $variations = Stock::where('product_id', $mainProductID)->get()->toArray();
                    // echo "<pre>"; print_r($variations); exit();
                    foreach ($variations as $key => $variation) {
                        $var_title = $variation['productName'];
                        $var_unitPrice = $variation['price'];
                        $var_salePrice = $variation['selling_price'];
                        $var_qty = $variation['qty'];

                        $stockValue = (float) $var_qty * (float) $var_unitPrice;
                        $productDisplayName = $mainProductName . " " . $var_title;
                        $totalAmount = $stockValue + $totalAmount;


                        $html['tdsource'] .= '<tr><td>' . $var_title . '</td>';
                        $html['tdsource'] .= '<td>' . $variation['barcode'] . '</td>';
                        $html['tdsource'] .= '<td class="text-end">' . $var_unitPrice . '</td>';
                        $html['tdsource'] .= '<td class="text-end">' . $var_salePrice . '</td>';
                        $html['tdsource'] .= '<td class="text-center">' . $var_qty . '</td>';
                        $html['tdsource'] .= '<td class="text-end">' . $stockValue . '</td></tr>';
                    }


                }
                $html['tfootsource'] = '<tr style="background: gray; font-weight: bold; color:white;"><td colspan="5">Total</td><td style="text-align: right; font-weight: bold; color:white;">' . number_format($totalAmount) . '</td></tr>';
                return response(@$html);

            } else {
                $products = Product::select('id', 'name')->where('id', $data['product_id'])->get()->toArray();
                // echo "<pre>"; print_r($products); exit();

                $html['thsource'] = '<th class="text-center">Product Name</th>';
                $html['thsource'] .= '<th class="text-center">Barcode</th>';
                $html['thsource'] .= '<th class="text-center">Unit Price</th>';
                $html['thsource'] .= '<th class="text-center">Sale Price</th>';
                $html['thsource'] .= '<th class="text-center">Current Stock</th>';
                $html['thsource'] .= '<th class="text-center">Stock Value</th>';

                $html['tdsource'] = null;
                $totalAmount = 0;
                foreach ($products as $mainProduct) {
                    $mainProductID = $mainProduct['id'];
                    $mainProductName = $mainProduct['name'];

                    $variations = Stock::where('product_id', $mainProductID)->get();

                    foreach ($variations as $key => $variation) {
                        $var_title = $variation['productName'];
                        $var_unitPrice = $variation['price'];
                        $var_salePrice = $variation['selling_price'];
                        $var_qty = $variation['qty'];

                        $stockValue = (float) $var_qty * (float) $var_unitPrice;
                        $productDisplayName = $mainProductName . " " . $var_title;
                        $totalAmount = $stockValue + $totalAmount;

                        $html['tdsource'] .= '<tr><td>' . $var_title . '</td>';
                        $html['tdsource'] .= '<td>' . $variation['barcode'] . '</td>';
                        $html['tdsource'] .= '<td class="text-end">' . $var_unitPrice . '</td>';
                        $html['tdsource'] .= '<td class="text-end">' . $var_salePrice . '</td>';
                        $html['tdsource'] .= '<td class="text-center">' . $var_qty . '</td>';
                        $html['tdsource'] .= '<td class="text-end">' . $stockValue . '</td></tr>';
                    }


                }
                $html['tfootsource'] = '<tr style="background: gray; font-weight: bold; color:white;"><td colspan="5">Total</td><td style="text-align: right; font-weight: bold; color:white;">' . number_format($totalAmount) . '</td></tr>';
                return response(@$html);
            }
        }
    }
}
