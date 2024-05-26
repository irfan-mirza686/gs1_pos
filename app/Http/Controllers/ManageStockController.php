<?php

namespace App\Http\Controllers;

use App\Services\ProductService;
use DataTables;
use Illuminate\Http\Request;

class ManageStockController extends Controller
{
    private $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }
    /********************************************************************/
    public function index()
    {
        $pageTitle = "Manage Stock";
        $user_info = session('user_info');

        return view('user.stock.manage_stock.index', compact('pageTitle', 'user_info'));
    }
    public function List(Request $request)
    {
        if ($request->ajax()) {
            $user_info = session('user_info');

            $token = $user_info['token'];
            $gs1MemberID = $user_info['memberData']['id'];
            $products = $this->productService->getAllProducts($token,$gs1MemberID);
            // echo "<pre>"; print_r($products); exit;

            return DataTables::of($products)
                ->addIndexColumn()
                ->editColumn('image', function ($row) {

                    return '<img src="' . $row['image'] . '" border="0"
                    width="50" height="50" class="img-rounded product-img-2" align="center" />';
                })
                ->editColumn('type', function ($row) {
                    // return $row['type'];

                    if ($row['type'] == 'gs1_product') {
                        return '<span class="badge bg-gradient-quepal text-white shadow-sm w-100">' . strtoupper($row['type']) . '</span>';
                        // return '<span class="badge bg-info" style="width:100px;">' . strtoupper($row['type']) . '</span>';
                    } else {
                        return '<span class="badge bg-gradient-blooker text-white shadow-sm w-100">' . strtoupper($row['type']) . '</span>';
                        // return '<span class="badge bg-primary" style="width:100px;">' . strtoupper($row['type']) . '</span>';
                    }
                })
                ->editColumn('productnameen', function ($row) {
                    return ($row['productnameenglish']) ? $row['productnameenglish'] : '';
                })
                ->editColumn('productnamear', function ($row) {
                    return ($row['productnamearabic']) ? $row['productnamearabic'] : '';
                })
                ->editColumn('brand', function ($row) {
                    return ($row['BrandName']) ? $row['BrandName'] : '';
                })
                ->editColumn('barcode', function ($row) {
                    return '<span class="badge bg-success" style="width:120px;">' . strtoupper($row['barcode']) . '</span>';
                })

                ->addColumn('action', function ($row) {
                    if ($row['type'] == 'gs1_product') {
                        $edit = $row['barcode'];
                        $type = 'gs1';
                    } else {
                        $edit = $row['barcode'];
                        $type = 'non_gs1';
                    }
                    $url = route('product.edit');
                    $urlWithQueryString = $url . '?' . http_build_query(['product_type' => $type, 'barcode' => $edit]);
                    $btn = '<div class="col text-end">
                    <div class="dropdown">
                        <button class="btn btn-primary dropdown-toggle btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">Action</button>
                        <ul class="dropdown-menu" style="">

                            <li><a class="dropdown-item edit" href="' . $urlWithQueryString . '" ><i class="lni lni-pencil-alt" style="color: yelow;"></i> Edit</a>
                            </li>


                        </ul>
                    </div>
                </div>';

                    return $btn;
                })
                ->rawColumns(['type','image', 'barcode', 'action'])
                ->make(true);
        }
    }
}
