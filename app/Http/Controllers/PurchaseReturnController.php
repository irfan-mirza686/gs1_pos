<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PurchaseReturnService;
use App\Models\PurchaseReturn;
use App\Http\Requests\PurchaseReturnRequest;
use Auth;
use DataTables;
use Session;

class PurchaseReturnController extends Controller
{
    private $purchaseReturnService;

    public function __construct(PurchaseReturnService $purchaseReturnService)
    {
        $this->purchaseReturnService = $purchaseReturnService;
    }
    /********************************************************************/
    public function index()
    {
        $pageTitle = "Puchase Returns";
        return view('user.purchase.returns.index', compact('pageTitle'));
    }
    /********************************************************************/
    public function List(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->purchaseReturnService->getAllPurchaseReturns();
            return Datatables::of($data)
                ->addIndexColumn()

                ->editColumn('order_no', function ($row) {
                    return '<span class="badge bg-dark">' . $row->order_no . '</span>';
                })

                ->editColumn('supplier', function ($row) {
                    return ($row->supplier) ? strtoupper($row->supplier->name) : '';
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
                        <li><a class="dropdown-item" href="' . route('purchase.view', $row->order_no) . '"><i class="lni lni-eye" style="color: blue;"></i> Items</a>
                            </li>
                            <li><a class="dropdown-item edit" href="' . route('purchase.return.edit', $row->id) . '" data-OrderNo="' . $row->order_no . '" ><i class="lni lni-pencil-alt" style="color: yelow;"></i> Edit</a>
                            </li>
                            <li><a class="dropdown-item del" href="' . route('purchase.return.delete', $row->id) . '"><i class="lni lni-trash" style="color: red;"></i> Delete</a>
                            </li>

                        </ul>
                    </div>
                </div>';

                    return $btn;
                })
                ->rawColumns(['order_no', 'supplier', 'status', 'action'])
                ->make(true);
        }
    }
    /********************************************************************/
    public function create()
    {
        $pageTitle = "Create Purchase Return";
        date_default_timezone_set((config('app.timezone')));
        $currentDate = date('Y-m-d h:i:s');
        return view('user.purchase.returns.create', compact('pageTitle', 'currentDate'));
    }
    /********************************************************************/
    public function store(PurchaseReturnRequest $request)
    {
        try {
            $data = $request->all();

            $create = $this->purchaseReturnService->savePurchaseReturn($data, $id = null);
            $create->user_id = Auth::user()->id;
            \DB::beginTransaction();
            if ($create->save()) {
                \LogActivity::addToLog(strtoupper(Auth::user()->name) . ' Add a new Purchase Order (' . $data['order_no'] . ')', \Config::get('app.url') . '/purchase_view' . '/' . $create->order_no);
                \DB::commit();
                Session::flash('flash_message_success', 'Data has been saved successfully');
                return redirect(route('purchase.returns'));
            } else {
                Session::flash('flash_message_error', 'Data has not been saved');
                return redirect()->back();
            }
        } catch (\Throwable $th) {
            Session::flash('flash_message_error', $th->getMessage());
            return redirect()->back();
        }
    }
    /********************************************************************/
    public function edit($id = null)
    {
        $pageTitle = "Edit Purchase Return";

        date_default_timezone_set((config('app.timezone')));
        $currentDate = date('Y-m-d h:i:s');
        $data = PurchaseReturn::with('supplier')->find($id);
        $balance = $data->total - ($data->paid_amount + $data->discount);
        // echo "<pre>"; print_r($data->items); exit;
        return view('user.purchase.returns.edit', compact('pageTitle', 'currentDate', 'data', 'balance'));
    }
    /********************************************************************/
    public function update(PurchaseReturnRequest $request, $id = null)
    {
        try {
            $data = $request->all();

            $create = $this->purchaseReturnService->savePurchaseReturn($data, $id);
            $create->user_id = Auth::user()->id;
            \DB::beginTransaction();
            if ($create->save()) {
                \LogActivity::addToLog(strtoupper(Auth::user()->name) . ' Update a Purchase Order (' . $data['order_no'] . ')', \Config::get('app.url') . '/purchase_view' . '/' . $create->order_no);
                \DB::commit();
                Session::flash('flash_message_success', 'Data has been updated successfully');
                return redirect(route('purchase.returns'));
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
    public function searchPurchaseByOrderNo(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->purchaseReturnService->autocompReturnOrder($request);
            $dataRetrun = json_encode($data);
            return Response($dataRetrun);
        }
    }

}
