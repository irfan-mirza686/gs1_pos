<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\Stock;
use Illuminate\Http\Request;
use App\Services\PurchaseService;
use App\Http\Requests\PurchaseRequest;
use Auth;
use DataTables;
use Session;
use App\Models\SupplierPayment;

class PurchaseController extends Controller
{
    private $purchaseService;

    public function __construct(PurchaseService $purchaseService)
    {
        $this->purchaseService = $purchaseService;
    }
    /********************************************************************/
    public function index()
    {
        $pageTitle = "Puchase";
        return view('user.purchase.index', compact('pageTitle'));
    }
    /********************************************************************/
    public function List(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->purchaseService->getAllPurchase();
            // echo "<pre>"; print_r($data->toArray()); exit;
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('order_date', function ($row) {
                    $order_date = date('d-m-Y', strtotime($row->date));
                    return $order_date;
                })
                ->editColumn('order_no', function ($row) {
                    return '<span class="badge bg-dark">' . $row->order_no . '</span>';
                })
                ->editColumn('purchase_status', function ($row) {
                    return strtoupper($row->status);
                })

                ->editColumn('supplier', function ($row) {
                    return ($row->supplier) ? strtoupper($row->supplier->name) : '';
                })

                ->editColumn('due_amount', function ($row) {
                    $due_amount = $row->total - $row->paid_amount;
                    return $due_amount;
                })
                ->editColumn('payment_status', function ($row) {
                    $due_amount = $row->total - $row->paid_amount;
                    if ($row->paid_amount == 0) {
                        return '<span class="badge bg-danger" style="cursor: pointer; width:100px;">Unpaid</span>';
                    } else if ($due_amount > 0) {
                        return '<span class="badge bg-warning" style="cursor: pointer; width:100px;">Partial</span>';
                    } else if ($due_amount == 0) {
                        return '<span class="badge bg-success" style="cursor: pointer; width:100px;">Paid</span>';
                    }
                })
                ->editColumn('created_by', function ($row) {
                    return ($row->user) ? strtoupper($row->user->name) : '';
                })

                ->addColumn('action', function ($row) {

                    $btn = '<div class="col text-end">
                    <div class="dropdown">
                        <button class="btn btn-primary dropdown-toggle btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">Action</button>
                        <ul class="dropdown-menu" style="">
                        <li><a class="dropdown-item" href="' . route('purchase.view', $row->order_no) . '"><i class="lni lni-eye" style="color: blue;"></i> Items</a>
                            </li>
                            <li><a class="dropdown-item purchaseViewPayments" href="javascript:void(0);" data-ViewURL="' . route('purchase.view.payments', $row->id) . '" data-delPurchasePaymentURL="' . route('delete.purchase.payment', $row->order_no) . '" data-OrderNo="' . $row->order_no . '"><i class="lni lni-eye" style="color: blue;"></i> View Payments</a>
                            </li>
                            <li><a class="dropdown-item purchasePayNow" href="javascript:void(0);" data-PurchasePendingURL="' . route('purchase.pending.payment', $row->id) . '" data-url="' . route('purchase.paynow', $row->id) . '"><i class="lni lni-eye" style="color: blue;"></i> Pay Now</a>
                            </li>
                            <li><a class="dropdown-item edit" href="' . route('purchase.edit', $row->id) . '" data-OrderNo="' . $row->order_no . '" ><i class="lni lni-pencil-alt" style="color: yelow;"></i> Edit</a>
                            </li>
                            <li><a class="dropdown-item del" href="' . route('purchase.delete', $row->id) . '"><i class="lni lni-trash" style="color: red;"></i> Delete</a>
                            </li>

                        </ul>
                    </div>
                </div>';

                    return $btn;
                })
                ->rawColumns(['order_no', 'payment_status', 'action'])
                ->make(true);
        }
    }
    /********************************************************************/
    public function create()
    {
        $pageTitle = "Create Purchase";
        $order_no = time();
        date_default_timezone_set((config('app.timezone')));
        $currentDate = date('Y-m-d h:i:s');
        return view('user.purchase.create', compact('pageTitle', 'order_no', 'currentDate'));
    }
    /********************************************************************/
    public function store(PurchaseRequest $request)
    {
        try {
            $data = $request->all();

            $create = $this->purchaseService->savePurchaseOrder($data, $id = null);
            $create->user_id = Auth::user()->id;
            \DB::beginTransaction();
            if ($create->save()) {
                if ($data['paid_amount'] > 0) {
                    $this->purchaseService->update_purchase_payment_supplier_payment($create);
                }
                \LogActivity::addToLog(strtoupper(Auth::user()->name) . ' Add a new Purchase Order (' . $data['order_no'] . ')', \Config::get('app.url') . '/purchase_view' . '/' . $create->order_no);
                \DB::commit();
                Session::flash('flash_message_success', 'Data has been saved successfully');
                return redirect(route('purchase'));
            } else {
                Session::flash('flash_message_error', 'Data has not been saved');
                return redirect()->back();
            }
        } catch (\Throwable $th) {
            Session::flash('flash_message_error', $th->getMessage());
            return redirect()->back();
        }
    } /********************************************************************/
    public function edit($id = null)
    {
        $pageTitle = "Edit Purchase";

        date_default_timezone_set((config('app.timezone')));
        $currentDate = date('Y-m-d h:i:s');
        $data = Purchase::with('supplier')->find($id);
        $balance = $data->total - ($data->paid_amount + $data->discount);
        // echo "<pre>"; print_r($data->items); exit;
        return view('user.purchase.edit', compact('pageTitle', 'currentDate', 'data', 'balance'));
    }
    /********************************************************************/
    public function update(PurchaseRequest $request, $id = null)
    {
        try {
            $data = $request->all();

            $create = $this->purchaseService->savePurchaseOrder($data, $id);
            $create->user_id = Auth::user()->id;
            \DB::beginTransaction();
            if ($create->save()) {
                \LogActivity::addToLog(strtoupper(Auth::user()->name) . ' Update a Purchase Order (' . $data['order_no'] . ')', \Config::get('app.url') . '/purchase_view' . '/' . $create->order_no);
                \DB::commit();
                Session::flash('flash_message_success', 'Data has been updated successfully');
                return redirect(route('purchase'));
            } else {
                Session::flash('flash_message_error', 'Data has not been updated');
                return redirect()->back();
            }
        } catch (\Throwable $th) {
            Session::flash('flash_message_error', $th->getMessage());
            return redirect()->back();
        }
    }
    /********************************************************************/
    public function viewPayments(Request $request, $id = null)
    {
        if ($request->ajax()) {
            try {
                $data = $this->purchaseService->getPendingPurchaseAmount($request, $id);
                return response()->json(['status' => 200, 'data' => $data]);
            } catch (\Throwable $th) {
                return response()->json(['status' => 422, 'message' => $th->getMessage()]);
            }

        }
    }
    /********************************************************************/
    public function delPurchasePayment(Request $request, $order_no = null)
    {
        if ($request->ajax()) {
            try {
                if ($request->PaymentType == 'supplier_payment') {
                    SupplierPayment::where('order_no', $order_no)->delete();
                    return response()->json(['status' => 200, 'message' => 'Supplier Payment Deleted']);
                } else if ($request->PaymentType == 'purchase_payment') {
                    Purchase::where('order_no', $order_no)->delete();
                    return response()->json(['status' => 200, 'message' => 'Purchase Payment Deleted']);
                }
            } catch (\Throwable $th) {
                return response()->json(['status' => 422, 'message' => $th->getMessage()]);
            }
        }
    }
    /********************************************************************/
    public function checkBarcode(Request $request)
    {
        if ($request->ajax()) {
           $data =  Stock::where('barcode',$request->barcode)->first();
           if ($data) {
                return response()->json(['status'=> 422,'message'=>'Barcode is already exist']);
           }else{
            return response()->json(['status'=> 200]);
           }
            // echo "<pre>"; print_r($data);exit;
        }
    }
}
