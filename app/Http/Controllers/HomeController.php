<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Http;
use Illuminate\Http\Request;
use DB;
use App\Models\User;
use App\Models\Product;
use App\Models\Stock;
use Auth;


class HomeController extends Controller
{
    public function index()
    {

        function rand_color()
        {
            return sprintf('#%06X', mt_rand(0, 0xFFFFFF));
        }
        $pageTitle = "Dashboard";

        $user_info = session('user_info');
        $sales = Sale::get();
        $total_sales = $sales->count();
        $total_sales_amount = $sales->sum('total');
        $totalVat = 0;
        foreach ($sales as $key => $value) {
            // echo "<pre>"; print_r($value['items']); exit;
            $products = $value['items'];
            foreach ($products as $product) {
                $totalVat += $product['vat_total'];
            }
        }
        $products = Product::get();
        $local_products = $products->count();

        // Retrieve items from the database
        $items = Sale::select('items', 'created_at')->get(); // Assuming 'items' is the JSON column name

        $productTypeCounts = [];
        $months = [];

        // Initialize product type counts for each month
        for ($i = 1; $i <= 12; $i++) {
            $productTypeCounts['non_gs1'][$i] = 0;
            $productTypeCounts['gs1'][$i] = 0;
            $months[$i] = date('F', mktime(0, 0, 0, $i, 1)); // Get month name (e.g., January, February, etc.)
        }
        // echo "<pre>"; print_r($items->toArray()); exit;
        // Process each item
        foreach ($items as $item) {
            $products = $item;

            foreach ($products['items'] as $product) {
                // echo "<pre>"; print_r($product); exit;
                $month = date('n', strtotime($item['created_at'])); // Assuming you have a 'created_at' field
                $productType = $product['product_type'];

                if (isset($productTypeCounts[$productType][$month])) {
                    $productTypeCounts[$productType][$month]++;
                }
            }
        }
// echo "<pre>"; print_r(array_values($productTypeCounts['non_gs1'])); exit;
        // Prepare data for the bar chart
        // $data = [
        //     'labels' => array_values($months),
        //     'datasets' => [
        //         [
        //             'label' => 'Non GS1 Products',
        //             'backgroundColor' => 'rgba(255, 99, 132, 0.5)',
        //             'data' => array_values($productTypeCounts['non_gs1']),
        //         ],
        //         [
        //             'label' => 'GS1 Products',
        //             'backgroundColor' => 'rgba(54, 162, 235, 0.5)',
        //             'data' => array_values($productTypeCounts['gs1']),
        //         ],
        //     ],
        // ];

        // echo "<pre>"; print_r($data); exit;

        $apiProducts = Http::withHeaders([
            'Authorization' => 'Bearer ' . $user_info['token'],
        ])->get('https://gs1ksa.org:3093/api/products', [
                    'user_id' => $user_info['memberData']['id'],
                ]);


        $apiProductsBody = $apiProducts->getBody();
        $apiProductssData = json_decode($apiProductsBody, true);
        $totalGs1Products = count($apiProductssData);
        $pieChartData = [$totalGs1Products,$local_products];

        $total_products = $local_products + $totalGs1Products;
        session(['gs1Products' => array_values($productTypeCounts['gs1']),'nonGs1Product'=>array_values($productTypeCounts['non_gs1']),'pieChartData'=>$pieChartData]);
// echo "<pre>"; print_r(count($apiProductssData)); exit;
        return view('user.master_dashboard', compact('pageTitle','user_info','total_sales','total_sales_amount','totalVat','total_products','totalGs1Products','local_products'));
    }
    public function viewUsedItems(Request $request)
    {
        // echo "<pre>"; print_r($request->all()); exit;
        if ($request->ajax()) {
            $stock = Stock::select('id','productName','barcode','qty')->where('qty', '>' , 0)->where('type',$request->type)->get();
            if ($stock) {
                return response()->json(['status'=>200,'total_stock'=>$stock]);
            }
        }
    }
    public function viewNewItems(Request $request)
    {
        // echo "<pre>"; print_r($request->all()); exit;
        if ($request->ajax()) {
            $stock = Stock::select('id','productName','barcode','qty','product_id')->where('qty', '>' , 0)->where('type',$request->type)->groupby('product_id')->get();
            if ($stock) {
                return response()->json(['status'=>200,'total_stock'=>$stock]);
            }
        }
    }

}
