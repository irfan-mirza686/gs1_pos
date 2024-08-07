<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Http\Requests\SaleRequest;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Services\SaleService;
use App\Http\Requests\PosRequest;
use App\Models\Stock;
use Auth;
use DataTables;
use Prgayman\Zatca\Zatca;
use Session;
use App\Models\Sale;
use App\ZatcaWrapper\ZatcaWrapper;
use Stevebauman\Location\Facades\Location;

class SaleController extends Controller
{
    private $saleService;

    public function __construct(SaleService $saleService)
    {
        $this->saleService = $saleService;
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
            '0' => 'sales_management',
            '1' => 'sales'
        ];
        $this->authenticateRole($roles);
        // $this->authenticateRole("sales");

        $pageTitle = "Sales";
        $user_info = session('user_info');
        return view('user.sales.index', compact('pageTitle', 'user_info'));
    }
    /********************************************************************/
    public function List(Request $request)
    {
        if ($request->ajax()) {

            $data = $this->saleService->getAllSales();
            return Datatables::of($data)
                ->addIndexColumn()

                ->editColumn('order_no', function ($row) {
                    return '<span class="badge bg-dark">' . $row->order_no . '</span>';
                })

                ->editColumn('customer', function ($row) {
                    return ($row->customer) ? strtoupper($row->customer->name) : '';
                })
                ->editColumn('status', function ($row) {
                    if ($row->status == 'confirmed') {
                        return '<span class="badge bg-success" data-ProductID="' . $row->id . '" data-Status="inactive" style="width:100px;">' . strtoupper($row->status) . '</span>';
                    } else if ($row->status == 'pending') {
                        return '<span class="badge bg-danger updateStatus" data-ProductID="' . $row->id . '" data-Status="active" style="cursor: pointer; width:100px;">' . strtoupper($row->status) . '</span>';
                    }
                })

                ->addColumn('action', function ($row) {

                    $btn = '<div class="col text-end">
                    <div class="dropdown">
                        <button class="btn btn-primary dropdown-toggle btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">Action</button>
                        <ul class="dropdown-menu" style="">
                        <li><a class="dropdown-item openSalePrint" href="javascript:void(0);" data-OrderNo="' . $row->order_no . '" data-URL="' . route('sale.view', $row->order_no) . '"><i class="lni lni-eye" style="color: blue;"></i> View</a>


                        </ul>
                    </div>
                </div>';

                    return $btn;
                })
                ->rawColumns(['order_no', 'customer', 'status', 'action'])
                ->make(true);
        }
    }
    /********************************************************************/
    public function pos(Request $request)
    {
        $roles = [
            '0' => 'sales_management',
            '1' => 'sales'
        ];
        $this->authenticateRole($roles);

        $pageTitle = "POS";
        $user = checkMemberID(Auth::guard('web')->user()->id);
        $token = $user['user']['v2_token'];
        $gs1MemberID = $user['user']['parentMemberUniqueID'];
        // echo "<pre>"; print_r($user_info['memberData']['companyID']); exit;
        $printInvoiceNo = time();
        $page_name = "pos";

        $gln = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->get('https://gs1ksa.org:3093/api/gln', [
                    'user_id' => $gs1MemberID,
                ]);
        $glnBody = $gln->getBody();
        $glnData = json_decode($glnBody, true);
        // echo "<pre>"; print_r($glnData); exit;
        // $glnBarcode = [];
        // $glnName = [];
        $glns = [];
        foreach ($glnData as $key => $value) {
            $glns[] = array(
                'gln' => $value['GLNBarcodeNumber'],
                'glnName' => $value['locationNameEn']
            );
            // $glnBarcode[] = $value['GLNBarcodeNumber'];
            // $glnName[] = $value['locationNameEn'];
        }
        $clientIP = '103.239.147.187';
        // $clientIP = $request->ip();
        $userLocation = Location::get($clientIP);
        $customer = Customer::find(1);
        // echo "<pre>"; print_r($userLocation); exit;

        return view('user.sales.pos.index', compact('pageTitle', 'printInvoiceNo', 'page_name', 'userLocation', 'glns', 'customer', 'user'));
    }
    /********************************************************************/
    public function findProduct(Request $request)
    {
        if ($request->ajax()) {
            try {
                $user = checkMemberID(Auth::guard('web')->user()->id);
                $token = $user['user']['v2_token'];
            $barcode = $request->barcode;
            $product = $this->saleService->findProductData($token, $barcode);
            // echo "<pre>"; print_r($product['prodArray']['quantity']); exit;
            if ($product['prodArray']['quantity'] <= 0) {
                return response()->json(['status' => 422, 'message' => 'Out of stock! Remaining quantity is ' . $product['prodArray']['quantity']]);
            }
            if ($product['status'] === 404) {
                return response()->json(['status' => 404, 'message' => $product['message']]);
            } else {
                return response()->json(['status' => 200, 'prodArray' => $product['prodArray']]);
            }
            } catch (\Throwable $th) {
                return response()->json(['status' => 500, 'message' => $th->getMessage()], 500);
            }

        }
    }
    /*=======================================================================*/
    public function checkProductStock(Request $request)
    {
        $data = $request->all();
        // echo "<pre>"; print_r($data); exit;
        $product = Product::where('id', $data['productID'])->first()->toArray();
        if ($product['quantity'] < $data['quantity']) {
            return response()->json(
                [
                    'error' => true,
                    'message' => $product['productnameenglish'] . ' Product is ' . $product['quantity'] . ' remaining!'
                ]
            );
        }
    }
    /********************************************************************/
    public function store(SaleRequest $request)
    {
        if ($request->ajax()) {
            \DB::beginTransaction();
            try {
                $data = $request->all();

                $user_info = session('user_info');
                $items = $this->saleService->makeItemsArr($data);
                // echo "<pre>";
                // print_r($data);
                // exit();
                $pos = $this->saleService->saveSale($data, $id = "");


                $pos->items = $items;
                if ($pos->save()) {
                    $this->saleService->updateStock($items);
                    $this->saleService->itmesLog($user_info, $items, $data);
                    $customer = Customer::find(1);
                    // $base64 = \Prgayman\Zatca\Facades\Zatca::sellerName('Zatca')
                    //     ->vatRegistrationNumber("300456416500003")
                    //     ->timestamp("2021-12-01T14:00:09Z")
                    //     ->totalWithVat('100.00')
                    //     ->vatTotal('15.00')
                    //     ->toBase64();
                    // echo "<pre>";
                    // print_r($base64);
                    // exit;

                    \LogActivity::addToLog(strtoupper($user_info['memberData']['company_name_eng']) . ' Add a new Sale Order (' . $data['order_no'] . ')', route('sale.view', $pos->order_no));
                    \DB::commit();
                    return response()->json(['status' => 200, 'message' => 'Data has been saved successfully', 'invoice_no' => time(), 'print_invoiceNo' => $data['order_no'], 'customer' => $customer]);
                } else {
                    return response()->json(['status' => 401, 'message' => 'Data has not been saved']);
                }

            } catch (\Throwable $th) {
                \DB::rollBack();
                return response()->json(['status' => 500, 'message' => $th->getMessage()], 500);
            }

        }
    }
    /********************************************************************/
    public function view(Request $request, $invoice_no = null)
    {
        $getInvoiceData = Sale::with('customer')->where('order_no', $invoice_no)->first();
        // $totalWithVat = $getInvoiceData
        $totalVat = 0;
        // echo "<pre>"; print_r($getInvoiceData->items); exit;
        // Loop through each object in the array
        foreach ($getInvoiceData->items as $product) {
            // Add vat_total of each product to the totalVat
            $totalVat += $product['vat_total'];
        }
        $base64 = (new ZatcaWrapper())
            ->sellerName('Saudi Leather Industries Factory Company Ltd')
            ->vatRegistrationNumber("300456416500003")
            ->timestamp("2021-12-01T14:00:09Z")
            ->totalWithVat($getInvoiceData->total)
            ->vatTotal($totalVat)
            ->csrCommonName('Saudi Leather Industries Factory Company Ltd')
            ->csrSerialNumber('2050011041')
            ->csrOrganizationIdentifier('3844')
            ->csrOrganizationUnitName('1')
            ->csrOrganizationName('OutSeller')
            ->csrCountryName('KSA')
            ->csrInvoiceType('zatca')
            ->csrLocationAddress('Dammam')
            ->csrIndustryBusinessCategory('Manufacturing')
            ->toBase64();
        // echo "<pre>"; print_r($base64); exit();
        // echo "<pre>"; print_r($getInvoiceData->toArray()); exit();
        $apiInvoice = "flase";
        return view('user.sales.print_invoice', compact('getInvoiceData', 'base64', 'totalVat', 'apiInvoice'));
    }
}
