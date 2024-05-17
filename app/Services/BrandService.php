<?php
namespace App\Services;

use App\Models\{
    Brand,
};

class BrandService
{
    public function getAllData()
    {
        return Brand::get();
    }

    /********************************************************************/
    public function store($data, $id = null)
    {
        if ($id == null) {
            $create = new Brand;
        } else if ($id != null) {
            $create = Brand::find($id);
        }

        $create->name = $data['name'];
        $create->status = $data['status'];
        return $create;
    }
}
