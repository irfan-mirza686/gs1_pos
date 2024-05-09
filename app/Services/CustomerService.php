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
        $customers = Customer::where('mobile', 'LIKE', "%" . $request->term . "%")->orWhere('name', 'LIKE', "%" . $request->term . "%")->orWhere('cnic', 'LIKE', "%" . $request->term . "%")->paginate(5);
        if ($customers) {
            foreach ($customers as $key => $value) {
                $customerAuto[] = array(
                    "value" => $value->mobile,
                    "customerID" => $value->id,
                    "customerName" => $value->name,
                    "customerMobile" => $value->mobile,
                    "vat_no" => $value->vat
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
        $create->name = $data['name'];
        $create->mobile = $data['mobile'];
        $create->cnic = $data['cnic'];
        $create->vat = $data['vat'];
        $create->address = isset($data['address']) ? $data['address'] : null;
        return $create;
    }
}
