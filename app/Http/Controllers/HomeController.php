<?php

namespace App\Http\Controllers;

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

        return view('user.master_dashboard', compact('pageTitle','user_info'));
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
