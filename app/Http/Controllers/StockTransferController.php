<?php

namespace App\Http\Controllers;

use App\Http\Requests\StockTransferRequest;
use App\Models\Product;
use App\Models\Receiving;
use App\Models\StockTransfer;
use App\Services\StockTransferService;
use DataTables;
use Http;
use Illuminate\Http\Request;
use Session;

class StockTransferController extends Controller
{
    private $stockTransferService;

    public function __construct(StockTransferService $stockTransferService)
    {
        $this->stockTransferService = $stockTransferService;
    }
    /********************************************************************/
    public function authenticateRole($module_page = null)
    {
        $permissionCheck = checkRolePermission($module_page);
        if ($permissionCheck->access == 0) {
            Session::flash('flash_message_warning', 'You have no permission');
            return redirect(route('dashboard'))->send();
        }
    }
    /********************************************************************/
    public function index()
    {
        $this->authenticateRole("stock_management");
        $this->authenticateRole("stock_transfer");

        $pageTitle = "Manage Stock";
        $user_info = session('user_info');
        // echo "<pre>"; print_r($user_info); exit;
        $gln = Http::withHeaders([
            'Authorization' => 'Bearer ' . $user_info['token'],
        ])->get('https://gs1ksa.org:3093/api/gln', [
                    'user_id' => $user_info['memberData']['id'],
                ]);
        $glnBody = $gln->getBody();
        $glnData = json_decode($glnBody, true);
        // echo "<pre>"; print_r($glnData); exit;
        $glnBarcode = [];
        $glnName = [];
        foreach ($glnData as $key => $value) {
            $glnBarcode[] = $value['GLNBarcodeNumber'];
            $glnName[] = $value['locationNameEn'];
        }

        return view('user.stock.stock_transfer.index', compact('pageTitle', 'user_info', 'glnName'));
    }
    public function List(Request $request)
    {
        if ($request->ajax()) {

            $data = StockTransfer::get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('date_time', function ($row) {

                    return date('d-m-Y', strtotime($row['date'])) . ' ' . $row['time'];
                })
                ->editColumn('status', function ($row) {
                    // return $row['status'];

                    if ($row['status'] == 'approved') {
                        return '<span class="badge bg-gradient-quepal text-white shadow-sm w-100">' . strtoupper($row['status']) . '</span>';
                        // return '<span class="badge bg-info" style="width:100px;">' . strtoupper($row['status']) . '</span>';
                    } else {
                        return '<span class="badge bg-gradient-blooker text-white shadow-sm w-100">' . strtoupper($row['status']) . '</span>';
                        // return '<span class="badge bg-primary" style="width:100px;">' . strtoupper($row['status']) . '</span>';
                    }
                })


                ->addColumn('action', function ($row) {
                    $stockDetails = htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8');
                    $btn = '<div class="col text-end">
                    <div class="dropdown">
                        <button class="btn btn-primary dropdown-toggle btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">Action</button>
                        <ul class="dropdown-menu" style="">

                            <li><a class="dropdown-item" href="' . route('view.stock.request', $row->id) . '"><i class="lni lni-eye" style="color: yelow;"></i> View</a>
                            </li>
                            <li><a class="dropdown-item" href="' . route('stock.transfers.request.edit', $row->id) . '" data-StockDetails="' . $stockDetails . '"><i class="lni lni-pencil-alt" style="color: yelow;"></i> Edit</a>
                            </li>


                        </ul>
                    </div>
                </div>';

                    return $btn;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }
    }
    public function create()
    {
        $this->authenticateRole("stock_management");
        $this->authenticateRole("stock_transfer");

        $pageTitle = "Manage Stock";
        $user_info = session('user_info');
        $gln = Http::withHeaders([
            'Authorization' => 'Bearer ' . $user_info['token'],
        ])->get('https://gs1ksa.org:3093/api/gln', [
                    'user_id' => $user_info['memberData']['id'],
                ]);
        $glnBody = $gln->getBody();
        $glnData = json_decode($glnBody, true);
        $glnBarcode = [];
        $glns = [];
        foreach ($glnData as $key => $value) {
            $glns[] = array(
                'gln' => $value['GLNBarcodeNumber'],
                'glnName' => $value['locationNameEn']
            );
        }
        session(['stock_request' => 'create', 'stockRequestData' => array()]);
        return view('user.stock.stock_transfer.create', compact('pageTitle', 'user_info', 'glns'));
    }
    public function searchProducts(Request $request)
    {
        $query = $request->input('query');
        $products = Product::where('productnameenglish', 'LIKE', "%{$query}%")->get();
        $productsData = [];
        if ($products) {
            foreach ($products as $key => $value) {
                $productsData[] = array(
                    'id' => $value['id'],
                    'product_type' => $value['type'],
                    'productnameenglish' => $value['productnameenglish'],
                    'barcode' => $value['barcode'],
                    'quantity' => $value['quantity'],
                    'description' => $value['details_page'],
                    'price' => $value['selling_price'],
                    'front_image' => ($value['front_image']) ? getFile('products', $value['front_image']) : asset('assets/uploads/no-image.png'),
                    'back_image' => ($value['back_image']) ? getFile('products', $value['back_image']) : asset('assets/uploads/no-image.png'),
                    'image_1' => ($value['image_1']) ? getFile('products', $value['image_1']) : asset('assets/uploads/no-image.png'),
                    'image_2' => ($value['image_2']) ? getFile('products', $value['image_2']) : asset('assets/uploads/no-image.png'),
                    'image_3' => ($value['image_3']) ? getFile('products', $value['image_3']) : asset('assets/uploads/no-image.png'),
                );
            }

        }
        return response()->json($productsData);
    }
    public function viewStockRequest($id = null)
    {
        $this->authenticateRole("stock_management");
        $this->authenticateRole("stock_transfer");

        $pageTitle = "View Stock Request";
        $user_info = session('user_info');
        $stock_transfer = StockTransfer::find($id);
        $productInfo = [];

        foreach ($stock_transfer->items as $key => $value) {
            $receiving = Receiving::where('request_no',$stock_transfer->request_no)->where('product_id',$value['product_id'])->first();
            // echo "<pre>"; print_r($value); exit;
            $product = Product::find($value['product_id']);
            if ($product) {
                $productInfo[] = array(
                    'productnameenglish' => $product->productnameenglish,
                    'BrandName' => $product->BrandName,
                    'unit' => $product->unit,
                    'purchase_price' => $product->purchase_price,
                    'selling_price' => $product->selling_price,
                    'details_page' => $product->details_page,
                    'quantity' => $product->quantity,
                    'req_quantity' => $receiving['req_quantity'] ?? 0,
                    'receive_quantity' => $receiving['receive_quantity'] ?? 0,
                    'size' => $product->size,
                    'barcode' => $product->barcode,
                    'front_image' => ($product->front_image) ? getFile('products',$product->front_image) : getFile('','no-image.png'),
                    'back_image' => ($product->back_image) ? getFile('products',$product->back_image) : getFile('','no-image.png'),
                );
            }

        }
        // echo "<pre>"; print_r($productInfo); exit;
        return view('user.stock.stock_transfer.view_products', compact('pageTitle', 'user_info', 'stock_transfer', 'productInfo'));
    }

    public function saveStockTransferReq(Request $request)
    {
        if ($request->ajax()) {
            try {
                $data = $request->all();
                $selectProduct = json_decode($data['selectedProducts']);
                // $items = $this->stockTransferService->makeArr($data);
                $items = $this->stockTransferService->makeSelecteProductsArr($selectProduct);
                // echo "<pre>"; print_r($items); exit;

                $save = $this->stockTransferService->store($data, $id = null);
                $save->items = $items;
                if ($save->save()) {
                    $user_info = session('user_info');
                    \LogActivity::addToLog(strtoupper($user_info['memberData']['company_name_eng']) . ' Add a new Transfer Stock Request (' . $data['request_no'] . ')', null);
                    \DB::commit();
                    return response()->json(['status' => 200, 'message' => 'Data has been saved successfully']);
                } else {
                    return response()->json(['status' => 401, 'message' => 'Data has not been saved']);
                }
            } catch (\Throwable $th) {
                return response()->json(['status' => 500, 'message' => $th->getMessage()], 500);
            }

        }
    }

    public function show($id)
    {

        $product = Product::findOrFail($id);
        return response()->json(['product' => $product]);
    }
    public function productsData(Request $request)
    {
        if ($request->ajax()) {
            // echo "<pre>"; print_r($request->all()); exit;
            $user_info = session('user_info');
            $product = Http::withHeaders([
                'Authorization' => 'Bearer ' . $user_info['token'],
            ])->get('https://gs1ksa.org:3093/api/getAlldigital_linkBYfield', [
                        'digital_info_type' => $request->category,
                        // 'user_id' => $user_info['memberData']['companyID']
                        'user_id' => '10105'
                    ]);
            $productBody = $product->getBody();
            $productData = json_decode($productBody, true);
            // echo "<pre>"; print_r($productData); exit;

            return Datatables::of($productData)
                ->addIndexColumn()

                ->editColumn('target_url', function ($row) {
                    return $row['target_url'];
                })
                ->editColumn('digital_info_type', function ($row) {
                    return $row['digital_info_type'];
                })
                ->editColumn('gtin', function ($row) {
                    return $row['GTIN'];
                })

                ->addColumn('action', function ($row) {

                    $btn = '<ul class="navbar-nav ml-auto">

                          <li class="nav-item dropdown">
                            <a class="btn btn-primary btn-sm btn-o dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">
                              Action <span class="caret"></span>
                          </a>
                          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">

                            <a class="dropdown-item" target="_blank" href="javascript:void(0);">

                                <i class="fas fa-pen" style="color: blue;"></i>
                                View
                            </a>




                        </div>
                    </li>
                </ul>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);

            // $printData = [];
            // $counter = 0;
            // foreach ($productData as $key => $value) {
            //     $counter = $counter+1;
            //     $printData[] = array(
            //         'id' => $counter,
            //         'actions' => 'View',
            //         'target_url' => $value['target_url'],
            //         'digital_info_type' => $request->category,
            //         'gtin' => $value['GTIN']
            //     );
            // }


            // return response()->json(['data' => $printData]);
        }
    }
    /***************************************************************/
    public function edit($id = null)
    {
        $this->authenticateRole("stock_management");
        $this->authenticateRole("stock_transfer");

        $pageTitle = "Manage Stock";
        $user_info = session('user_info');
        $gln = Http::withHeaders([
            'Authorization' => 'Bearer ' . $user_info['token'],
        ])->get('https://gs1ksa.org:3093/api/gln', [
                    'user_id' => $user_info['memberData']['id'],
                ]);
        $glnBody = $gln->getBody();
        $glnData = json_decode($glnBody, true);
        $glnBarcode = [];
        $glns = [];
        foreach ($glnData as $key => $value) {
            $glns[] = array(
                'gln' => $value['GLNBarcodeNumber'],
                'glnName' => $value['locationNameEn']
            );
        }
        $stockRequestData = StockTransfer::find($id);
        $items = $stockRequestData->items;
        // echo "<pre>"; print_r($items); exit;
        $itemArr = [];
        foreach ($items as $key => $item) {
            $product = Product::find($item['product_id']);
            $itemArr[] = array(
                'id' => $product['id'],
                'type' => $product['type'],
                'name' => $product['productnameenglish'],
                'barcode' => $product['barcode'],
                'qty' => $item['qty'],
                'description' => $product['details_page'],
                'price' => $product['selling_price'],
                'front_image' => ($product['front_image']) ? getFile('products', $product['front_image']) : asset('assets/uploads/no-image.png'),
                'back_image' => ($product['back_image']) ? getFile('products', $product['back_image']) : asset('assets/uploads/no-image.png'),
                'image_1' => ($product['image_1']) ? getFile('products', $product['image_1']) : asset('assets/uploads/no-image.png'),
                'image_2' => ($product['image_2']) ? getFile('products', $product['image_2']) : asset('assets/uploads/no-image.png'),
                'image_3' => ($product['image_3']) ? getFile('products', $product['image_3']) : asset('assets/uploads/no-image.png'),
            );

        }
        session(['stock_request' => 'edit', 'stockRequestData' => $itemArr]);
        // echo "<pre>"; print_r($stockRequestData->toArray()); exit;
        return view('user.stock.stock_transfer.edit', compact('pageTitle', 'user_info', 'glns', 'stockRequestData'));
    }
    public function update(Request $request,$id=null)
    {
        if ($request->ajax()) {
            try {
                $data = $request->all();
                $selectProduct = json_decode($data['selectedProducts']);
                // $items = $this->stockTransferService->makeArr($data);
                $items = $this->stockTransferService->makeSelecteProductsArr($selectProduct);
                // echo "<pre>"; print_r($items); exit;

                $save = $this->stockTransferService->store($data, $id = null);
                $save->items = $items;
                if ($save->save()) {
                    $user_info = session('user_info');
                    \LogActivity::addToLog(strtoupper($user_info['memberData']['company_name_eng']) . ' Updated a new Transfer Stock Request (' . $data['request_no'] . ')', null);
                    \DB::commit();
                    return response()->json(['status' => 200, 'message' => 'Data has been updated successfully']);
                } else {
                    return response()->json(['status' => 401, 'message' => 'Data has not been updated']);
                }
            } catch (\Throwable $th) {
                return response()->json(['status' => 500, 'message' => $th->getMessage()], 500);
            }

        }
    }
}
