<?php
namespace App\Services;

use App\Models\{
    StockTransfer,
};

class StockTransferService
{
    public function getAllData()
    {
        return StockTransfer::get();
    }

    /********************************************************************/
    public function store($data, $id = null)
    {
        date_default_timezone_set((config('app.timezone')));
                $currentDate = date('Y-m-d h:i:s');

        if ($id == null) {
            $create = new StockTransfer;
        } else if ($id != null) {
            $create = StockTransfer::find($id);
        }

        $create->request_no = $data['request_no'];
        $create->gln_from = $data['gln_from'];
        $create->gln_to = $data['gln_to'];
        $create->date = date('Y-m-d',strtotime($currentDate));
        $create->time = date('h:i A', strtotime($currentDate));
        $create->note = isset($data['note'])?$data['note']:'';
        return $create;
    }
    public function makeArr($data)
    {
        $itemsArr = [];

        for ($i = 0; $i < count($data['productName']); $i++) {
            $itemsArr[] = array(
                'productName' => $data['productName'][$i],
                'product_id' => $data['product_id'][$i],
                'product_type' => $data['product_type'][$i],
                'barcode' => $data['barcode'][$i],
                'qty' => $data['quantity'][$i]
            );
        }
        return $itemsArr;
    }
    public function makeSelecteProductsArr($selectProduct)
    {
        $itemsArr = [];
        foreach ($selectProduct as $key => $value) {
            $itemsArr[] = array(
                'productName' => $value->productName,
                'product_id' => $value->product_id,
                'product_type' => $value->product_type,
                'barcode' => $value->barcode,
                'qty' => $value->qty
            );
        }
        return $itemsArr;
    }
}
