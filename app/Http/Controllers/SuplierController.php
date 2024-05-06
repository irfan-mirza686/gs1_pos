<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SupplierService;
use App\Http\Requests\SupplierRequest;
use Auth;
use DataTables;

class SuplierController extends Controller
{
    private $supplierService;

    public function __construct(SupplierService $supplierService)
    {
        $this->supplierService = $supplierService;
    }
    /********************************************************************/
    public function autocompleteSupplier(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->supplierService->autocompSupplier($request);
            $dataRetrun = json_encode($data);
            return Response($dataRetrun);
        }
    }
    /********************************************************************/
    public function index()
    {
        $pageTitle = "Suppliers";
        return view('user.suppliers.index',compact('pageTitle'));
    }
    /********************************************************************/
    public function List(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->supplierService->getAllSuppliers();
            return Datatables::of($data)
                ->addIndexColumn()

                ->editColumn('created_by', function ($row) {
                    return ($row->user)?$row->user->name:'';
                })
                ->editColumn('status', function ($row) {
                    if ($row->status == 'active') {
                        return '<span class="badge bg-success" style="width:100px;">' . strtoupper($row->status) . '</span>';
                    } else if ($row->status == 'inactive') {
                        return '<span class="badge bg-danger" style="width:100px;">' . strtoupper($row->status) . '</span>';
                    }
                })
                ->addColumn('action', function ($row) {

                    $btn = '<div class="col text-end">
                    <div class="dropdown">
                        <button class="btn btn-primary dropdown-toggle btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">Action</button>
                        <ul class="dropdown-menu" style="">
                        <li><a class="dropdown-item updateProductItemSellingPrice" data-customerID="' . $row->id . '" style="cursor: pointer;"><i class="lni lni-eye" style="color: blue;"></i> Pay Now</a>
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
    /********************************************************************/
    public function store(SupplierRequest $request)
    {
        if ($request->ajax()) {
            try {
                $data = $request->all();
                $create = $this->supplierService->saveSupplier($data, $id = null);
                $create->user_id = Auth::user()->id;
                \DB::beginTransaction();
                if ($create->save()) {
                    \LogActivity::addToLog(strtoupper(Auth::user()->name) . ' Add a Supplier (' . $data['name'] . ')', \Config::get('app.url') . '/supplier_view' . '/' . $create->id);
                    \DB::commit();
                    return response()->json(['status' => 200, 'message' => 'Data has been saved successfully','supplier'=>$create]);
                } else {
                    return response()->json(['status' => 422, 'message' => 'Data has not been saved']);
                }
            } catch (\Throwable $th) {
                return response()->json(['status' => 422, 'message' => $th->getMessage()]);
            }
        }
    }
    /********************************************************************/
}
