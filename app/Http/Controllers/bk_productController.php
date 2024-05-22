<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Services\ProductService;
use App\Http\Requests\ProductRequest;
use Auth;
use DataTables;
use App\Models\Unit;
use App\Models\Stock;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Exception\RequestException;
use Session;
use Response;

class ProductController extends Controller
{
    private $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }
    /********************************************************************/
    public function index()
    {
        $pageTitle = "Products";
        $user_info = session('user_info');
        // echo "<pre>"; print_r($user_info); exit;
        // $products = Http::withHeaders([
        //     'Authorization' => 'Bearer ' . $user_info['token'],
        // ])->get('https://gs1ksa.org:3093/api/products', [
        //             'user_id' => $user_info['memberData']['id'],
        //         ]);


        // $productssBody = $products->getBody();
        // $productssData = json_decode($productssBody, true);

        // echo "<pre>"; print_r($productssData); exit;
        return view('user.product.index', compact('pageTitle', 'user_info'));
    }
    public function List(Request $request)
    {
        if ($request->ajax()) {
            $user_info = session('user_info');

            $token = $user_info['token'];
            $gs1MemberID = $user_info['memberData']['id'];
            $products = $this->productService->getAllProducts($token, $gs1MemberID);
            // echo "<pre>"; print_r($products); exit;

            return Datatables::of($products)
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
                ->rawColumns(['type', 'image', 'barcode', 'action'])
                ->make(true);
        }
    }
    public function loadUnits(Request $request)
    {
        if ($request->ajax()) {
            $units = Unit::where('status', 'active')->get();
            return response()->json(['units' => $units]);
        }
    }
    /**********************************************************************/
    public function create(Request $request)
    {
        $pageTitle = "Product Create";
        $user_info = session('user_info');
        try {
            $data = $request->all();
            $product_type = $data['product_type'];
            if ($product_type === 'gs1') {
                $productData = $this->productService->productData();
            } else {
                $productData = $this->productService->localProductData();
            }





            // echo "<pre>"; print_r($productData); exit;
            $title = "Create Product";
            return view('user.product.create', compact('pageTitle', 'productData', 'user_info', 'product_type'));
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                // Extract the error message from the response body
                $responseBody = $e->getResponse()->getBody()->getContents();
                $responseData = json_decode($responseBody, true);
                // echo "<pre>"; print_r($responseData['error']); exit;
                $errorMessage = isset($responseData['error']) ? $responseData['error'] : 'An unexpected error occurred.';
            } else {
                // If the response is not available, use a default error message
                $errorMessage = 'An unexpected error occurred.';
            }

            // You can log the error message
            \Log::error('Guzzle HTTP request failed: ' . $errorMessage);

            // Return an error response with the extracted error message
            return redirect()->back()->with('success', $errorMessage);
        } catch (\Throwable $th) {
            \Log::error('An unexpected error occurred: ' . $e->getMessage());

            // Return an error response
            return redirect()->back()->with('success', 'An unexpected error occurred. Please try again later.');
        }

    }
    /**********************************************************************/
    public function store(ProductRequest $request)
    {

        try {
            $data = $request->all();

            $user_info = session('user_info');
            // echo "<pre>"; print_r($data); exit;
            if ($data['product_type'] == 'gs1') {
                try {
                    $user_info = session('user_info');

                    $frontImagePath = $data['front_image'] ?? null;
                    $backImagePath = $data['back_image'] ?? null;
                    $image1Path = $data['image_1'] ?? null;
                    $image2Path = $data['image_2'] ?? null;
                    $image3Path = $data['image_3'] ?? null;

                    $request = Http::withHeaders([
                        'Authorization' => 'Bearer ' . $user_info['token'],
                    ]);

                    if ($frontImagePath) {
                        $request->attach('front_image', file_get_contents($frontImagePath), 'front_image.jpg');
                    }

                    if ($backImagePath) {
                        $request->attach('back_image', file_get_contents($backImagePath), 'back_image.jpg');
                    }

                    if ($image1Path) {
                        $request->attach('image_1', file_get_contents($image1Path), 'image_1.jpg');
                    }

                    if ($image2Path) {
                        $request->attach('image_2', file_get_contents($image2Path), 'image_2.jpg');
                    }

                    if ($image3Path) {
                        $request->attach('image_3', file_get_contents($image3Path), 'image_3.jpg');
                    }

                    $response = $request->post('https://gs1ksa.org:3093/api/products', [
                        'user_id' => $user_info['memberData']['id'],
                        'productnameenglish' => $data['productnameenglish'],
                        'productnamearabic' => $data['productnamearabic'],
                        'BrandName' => $data['BrandName'],
                        'BrandNameAr' => $data['BrandNameAr'],
                        'ProductType' => $data['ProductType'],
                        'Origin' => $data['Origin'],
                        'PackagingType' => $data['PackagingType'],
                        'unit' => $data['unit'],
                        'size' => $data['size'],
                        'gpc' => $data['gpc'],
                        'gpc_code' => '10000027',
                        'countrySale' => $data['countrySale'],
                        'HSCODES' => '1234.56.78',
                        'HsDescription' => $data['HsDescription'],
                        'gcp_type' => '1',
                        'prod_lang' => $data['prod_lang'],
                        'details_page' => $data['details_page'],
                        'details_page_ar' => $data['details_page_ar'],
                        'product_url' => $data['product_url'],
                    ]);

                    // $response = Http::withHeaders([
                    //     'Authorization' => 'Bearer ' . $user_info['token'],
                    // ])->post('https://gs1ksa.org:3093/api/products', $requestData);

                    $responseBody = $response->getBody();
                    $responseSaleData = json_decode($responseBody, true);
                    // echo "<pre>";
                    // print_r($responseSaleData);
                    // exit;
                    if (@$responseSaleData['error']) {
                        return redirect()->back()->with('flash_message_warning', @$responseSaleData['error']);
                    }
                    \LogActivity::addToLog(strtoupper($user_info['memberData']['company_name_eng']) . ' Added a gs1 product (' . $data['productnameenglish'] . ')', null);
                    return redirect(route('products'))->with('flash_message_success', 'Product successfully Added!');
                } catch (RequestException $e) {
                    if ($e->hasResponse()) {
                        // Extract the error message from the response body
                        $responseBody = $e->getResponse()->getBody()->getContents();
                        $responseData = json_decode($responseBody, true);
                        // echo "<pre>"; print_r($responseData['error']); exit;
                        $errorMessage = isset($responseData['error']) ? $responseData['error'] : 'An unexpected error occurred.';
                    } else {
                        // If the response is not available, use a default error message
                        $errorMessage = 'An unexpected error occurred.';
                    }

                    // You can log the error message
                    \Log::error('Guzzle HTTP request failed: ' . $errorMessage);

                    // Return an error response with the extracted error message
                    return redirect()->back()->with('flash_message_error', $errorMessage);
                } catch (\Throwable $th) {
                    \Log::error('An unexpected error occurred: ' . $th->getMessage());

                    // Return an error response
                    return redirect()->back()->with('flash_message_error', 'An unexpected error occurred. Please try again later.');
                }
            } else {
                $create = $this->productService->storeProduct($data, $id = null);

                $create->user_id = isset(Auth::user()->id) ? Auth::user()->id : 0;
                // echo "<pre>"; print_r($create); exit;
                \DB::beginTransaction();
                if ($create->save()) {
                    \LogActivity::addToLog(strtoupper($user_info['memberData']['company_name_eng']) . ' Added a gs1 product (' . $data['productnameenglish'] . ')', \Config::get('app.url') . '/product' . '/' . $create->slug);
                    \DB::commit();
                    return redirect(route('products'))->with('flash_message_success', 'Product successfully Added!');
                } else {
                    return redirect(route('products'))->with('flash_message_error', 'Data has not been saved!');
                }
            }

        } catch (\Throwable $th) {
            return redirect(route('products'))->with('flash_message_error', $th->getMessage());
        }

    }
    public function edit(Request $request)
    {

        $user_info = session('user_info');
        // dd($request->product_type);
        if ($request->product_type == 'gs1') {

            $productData = $this->productService->productData();
            $editData = Http::withHeaders([
                'Authorization' => 'Bearer ' . $user_info['token'],
            ])->get('https://gs1ksa.org:3093/api/products', [
                        'barcode' => $request->barcode,
                    ]);
            $editDataBody = $editData->getBody();
            $editProduct = json_decode($editDataBody, true);
            $editProduct = $editProduct[0];


            //     echo "<pre>";
            // print_r($editProduct);
            // exit;
            $product_type = 'gs1';
        } else {
            $productData = $this->productService->localProductData();
            $editProduct = Product::where('barcode', $request->barcode)->first();
            $product_type = 'non_gs1';
        }
        $pageTitle = "Edit Product";

        return view('user.product.edit', compact('pageTitle', 'editProduct', 'productData', 'user_info', 'product_type'));

    }
    /**********************************************************************/
    public function update(ProductRequest $request, $id = null)
    {

        // try {
        $data = $request->all();
        // echo "<pre>"; print_r($data); exit;
        $user_info = session('user_info');
        if ($request->product_type == 'gs1') {
            // try {
            // echo "<pre>"; print_r($data); exit;
            $frontImagePath = $data['front_image'];
            $backImagePath = $data['back_image'];
            $image1Path = $data['image_1'];
            $image2Path = $data['image_2'];
            $image3Path = $data['image_3'];

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $user_info['token'],
            ])
                ->attach('front_image', file_get_contents($frontImagePath), 'front_image.jpg')
                ->attach('back_image', file_get_contents($backImagePath), 'back_image.jpg')
                ->attach('image_1', file_get_contents($image1Path), 'image_1.jpg')
                ->attach('image_2', file_get_contents($image2Path), 'image_2.jpg')
                ->attach('image_3', file_get_contents($image3Path), 'image_3.jpg')
                ->put('https://gs1ksa.org:3093/api/products/gtin/' . $request->product_id, [
                    'user_id' => $user_info['memberData']['id'],
                    'productnameenglish' => $data['productnameenglish'],
                    'productnamearabic' => $data['productnamearabic'],
                    'BrandName' => $data['BrandName'],
                    'BrandNameAr' => $data['BrandNameAr'],
                    'ProductType' => $data['ProductType'],
                    'Origin' => $data['Origin'],
                    'PackagingType' => $data['PackagingType'],
                    'unit' => $data['unit'],
                    'size' => $data['size'],
                    'gpc' => $data['gpc'],
                    'gpc_code' => '10000027',
                    'countrySale' => $data['countrySale'],
                    'HSCODES' => '1234.56.78',
                    'HsDescription' => $data['HsDescription'],
                    'gcp_type' => '1',
                    'prod_lang' => $data['prod_lang'],
                    'details_page' => $data['details_page'],
                    'details_page_ar' => $data['details_page_ar'],
                    'product_url' => $data['product_url'],
                ]);

            // $response = Http::withHeaders([
            //     'Authorization' => 'Bearer ' . $user_info['token'],
            // ])->put('https://gs1ksa.org:3093/api/products/gtin/' . $request->product_id, $requestData);

            $responseBody = $response->getBody();
            $responseSaleData = json_decode($responseBody, true);

            if (@$responseSaleData['error']) {
                return redirect()->back()->with('flash_message_warning', @$responseSaleData['error']);
            }
            // echo "<pre>";
            // print_r($responseSaleData);
            // exit;
            \LogActivity::addToLog(strtoupper($user_info['memberData']['company_name_eng']) . ' Updated a gs1 product (' . $data['productnameenglish'] . ')', null);
            return redirect(route('products'))->with('flash_message_success', 'Product successfully Updated!');
            // } catch (RequestException $e) {
            //     if ($e->hasResponse()) {
            //         // Extract the error message from the response body
            //         $responseBody = $e->getResponse()->getBody()->getContents();
            //         $responseData = json_decode($responseBody, true);
            //         // echo "<pre>"; print_r($responseData['error']); exit;
            //         $errorMessage = isset($responseData['error']) ? $responseData['error'] : 'An unexpected error occurred.';
            //     } else {
            //         // If the response is not available, use a default error message
            //         $errorMessage = 'An unexpected error occurred.';
            //     }

            //     // You can log the error message
            //     \Log::error('Guzzle HTTP request failed: ' . $errorMessage);

            //     // Return an error response with the extracted error message
            //     return redirect()->back()->with('flash_message_error', $errorMessage);
            // } catch (\Throwable $th) {
            //     \Log::error('An unexpected error occurred: ' . $th->getMessage());

            //     // Return an error response
            //     return redirect()->back()->with('flash_message_error', 'An unexpected error occurred. Please try again later.');
            // }
        } else {
            $update = $this->productService->storeProduct($data, $id);
            $update->user_id = (Auth::user()) ? Auth::user()->id : 0;
            \DB::beginTransaction();
            if ($update->save()) {
                \LogActivity::addToLog(strtoupper($user_info['memberData']['company_name_eng']) . ' Updated a non gs1 product (' . $data['productnameenglish'] . ')', \Config::get('app.url') . '/product' . '/' . $update->slug);
                \DB::commit();
                return redirect(route('products'))->with('flash_message_success', 'Product successfully updated!');
            } else {
                return redirect(route('products'))->with('flash_message_success', 'Data has not been updated!');
            }
        }

        // } catch (\Throwable $th) {
        //     return redirect(route('products'))->with('flash_message_error', $th->getMessage());
        // }

    }
    /**********************************************************************/
    public function autocompleteProduct(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->productService->autoCompProduct($request);

            $dataRetrun = json_encode($data);
            return Response($dataRetrun);


        }
    }
    /**********************************************************************/
    public function loadProducts(Request $request)
    {
        if ($request->ajax()) {
            try {
                $products = Product::get();
                return response()->json(['status' => 200, 'products' => $products]);
            } catch (\Throwable $th) {
                return response()->json(['status' => 422, 'message' => $th->getMessage()]);
            }
        }
    }
    /**********************************************************************/
    public function productItems(Request $request)
    {
        if ($request->ajax()) {
            $data = Stock::with('supplier')->where('product_id', $request->productID)->get();
            // echo "<pre>"; print_r($data->toArray()); exit;
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('type', function ($row) {
                    if ($row->type == 'new') {
                        return '<span class="badge bg-success" style="width: 70px;">' . strtoupper($row->type) . '</span>';
                    } else if ($row->type == 'used') {
                        return '<span class="badge bg-warning" style="width: 70px;">' . strtoupper($row->type) . '</span>';
                    }

                })
                ->editColumn('supplier', function ($row) {

                    return ($row->supplier) ? $row->supplier->name : '';
                })
                ->addColumn('action', function ($row) {

                    $btn = '<div class="col text-end">
                    <div class="dropdown">
                        <button class="btn btn-primary dropdown-toggle btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">Action</button>
                        <ul class="dropdown-menu" style="">
                        <li><a class="dropdown-item updateProductItemSellingPrice" data-ProductID="' . $row->product_id . '" data-Type="' . $row->type . '" data-itemPrice="' . $row->selling_price . '" href="' . route('update.item.selling.price', $row->id) . '"><i class="lni lni-eye" style="color: blue;"></i> Update Item</a>
                            </li>


                        </ul>
                    </div>
                </div>';

                    return $btn;
                })
                ->rawColumns(['type', 'action'])
                ->make(true);
        }
    }
    /**********************************************************************/
    public function getGpcBasedOnProductName(Request $request)
    {
        if ($request->ajax()) {
            try {

                Session::put('page', 'addProduct');
                $user_info = session('user_info');

                $gpc = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $user_info['token'],
                ])->get('https://gs1ksa.org:4044/api/findSimilarRecords', [
                            'text' => trim($request->search),
                            'tableName' => 'gpc_bricks',
                        ]);

                $gpcBody = $gpc->getBody();
                $gpcData = json_decode($gpcBody, true);
                // echo "<pre>";
                // print_r($gpcData);
                // exit;
                if ($gpcData) {
                    return response()->json(['status' => 200, 'data' => $gpcData]);
                }
            } catch (RequestException $e) {
                if ($e->hasResponse()) {
                    // Extract the error message from the response body
                    $responseBody = $e->getResponse()->getBody()->getContents();
                    $responseData = json_decode($responseBody, true);
                    // echo "<pre>"; print_r($responseData['error']); exit;
                    $errorMessage = isset($responseData['error']) ? $responseData['error'] : 'An unexpected error occurred.';
                } else {
                    // If the response is not available, use a default error message
                    $errorMessage = 'An unexpected error occurred.';
                }

                // You can log the error message
                \Log::error('Guzzle HTTP request failed: ' . $errorMessage);

                // Return an error response with the extracted error message
                return response()->json(['status' => 404, 'error' => $errorMessage], 404);
            } catch (\Throwable $th) {
                \Log::error('An unexpected error occurred: ' . $th->getMessage());

                // Return an error response
                return response()->json(['error' => 'An unexpected error occurred. Please try again later.'], 500);
            }

        }
    }
    public function getHscodesBasedOnGpcProductName(Request $request)
    {
        if ($request->ajax()) {
            try {

                Session::put('page', 'addProduct');
                $user_info = session('user_info');

                $hsCode = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $user_info['token'],
                ])->get('https://gs1ksa.org:4044/api/findSimilarRecords', [
                            'text' => $request->title ?? $request->productName,
                            'tableName' => 'hs_codes',
                        ]);
                $hsCodeBody = $hsCode->getBody();
                $hsCodeData = json_decode($hsCodeBody, true);
                // echo "<pre>";
                // print_r($hsCodeData);
                // exit;
                if ($hsCodeData) {
                    return response()->json(['status' => 200, 'data' => $hsCodeData]);
                }
            } catch (RequestException $e) {
                if ($e->hasResponse()) {
                    // Extract the error message from the response body
                    $responseBody = $e->getResponse()->getBody()->getContents();
                    $responseData = json_decode($responseBody, true);
                    // echo "<pre>"; print_r($responseData['error']); exit;
                    $errorMessage = isset($responseData['error']) ? $responseData['error'] : 'An unexpected error occurred.';
                } else {
                    // If the response is not available, use a default error message
                    $errorMessage = 'An unexpected error occurred.';
                }

                // You can log the error message
                \Log::error('Guzzle HTTP request failed: ' . $errorMessage);

                // Return an error response with the extracted error message
                return response()->json(['status' => 404, 'error' => $errorMessage], 404);
            } catch (\Throwable $th) {
                \Log::error('An unexpected error occurred: ' . $th->getMessage());

                // Return an error response
                return response()->json(['error' => 'An unexpected error occurred. Please try again later.'], 500);
            }
        }
    }
}
