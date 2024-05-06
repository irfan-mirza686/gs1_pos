<?php
namespace App\Services;

use App\Models\{
    Sale,
    Stock,
};
use Auth;

class SaleService
{
    public function getAllSales()
    {
        return Sale::with('customer')->get();
    }

    public function saveSale($data, $id = "")
    {
        if ($id == null) {
            $create = new Sale;
        } else if ($id != null) {
            $create = Sale::find($id);
        }
        date_default_timezone_set((config('app.timezone')));
        $currentDate = date('Y-m-d h:i:s a');

        $create->order_no = $data['invoice_no'];
        $create->customer_id = $data['customer_id'];
        $create->total = $data['totalAmount'];
        $create->date = date('Y-m-d');
        $create->time = $currentDate;
        $create->paid_amount = $data['cashAmount'];

        $create->status = 'confirmed';
        $create->user_id = Auth::user()->id;
        return $create;
    }
    /********************************************************************/
    public function makeItemsArr($data)
    {
        $purchaseItems = [];

        for ($i = 0; $i < count($data['productName']); $i++) {
            $purchaseItems[] = array(
                'productName' => $data['productName'][$i],
                'product_id' => $data['product_id'][$i],
                'type' => $data['type'][$i],
                'barcode' => $data['barcode'][$i],
                'barcode_2' => $data['barcode_2'][$i],
                'qty' => $data['quantity'][$i],
                'price' => $data['price'][$i],
                'sub_total' => $data['single_total'][$i],
            );
        }
        return $purchaseItems;
    }
    /********************************************************************/
    public function updateStock($items)
    {

        foreach ($items as $key => $value) {
            $stock = new Stock;
            $checkBarcode = Stock::where('barcode', $value['barcode'])->first();
            // echo "<pre>"; print_r($checkBarcode); exit;
            $oldQty = $checkBarcode->qty - $value['qty'];
            if ($checkBarcode) {
                $checkBarcode->update(['qty' => $oldQty]);
            }

        }
    }
}
