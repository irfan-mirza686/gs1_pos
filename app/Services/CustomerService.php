<?php
namespace App\Services;

use App\Models\{
    Customer,
};

class CustomerService
{
    public function getAllData()
    {
        return Customer::with('user')->get();
    }
    /********************************************************************/

    public function autocompCustomer($request)
    {
        $customerAuto = [];
        $customers = Customer::with('customer_address')->where('mobile', 'LIKE', "%" . $request->term . "%")->orWhere('name', 'LIKE', "%" . $request->term . "%")->paginate(5);
        // echo "<pre>"; print_r($customers->toArray()); exit;
        if ($customers) {
            foreach ($customers as $key => $value) {
                $customerAuto[] = array(
                    "value" => $value->mobile,
                    "customerID" => $value->id,
                    "customerName" => $value->name,
                    "customerMobile" => $value->mobile,
                    "vat_no" => $value->vat,
                    "address" => $value->customer_address
                );
            }
            return $customerAuto;
        }
    }
    /********************************************************************/
    public function saveCustomer($data, $id = null)
    {
        if ($id == null) {
            $create = new Customer;
        } else if ($id != null) {
            $create = Customer::find($id);
        }
        // $addressData = [];
        // foreach($data['address'] as $key => $value){
        //     $addressData[] = array(
        //         'id' => $key+1,
        //         'address' => $value
        //     );
        // }
        // $address = json_encode($addressData);
        // echo "<pre>"; print_r($address); exit;
        $create->name = $data['name'];
        $create->mobile = $data['mobile'];
        $create->vat = $data['vat'];
        // $create->address = isset($data['address']) ? $address : null;
        return $create;
    }
}
