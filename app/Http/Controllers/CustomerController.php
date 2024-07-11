<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerImportRequest;
use App\Imports\CustomersImport;
use App\Models\Customer;
use App\Models\CustomerAddress;
use Illuminate\Http\Request;
use App\Services\CustomerService;
use App\Http\Requests\CustomerRequest;
use Auth;
use DataTables;
use Stevebauman\Location\Facades\Location;
use Excel;
use DB;
use Session;

class CustomerController extends Controller
{
    private $customerService;

    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
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
        $roles = [
            '0' => 'sales_management',
            '1' => 'customers'
        ];
        $this->authenticateRole($roles);

        $pageTitle = "Customers";
        $user_info = session('user_info');
        $clientIP = '103.239.147.187';
        // $clientIP = $request->ip();
        $userLocation = Location::get($clientIP);
        // echo "<pre>"; print_r($userLocation); exit;
        return view('user.customers.index', compact('pageTitle', 'user_info', 'userLocation'));
    }
    /********************************************************************/
    public function List(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->customerService->getAllData();
            // echo "<pre>"; print_r($data->toArray()); exit;
            return Datatables::of($data)
                ->addIndexColumn()
                // ->editColumn('address', function ($row) {
                //     if ($row->customer_address) {
                //         $badge = [];
                //         foreach ($row->customer_address as $value) {

                //                 $badge[] = '<span class="badge bg-info style="cursor: pointer; width:100px;">' . $value['address'] . '</span>';

                //         }
                //         return implode(' | ', $badge);
                //     }
                // })
                ->editColumn('address', function ($row) {
                    $encodedAddress = htmlspecialchars(json_encode($row->customer_address), ENT_QUOTES, 'UTF-8');
                    return '<span class="badge bg-warning viewCustomerAddresses" data-customerID="' . $row->id . '" data-customerName="' . $row->name . '" data-address="' . $encodedAddress . '"  style="width:100px; cursor: pointer;">View</span>';
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
                        <li><a class="dropdown-item edit" data-customerID="' . $row->id . '" style="cursor: pointer;"><i class="lni lni-eye" style="color: blue;"></i> Edit</a>
                            </li>
                        </ul>
                    </div>
                </div>';

                    return $btn;
                })
                ->rawColumns(['status', 'address', 'action'])
                ->make(true);
        }
    }
    /********************************************************************/
    public function store(CustomerRequest $request)
    {
        if ($request->ajax()) {
            try {

                $user_info = session('user_info');
                $data = $request->all();
                // echo "<pre>"; print_r($data); exit;
                $create = $this->customerService->saveCustomer($data, $id = null);
                // echo "<pre>"; print_r($create); exit;
                $create->user_id = isset(Auth::user()->id) ? Auth::user()->id : 0;
                DB::beginTransaction();
                if ($create->save()) {
                    foreach ($data['address'] as $key => $value) {
                        $address = new CustomerAddress;
                        $address->customer_id = $create->id;
                        $address->address = $value;
                        $address->save();
                    }
                    $customer = Customer::with('customer_address')->find($create->id);
                    \LogActivity::addToLog(strtoupper($user_info['memberData']['company_name_eng']) . ' Add a Customer (' . $data['name'] . ')', \Config::get('app.url') . '/customer_view' . '/' . $create->id);
                    DB::commit();
                    return response()->json(['status' => 200, 'message' => 'Data has been saved successfully', 'customer' => $customer]);
                } else {
                    return response()->json(['status' => 422, 'message' => 'Data has not been saved']);
                }
            } catch (\Throwable $th) {
                return response()->json(['status' => 422, 'message' => $th->getMessage()]);
            }
        }
    }
    /********************************************************************/
    public function importCustomers(CustomerImportRequest $request)
    {
        if ($request->ajax()) {
            $data = $request->all();
            try {
                // DB::beginTransaction();
                $checkColums = $this->customerService->checkExcelColumns($request);
                // echo "<pre>"; print_r($checkColums); exit;
                if (isset($checkColums) && $checkColums['status'] == 422) {
                    return response()->json(['status' => 422, 'message' => $checkColums['message']]);
                }
                if (isset($checkColums) && $checkColums['status'] == 'incorrect_columns') {
                    return response()->json(['status' => 'incorrect_columns', 'errors' => $checkColums['errors']]);
                }
                Excel::import(new CustomersImport, request()->file('file'));
                // DB::commit();
                return response()->json(['status' => 200, 'message' => 'Data Imported Successfully']);
            } catch (\Throwable $th) {
                // DB::rollBack();
                return response()->json(['error' => $th->getMessage()], 500);
            }


        }
    }
    /********************************************************************/
}
