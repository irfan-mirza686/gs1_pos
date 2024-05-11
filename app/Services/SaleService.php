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

        $create->transactions = $data['transactions'];
        $create->type = $data['type'];
        $create->salesLocation = isset($data['salesLocation'])?$data['salesLocation']:'';
        $create->vat_no = isset($data['vat_no'])?$data['vat_no']:'';
        $create->order_no = $data['invoice_no'];
        $create->customer_id = $data['customer_id'];
        $create->total = $data['totalAmount'];
        $create->date = date('Y-m-d');
        $create->time = $currentDate;
        $create->net_with_vat = $data['net_with_vat'];
        $create->cashAmount = $data['cashAmount'];
        $create->tender_amount = $data['tender_amount'];
        $create->change_amount = $data['change_amount'];

        $create->status = 'confirmed';
        $create->user_id = isset(Auth::user()->id)?Auth::user()->id:0;
        return $create;
    }
    /********************************************************************/
    public function makeItemsArr($data)
    {
        $purchaseItems = [];

        for ($i = 0; $i < count($data['description']); $i++) {
            $purchaseItems[] = array(
                'productName' => $data['description'][$i],
                // 'product_id' => $data['product_id'][$i],
                // 'type' => $data['type'][$i],
                'barcode' => $data['barcode'][$i],
                // 'barcode_2' => $data['barcode_2'][$i],
                'qty' => $data['quantity'][$i],
                'price' => $data['price'][$i],
                'discount' => $data['discount'][$i],
                'vat' => $data['vat'][$i],
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
