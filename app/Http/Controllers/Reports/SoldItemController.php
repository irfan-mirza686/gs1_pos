<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\Request;

class SoldItemController extends Controller
{
    public function index()
    {
        $pageTitle = "Sales Report";
        $products = Product::get();
        return view('user.reports.sale_items.index',compact('pageTitle','products'));
    }
    public function saleItemsList(Request $request)
    {
        if ($request->ajax()) {
            $data = $request->all();
            $startDate = date('Y-m-d',strtotime($data['startDate']));
            $endDate = date('Y-m-d',strtotime($data['endDate']));
            // echo "<pre>"; print_r($data); exit();
            if ($data['product_id']=='all') {
                $products = Sale::with('customer')->where('status','confirmed')->whereBetween('date',[$startDate,$endDate])->get()->toArray();

                // echo "<pre>"; print_r($products); exit();
                $html['thsource'] = '<th>#</th>';
                $html['thsource'] .= '<th>Invoice#</th>';
                $html['thsource'] .= '<th>Date</th>';
                $html['thsource'] .= '<th>Customer</th>';
                $html['thsource'] .= '<th>Type</th>';
                $html['thsource'] .= '<th>Product Name</th>';
                $html['thsource'] .= '<th>Barcode</th>';
                $html['thsource'] .= '<th>Selling Price</th>';
                $html['thsource'] .= '<th>Item Sales Count</th>';
                $html['thsource'] .= '<th>Sales Amount</th>';

                $html['tdsource'] = null;
                $totalAmount = 0;
                $returnTotalAmount = 0;
                $counter = 0;
                foreach ($products as $productKey => $mainProduct) {
                    $mainProductID = $mainProduct['id'];
                    $invoice_no = $mainProduct['order_no'];
                    $customerName = $mainProduct['customer']['name'];
                    $date = date('d M Y',strtotime($mainProduct['date']));
                    $variations = $mainProduct['items'];
                    // echo "<pre>"; print_r($variations); exit;
                    foreach ($variations as $key => $variation){
                        $product_id = $variation['product_id'];
                        $productName = $variation['productName'];
                        $type = $variation['type'];
                        $barcode = $variation['barcode'];
                        $selling_price = $variation['price'];
                        $quantity = $variation['qty'];
                        $amount = $variation['sub_total'];

                        $totalAmount = $variation['sub_total'] + $totalAmount;

                    $soldValue = $quantity * $selling_price;
                    $counter = $counter+1;

                    $html['tdsource'] .= '<tr><td>'.$counter.'</td>';
                    $html['tdsource'] .= '<td><a target="_blank" href="' .url("sale-invoice").'/'.$mainProduct['id'].'">'.$invoice_no.'</a></td>';
                    $html['tdsource'] .= '<td>'.$date.'</td>';
                    $html['tdsource'] .= '<td>'.$customerName.'</td>';
                    $html['tdsource'] .= '<td>'.$type.'</td>';
                    $html['tdsource'] .= '<td>'.$productName.'</td>';
                    $html['tdsource'] .= '<td>'.$barcode.'</td>';
                    $html['tdsource'] .= '<td style="text-align: right;">'.$selling_price.'</td>';
                    $html['tdsource'] .= '<td style="text-align: center;">'.$quantity.'</td>';
                    $html['tdsource'] .= '<td style="text-align: right;">'.$soldValue.'</td></tr>';
                    }
                    $returnTotalAmount = $totalAmount;


                }
                $html['tfootsource'] = '<tr><td colspan="9" style="background: gray; font-weight: bold; color:white;">Total</td><td style="text-align: right; background: gray; font-weight: bold; color:white;">'.$returnTotalAmount.'</td></tr>';

                return response(@$html);

            }else{
                $products = Sale::with('customer')->where('status','confirmed')->whereBetween('date',[$startDate,$endDate])->get()->toArray();
                // echo "<pre>"; print_r($products); exit();

                $html['thsource'] = '<th>#</th>';
                $html['thsource'] .= '<th>Invoice#</th>';
                $html['thsource'] .= '<th>Date</th>';
                $html['thsource'] .= '<th>Customer</th>';
                $html['thsource'] .= '<th>Type</th>';
                $html['thsource'] .= '<th>Product Name</th>';
                $html['thsource'] .= '<th>Barcode</th>';
                $html['thsource'] .= '<th>Selling Price</th>';
                $html['thsource'] .= '<th>Item Sales Count</th>';
                $html['thsource'] .= '<th>Sales Amount</th>';

                $html['tdsource'] = null;
                $itemSoldCount = 0;
                $returnItemSoldCount = 0;
                $totalAmount = 0;
                $returnTotalAmount = 0;
                $counter = 0;
                foreach ($products as $mainProduct) {
                    // echo "<pre>"; print_r($mainProduct); exit();
                    $mainProductID = $mainProduct['id'];
                    $invoice_no = $mainProduct['order_no'];
                    $customerName = $mainProduct['customer']['name'];
                    $date = date('d M Y',strtotime($mainProduct['date']));
                    $variations = $mainProduct['items'];


                    foreach ($variations as $keys => $variation){
                    if ($variation['product_id']==$data['product_id']) {

                        $product_id = $variation['product_id'];
                        $productName = $variation['productName'];
                        $type = $variation['type'];
                        $barcode = $variation['barcode'];
                        $selling_price = $variation['price'];
                        $quantity = $variation['qty'];
                        $amount = $variation['sub_total'];

                        $itemSoldCount = $variation['qty'] + $itemSoldCount;
                        $totalAmount = $variation['sub_total'] + $totalAmount;

                    $soldValue = $quantity * $selling_price;
                    $counter = $counter+1;

                    $html['tdsource'] .= '<tr><td>'.$counter.'</td>';
                    $html['tdsource'] .= '<td><a target="_blank" href="' .url("sale-invoice").'/'.$mainProduct['id'].'">'.$invoice_no.'</a></td>';
                    $html['tdsource'] .= '<td>'.$date.'</td>';
                    $html['tdsource'] .= '<td>'.$customerName.'</td>';
                    $html['tdsource'] .= '<td>'.$type.'</td>';
                    $html['tdsource'] .= '<td>'.$productName.'</td>';
                    $html['tdsource'] .= '<td>'.$barcode.'</td>';
                    $html['tdsource'] .= '<td style="text-align: right;">'.$selling_price.'</td>';
                    $html['tdsource'] .= '<td style="text-align: center;">'.$quantity.'</td>';
                    $html['tdsource'] .= '<td style="text-align: right;">'.$soldValue.'</td></tr>';
                    }
                }
                    $returnItemSoldCount = $itemSoldCount;
                    $returnTotalAmount = $totalAmount;

                }
                $html['tfootsource'] = '<tr><td colspan="8" style="background: gray; font-weight: bold; color:white;">Total</td><td style="text-align: center; background: gray; font-weight: bold; color:white;">'.$returnItemSoldCount.'</td><td style="text-align: right; background: gray; font-weight: bold; color:white;">'.$returnTotalAmount.'</td></tr>';
                return response(@$html);
            }
        }
    }
}
