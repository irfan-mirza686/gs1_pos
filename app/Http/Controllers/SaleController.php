<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SaleService;
use App\Http\Requests\PosRequest;
use App\Models\Stock;
use Auth;
use DataTables;
use Prgayman\Zatca\Zatca;
use Session;
use App\Models\Sale;
use App\ZatcaWrapper\ZatcaWrapper;

class SaleController extends Controller
{
    private $saleService;

    public function __construct(SaleService $saleService)
    {
        $this->saleService = $saleService;
    }
    /********************************************************************/
    public function index()
    {
        $pageTitle = "Sales";
        return view('user.sales.index', compact('pageTitle'));
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
                    if ($row->status == 'approved') {
                        return '<span class="badge bg-success updateStatus" data-ProductID="' . $row->id . '" data-Status="inactive" style="cursor: pointer; width:100px;">' . strtoupper($row->status) . '</span>';
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
                            </li>
                            <li><a class="dropdown-item edit" href="' . route('sale.update', $row->order_no) . '" data-OrderNo="' . $row->order_no . '" ><i class="lni lni-pencil-alt" style="color: yelow;"></i> Edit</a>
                            </li>
                            <li><a class="dropdown-item del" href="' . route('sale.delete', $row->id) . '"><i class="lni lni-trash" style="color: red;"></i> Delete</a>
                            </li>

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
    public function pos()
    {
        $pageTitle = "POS";
        $user_info = session('user_info');
        // echo "<pre>"; print_r($user_info); exit;
        $printInvoiceNo = time();
        $page_name = "pos";
        $base64 = (new ZatcaWrapper())
            ->sellerName('Irfan')
            ->vatRegistrationNumber("300456416500003")
            ->timestamp("2021-12-01T14:00:09Z")
            ->totalWithVat('100.00')
            ->vatTotal('15.00')
            ->csrCommonName('ok ok ok')
            ->csrSerialNumber('123456789')
            ->csrOrganizationIdentifier('0686')
            ->csrOrganizationUnitName('IT')
            ->csrOrganizationName('OutSeller')
            ->csrCountryName('Pakistan')
            ->csrInvoiceType('Sell')
            ->csrLocationAddress('Pakistan')
            ->csrIndustryBusinessCategory('IT Industry')
            ->toBase64();

        // echo "<pre>"; print_r($base64); exit();
        return view('user.sales.pos.index', compact('pageTitle', 'printInvoiceNo', 'page_name','user_info'));
    }
    /********************************************************************/
    public function findProduct(Request $request)
    {
        if ($request->ajax()) {
            try {
                $product = Stock::where('barcode', 'LIKE', "%" . $request->barcode . "%")->orWhere('barcode_2', 'LIKE', "%" . $request->barcode . "%")->first();
                if (isset ($product) && !empty ($product) && $product->qty <= 0) {
                    return response()->json(['status' => 404, 'message' => 'Product was sold']);
                }
                // echo "<pre>"; print_r($product); exit;
                if (isset ($product) && !empty ($product) && $product->qty > 0) {
                    return response()->json(['status' => 200, 'product' => $product]);
                } else {
                    return response()->json(['status' => 404, 'message' => 'Product Not Found']);
                }
            } catch (\Throwable $th) {
                return response()->json(['status' => 422, 'message' => $th->getMessage()]);
            }

        }
    }
    /********************************************************************/
    public function store(Request $request)
    {
        if ($request->ajax()) {
            try {
                $data = $request->all();
                // echo "<pre>";
                // print_r($data);
                // exit();
                $items = $this->saleService->makeItemsArr($data);
                $pos = $this->saleService->saveSale($data, $id = "");
                \DB::beginTransaction();

                $pos->items = $items;
                if ($pos->save()) {

                    // $base64 = \Prgayman\Zatca\Facades\Zatca::sellerName('Zatca')
                    //     ->vatRegistrationNumber("300456416500003")
                    //     ->timestamp("2021-12-01T14:00:09Z")
                    //     ->totalWithVat('100.00')
                    //     ->vatTotal('15.00')
                    //     ->toBase64();
                    // echo "<pre>";
                    // print_r($base64);
                    // exit;
                    $this->saleService->updateStock($items);
                    \LogActivity::addToLog(strtoupper(Auth::user()->name) . ' Add a new Sale Order (' . $data['invoice_no'] . ')', route('sale.view', $pos->order_no));
                    \DB::commit();
                    return response()->json(['status' => 200, 'message' => 'Data has been saved successfully', 'invoice_no' => time(), 'print_invoiceNo' => $data['invoice_no']]);
                } else {
                    return response()->json(['status' => 401, 'message' => 'Data has not been saved']);
                }

            } catch (\Throwable $th) {
                return response()->json(['status' => 422, 'message' => $th->getMessage()]);
            }

        }
    }
    /********************************************************************/
    public function view(Request $request, $invoice_no = null)
    {
        $getInvoiceData = Sale::with('customer')->where('order_no', $invoice_no)->first();
        // echo "<pre>"; print_r($getInvoiceData->toArray()); exit();
        return view('user.sales.print_invoice', compact('getInvoiceData'));
    }
}
