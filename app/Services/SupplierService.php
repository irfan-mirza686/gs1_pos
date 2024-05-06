<?php
namespace App\Services;

use App\Models\{
    Supplier,
};

class SupplierService
{
    public function getAllSuppliers()
    {
        return Supplier::with('user')->get();
    }
    /********************************************************************/

    public function autocompSupplier($request)
    {
        $supplierAuto = [];
        $suppliers = Supplier::where('mobile', 'LIKE', "%" . $request->term . "%")->orWhere('name', 'LIKE', "%" . $request->term . "%")->orWhere('cnic', 'LIKE', "%" . $request->term . "%")->paginate(5);
        if ($suppliers) {
            foreach ($suppliers as $key => $value) {
                $supplierAuto[] = array(
                    "value" => $value->name,
                    "supplierID" => $value->id,
                    "supplierName" => $value->name,
                );
            }
            return $supplierAuto;
        }
    }
    /********************************************************************/
    public function saveSupplier($data, $id = null)
    {
        if ($id == null) {
            $create = new Supplier;
        } else if ($id != null) {
            $create = Supplier::find($id);
        }
        $create->name = $data['name'];
        $create->mobile = $data['mobile'];
        $create->cnic = $data['cnic'];
        $create->address = isset($data['address']) ? $data['address'] : null;
        return $create;
    }
}
