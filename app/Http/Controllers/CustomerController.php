<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerAddress;
use Illuminate\Http\Request;
use App\Services\CustomerService;
use App\Http\Requests\CustomerRequest;
use Auth;
use DataTables;

class CustomerController extends Controller
{
    private $customerService;

    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
    }
    /********************************************************************/
    public function autocompleteCustomer(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->customerService->autocompCustomer($request);
            $dataRetrun = json_encode($data);
            return Response($dataRetrun);
        }
    }
    /********************************************************************/
    public function index()
    {
        $pageTitle = "Customers";
        return view('user.customers.index',compact('pageTitle'));
    }
    /********************************************************************/
    public function List(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->customerService->getAllData();
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
    public function store(CustomerRequest $request)
    {
        if ($request->ajax()) {
            // try {
                $user_info = session('user_info');
                $data = $request->all();
                // echo "<pre>"; print_r($data); exit;
                $create = $this->customerService->saveCustomer($data, $id = null);
                // echo "<pre>"; print_r($create); exit;
                $create->user_id = isset(Auth::user()->id)?Auth::user()->id:0;
                \DB::beginTransaction();
                if ($create->save()) {
                    foreach($data['address'] as $key => $value){
                        $address = new CustomerAddress;
                        $address->customer_id = $create->id;
                        $address->address = $value;
                        $address->save();
                    }
                    $customer = Customer::with('customer_address')->find($create->id);
                    \LogActivity::addToLog(strtoupper($user_info['memberData']['company_name_eng']) . ' Add a Customer (' . $data['name'] . ')', \Config::get('app.url') . '/customer_view' . '/' . $create->id);
                    \DB::commit();
                    return response()->json(['status' => 200, 'message' => 'Data has been saved successfully', 'customer' => $customer]);
                } else {
                    return response()->json(['status' => 422, 'message' => 'Data has not been saved']);
                }
            // } catch (\Throwable $th) {
            //     return response()->json(['status' => 422, 'message' => $th->getMessage()]);
            // }
        }
    }
    /********************************************************************/
}
