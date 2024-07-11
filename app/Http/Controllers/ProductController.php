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
    public function authenticateRole($roles = null)
    {


        $permissionRole = [];
        foreach ($roles as $key => $value) {

            $permissionCheck = checkRolePermission($value);

            $permissionRole[] = [
                'role' => $value,
                'access' => $permissionCheck->access
            ];
        }

        if ($permissionRole[0]['access'] == 0 && $permissionRole[1]['access'] == 0) {
            Session::flash('flash_message_warning', 'You have no permission');
            return redirect(route('dashboard'))->send();
        }
    }
    /********************************************************************/
    public function index()
    {
        $roles = [
            '0' => 'inventory',
            '1' => 'products'
        ];
        $this->authenticateRole($roles);

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
            // $user_info = session('user_info');
            $user = checkMemberID(Auth::guard('web')->user()->id);
            // echo "<pre>"; print_r($user); exit;
            $token = $user['user']['v2_token'];
            $gs1MemberID = $user['user']['parentMemberUniqueID'];
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

                    if ($row['type'] == 'gs1') {
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
                    if ($row['type'] == 'gs1') {
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
    public function syncProducts(Request $request)
    {
        if ($request->ajax()) {
            // $user_info = session('user_info');
            // $url = 'https://gs1ksa.org:3093/uploads/products/memberProductsImages/front_image-1717839864972.jpg';
            $user = checkMemberID(Auth::guard('web')->user()->id);
            // echo "<pre>"; print_r($user); exit;
            $token = $user['user']['v2_token'];
            // $token = $user_info['token'];
            $gs1MemberID = $user['user']['parentMemberUniqueID'];
            $apiProducts = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
            ])->get('https://gs1ksa.org:3093/api/products', [
                        'user_id' => $gs1MemberID,
                    ]);


            $apiProductsBody = $apiProducts->getBody();
            $apiProductssData = json_decode($apiProductsBody, true);
            //  echo "<pre>"; print_r($apiProductssData); exit;
            $baseURL = "https://gs1ksa.org:3093/";
            $chunkSize = 100;
            // Process data in chunks
            foreach (array_chunk($apiProductssData, $chunkSize) as $chunk) {
                foreach ($chunk as $key => $value) {
                    // $baseURL = "https://gs1ksa.org:3093/";

                    $front_imageURL = $baseURL . $value['front_image'];
                    $back_imageURL = $baseURL . $value['back_image'];
                    $image_1URL = $baseURL . $value['image_1'];
                    $image_2URL = $baseURL . $value['image_2'];
                    $image_3URL = $baseURL . $value['image_3'];

                    // Decode images (assuming decodeImage is a defined function)
                    $front_imageD = decodeImage($front_imageURL);
                    $back_imageD = decodeImage($back_imageURL);
                    $image_1D = decodeImage($image_1URL);
                    $image_2D = decodeImage($image_2URL);
                    $image_3D = decodeImage($image_3URL);

                    //    echo "<pre>"; print_r($front_imageD); "</br>";
                    //    echo "<pre>"; print_r($back_imageD); "</br>";
                    //    echo "<pre>"; print_r($image_1D); "</br>";
                    //    echo "<pre>"; print_r($image_2D); "</br>";
                    //    echo "<pre>"; print_r($image_3D); "</br>";
                    //    exit;

                    $front_image = ($front_imageD !== 'Not Found') ? $front_imageD : '';
                    $back_image = ($back_imageD !== 'Not Found') ? $back_imageD : '';
                    $image_1 = ($image_1D !== 'Not Found') ? $image_1D : '';
                    $image_2 = ($image_2D !== 'Not Found') ? $image_2D : '';
                    $image_3 = ($image_3D !== 'Not Found') ? $image_3D : '';

                    // Find or create the product
                    $product = Product::where('barcode', $value['barcode'])->first();
                    if (!$product) {
                        $product = new Product;
                    }

                    // Update product details
                    $product->type = 'gs1';
                    $product->v2_productID = $value['id'];
                    $product->gcpGLNID = $value['gcpGLNID'];
                    $product->productnameenglish = $value['productnameenglish'];
                    $product->slug = \Str::slug($value['productnameenglish']);
                    $product->productnamearabic = $value['productnamearabic'];
                    $product->BrandName = $value['BrandName'];
                    $product->ProductType = $value['ProductType'];
                    $product->Origin = $value['Origin'];
                    $product->PackagingType = $value['PackagingType'];
                    $product->MnfCode = $value['MnfCode'];
                    $product->MnfGLN = $value['MnfGLN'];
                    $product->ProvGLN = $value['ProvGLN'];
                    $product->unit = $value['unit'];
                    $product->size = $value['size'];
                    // $product->quantity = $value['quantity'];
                    $product->barcode = $value['barcode'];
                    $product->gpc = $value['gpc'];
                    $product->gpc_code = $value['gpc_code'];
                    $product->countrySale = $value['countrySale'];
                    $product->HSCODES = $value['HSCODES'];
                    $product->HsDescription = $value['HsDescription'];
                    $product->gcp_type = $value['gcp_type'];
                    $product->prod_lang = $value['prod_lang'];
                    $product->details_page = $value['details_page'];
                    $product->details_page_ar = $value['details_page_ar'];
                    $product->status = $value['status'];
                    $product->product_url = $value['product_url'];
                    $product->product_type = $value['product_type'];
                    $product->BrandNameAr = $value['BrandNameAr'];
                    $product->front_image = $front_image;
                    $product->back_image = $back_image;
                    $product->image_1 = $image_1;
                    $product->image_2 = $image_2;
                    $product->image_3 = $image_3;
                    $product->user_id = $user['user']['id'];

                    $product->save();
                }

                // Optionally, clear memory after each chunk
                // unset($chunk);
            }
            return response()->json(['status' => 200, 'message' => 'Products are syncronized']);

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

            // $user_info = session('user_info');
            $user = checkMemberID(Auth::guard('web')->user()->id);
            $token = $user['user']['v2_token'];
            $gs1MemberID = $user['user']['parentMemberUniqueID'];
            $gcpGLNID = $user['user']['gcpGLNID'];

            if ($data['product_type'] == 'gs1') {

                try {
                    // $user_info = session('user_info');

                    $frontImagePath = $data['front_image'];
                    $backImagePath = $data['back_image'];
                    $image1Path = $data['image_1'];
                    $image2Path = $data['image_2'];
                    $image3Path = $data['image_3'];

                    $response = Http::withHeaders([
                        'Authorization' => 'Bearer ' . $token,
                    ])
                        ->attach('front_image', file_get_contents($frontImagePath), 'front_image.jpg')
                        ->attach('back_image', file_get_contents($backImagePath), 'back_image.jpg')
                        ->attach('image_1', file_get_contents($image1Path), 'image_1.jpg')
                        ->attach('image_2', file_get_contents($image2Path), 'image_2.jpg')
                        ->attach('image_3', file_get_contents($image3Path), 'image_3.jpg')
                        ->post('https://gs1ksa.org:3093/api/products', [
                            'user_id' => $gs1MemberID,
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


                    $responseBody = $response->getBody();
                    $responseSaleData = json_decode($responseBody, true);

                    $barcode = isset($responseSaleData['product']) ? $responseSaleData['product']['barcode'] : $data['product_code'];
                    if (@$responseSaleData['error']) {
                        return redirect()->back()->with('flash_message_warning', @$responseSaleData['error']);
                    }
                    $create = $this->productService->storeProduct($data, $id = null, $gcpGLNID, $barcode);
                    $create->save();

                    \LogActivity::addToLog(strtoupper($user['user']['company_name_eng']) . ' Added a gs1 product (' . $data['productnameenglish'] . ')', null);
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
                $barcode = $data['product_code'];
                $create = $this->productService->storeProduct($data, $id = null, $gcpGLNID, $barcode);

                $create->user_id = isset(Auth::user()->id) ? Auth::user()->id : 0;
                // echo "<pre>"; print_r($create); exit;
                \DB::beginTransaction();
                if ($create->save()) {
                    \LogActivity::addToLog(strtoupper($user['user']['company_name_eng']) . ' Added a gs1 product (' . $data['productnameenglish'] . ')', \Config::get('app.url') . '/product' . '/' . $create->slug);
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
        $user = checkMemberID(Auth::guard('web')->user()->id);
        $token = $user['user']['v2_token'];
        $gs1MemberID = $user['user']['parentMemberUniqueID'];
        $gcpGLNID = $user['user']['gcpGLNID'];

        $editProduct = Product::where('barcode', $request->barcode)->first();
        if ($request->product_type == 'gs1') {

            $productData = $this->productService->productData();

            $product_type = 'gs1';
        } else {
            $productData = $this->productService->localProductData();

            $product_type = 'non_gs1';
        }
        $pageTitle = "Edit Product";

        return view('user.product.edit', compact('pageTitle', 'editProduct', 'productData', 'user', 'product_type'));

    }
    /**********************************************************************/
    public function update(ProductRequest $request, $id = null)
    {

        try {
            $data = $request->all();
            $user = checkMemberID(Auth::guard('web')->user()->id);
            $token = $user['user']['v2_token'];
            $gs1MemberID = $user['user']['parentMemberUniqueID'];
            $gcpGLNID = $user['user']['gcpGLNID'];
            if ($request->product_type == 'gs1') {
                try {
                    // echo "<pre>"; print_r($data); exit;
                    $frontImagePath = $data['front_image'] ?? null;
                    $backImagePath = $data['back_image'] ?? null;
                    $image1Path = $data['image_1'] ?? null;
                    $image2Path = $data['image_2'] ?? null;
                    $image3Path = $data['image_3'] ?? null;

                    // Prepare the HTTP request
                    $request = Http::withHeaders([
                        'Authorization' => 'Bearer ' . $token,
                    ]);

                    // Conditionally attach images if they exist
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

                    // Make the PUT request
                    $response = $request->put('https://gs1ksa.org:3093/api/products/gtin/' . $data['product_id'], [
                        'user_id' => $gs1MemberID,
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
                    $responseBody = $response->getBody();
                    $responseSaleData = json_decode($responseBody, true);
                    $barcode = isset($responseSaleData['product']) ? $responseSaleData['product']['barcode'] : $data['product_code'];

                    $responseBody = $response->getBody();
                    $responseSaleData = json_decode($responseBody, true);

                    if (@$responseSaleData['error']) {
                        return redirect()->back()->with('flash_message_warning', @$responseSaleData['error']);
                    }
                    $update = $this->productService->storeProduct($data, $id, $gcpGLNID, $barcode);
                    $update->save();
                    $gtinData = checkGtinData($update->barcode);
                    if ($gtinData != true) {
                        $postProduct = $this->productService->sendProdutsToGepir($data);
                        // echo "<pre>"; print_r($postProduct); exit();
                        if ($postProduct['validationErrors']) {
                            Session::flash('flash_message_error', $postProduct['validationErrors']);
                            // return response()->json(['status' => 400, 'errors' => $postProduct['validationErrors']]);
                        } else {
                            Session::flash('flash_message_success', 'Product Posted to GEPIR');
                            // return response()->json(['status' => 200, 'errors' => _('notifications.notify.added')]);
                        }
                    }
                    \LogActivity::addToLog(strtoupper($user['user']['company_name_eng']) . ' Updated a gs1 product (' . $data['productnameenglish'] . ')', null);
                    return redirect(route('products'))->with('flash_message_success', 'Product successfully Updated!');
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
                $barcode = $data['product_code'];
                $update = $this->productService->storeProduct($data, $id, $gcpGLNID, $barcode);
                $update->user_id = (Auth::user()) ? Auth::user()->id : 0;
                \DB::beginTransaction();
                if ($update->save()) {

                    \LogActivity::addToLog(strtoupper($user['user']['company_name_eng']) . ' Updated a non gs1 product (' . $data['productnameenglish'] . ')', \Config::get('app.url') . '/product' . '/' . $update->slug);
                    \DB::commit();
                    return redirect(route('products'))->with('flash_message_success', 'Product successfully updated!');
                } else {
                    return redirect(route('products'))->with('flash_message_success', 'Data has not been updated!');
                }
            }

        } catch (\Throwable $th) {
            return redirect(route('products'))->with('flash_message_error', $th->getMessage());
        }

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
                $user = checkMemberID(Auth::guard('web')->user()->id);
                $token = $user['user']['v2_token'];
                $gs1MemberID = $user['user']['parentMemberUniqueID'];
                $gcpGLNID = $user['user']['gcpGLNID'];

                $gpc = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $token,
                ])->get('https://gs1ksa.org:4044/api/findSimilarRecords', [
                            'text' => trim($request->search),
                            'tableName' => 'gpc_bricks',
                        ]);

                $gpcBody = $gpc->getBody();
                $gpcData = json_decode($gpcBody, true);

                if ($gpcData) {
                    return response()->json(['status' => 200, 'data' => $gpcData]);
                }
            } catch (RequestException $e) {
                if ($e->hasResponse()) {
                    // Extract the error message from the response body
                    $responseBody = $e->getResponse()->getBody()->getContents();
                    $responseData = json_decode($responseBody, true);
                    $errorMessage = isset($responseData['error']) ? $responseData['error'] : 'An unexpected error occurred.';
                } else {
                    // If the response is not available, use a default error message
                    $errorMessage = 'An unexpected error occurred.';
                }

                // You can log the error message
                // \Log::error('Guzzle HTTP request failed: ' . $errorMessage);

                // Return an error response with the extracted error message
                return response()->json(['status' => 404, 'error' => $errorMessage], 404);
            } catch (\Throwable $th) {
                // \Log::error('An unexpected error occurred: ' . $th->getMessage());

                // Return an error response
                return response()->json(['error' => $th->getMessage()], 500);
            }

        }
    }
    public function getHscodesBasedOnGpcProductName(Request $request)
    {
        if ($request->ajax()) {
            try {

                Session::put('page', 'addProduct');
                $user = checkMemberID(Auth::guard('web')->user()->id);
                $token = $user['user']['v2_token'];
                $gs1MemberID = $user['user']['parentMemberUniqueID'];
                $gcpGLNID = $user['user']['gcpGLNID'];

                $hsCode = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $token,
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
                // \Log::error('An unexpected error occurred: ' . $th->getMessage());

                // Return an error response
                return response()->json(['error' => $th->getMessage()], 500);
            }
        }
    }
}
