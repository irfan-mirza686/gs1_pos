<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerRequest;
use App\Models\Customer;
use App\Models\CustomerAddress;
use Auth;
use Illuminate\Http\Request;
use App\Services\CustomerService;
use DB;

class CustomerController extends Controller
{
    private $customerService;

    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
    }
    /********************************************************************/
    public function index()
    {
        try {
            $data = $this->customerService->getAllData();
            return response()->json(['customers' => $data], 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }

    }
    /********************************************************************/
    public function store(CustomerRequest $request)
    {
        $data = $request->all();

        try {
            $saveData = $this->customerService->saveCustomer($data, $id = "");
            $saveData->user_id = isset(Auth::user()->id) ? Auth::user()->id : 0;
            DB::beginTransaction();
            if ($saveData->save()) {
                foreach ($data['address'] as $key => $value) {
                    $address = new CustomerAddress();
                    $address->customer_id = $saveData->id;
                    $address->address = $value;
                    $address->save();
                }
                $customer = Customer::with('customer_address')->find($saveData->id);
                \LogActivity::addToLog(strtoupper(@$data['company_name_eng']) . ' Added a Customer (' . $data['name'] . ')', null);
                DB::commit();
                return response()->json(['message' => 'Data has been created successfully', 'customer' => $customer]);
            } else {
                return response()->json(['message' => 'Data has been not created'], 422);
            }
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }
    /********************************************************************/
    public function edit($id = null)
    {
        try {

            $data = Customer::with('customer_address')->find($id);
            if ($data) {
                return response()->json($data, 200);
            } else {
                return response()->json(['message' => 'No Data Found'], 404);
            }

        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }

    }
    /********************************************************************/
    public function update(CustomerRequest $request, $id = null)
    {
        $data = $request->all();

        try {
            $saveData = $this->customerService->saveCustomer($data, $id);

            DB::beginTransaction();
            if ($saveData->save()) {
                foreach ($data['address'] as $key => $value) {
                    $address = CustomerAddress::where('customer_id', $saveData->id)->where('id', $value['id'])->first();

                    $address->customer_id = $saveData->id;
                    $address->address = $value['address'];
                    $address->save();
                }
                $customer = Customer::with('customer_address')->find($saveData->id);
                \LogActivity::addToLog(strtoupper(@$data['company_name_eng']) . ' Updated a Customer (' . $data['name'] . ')', null);
                DB::commit();
                return response()->json(['message' => 'Data has been updated successfully', 'customer' => $customer], 200);
            } else {
                DB::rollBack();
                return response()->json(['message' => 'Data has been not updated'], 422);
            }
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }
    /********************************************************************/
    public function delete($id = null)
    {

        try {
            $customer = Customer::find($id);
            if ($customer) {
                CustomerAddress::where('customer_id', $customer->id)->delete();
                $customer->delete();
                return response()->json(['message' => 'Data has been deleted successfully'], 200);
            } else {
                return response()->json(['message' => 'Data has been not deleted'], 422);
            }
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Oops, Something went wrong'], 500);
        }
    }
    /********************************************************************/
    public function searchCustomer(Request $request)
    {
        try {
            $data = $this->customerService->autocompCustomer($request);
            return response()->json($data,200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Oops, Something went wrong'], 500);
        }
    }
}
