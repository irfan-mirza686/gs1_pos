<?php
namespace App\Services;

use App\Models\{
    Sale,
    Stock,
};
use App\Models\Product;
use Auth;
use Illuminate\Support\Facades\Http;

class SaleService
{
    public function findProductData($token, $barcode)
    {
        $product = Product::where('barcode', 'LIKE', "%" . $barcode . "%")->orWhere('productnameenglish', 'LIKE', "%" . $barcode . "%")->first();

        // $searchAPiProduct = Http::withHeaders([
        //     'Authorization' => 'Bearer ' . $token,
        // ])->get('https://gs1ksa.org:3093/api/products', [
        //             'barcode' => $barcode,
        //         ]);
        // $searchAPiProductBody = $searchAPiProduct->getBody();
        // $findApiProduct = json_decode($searchAPiProductBody, true);


        // if (isset($findApiProduct) && !empty($findApiProduct)) {
        //     $findApiProduct = $findApiProduct[0];
        // }
        //  if ($findApiProduct) {
        //     $prodArray = [
        //         'product_id' => $findApiProduct['id'],
        //         'product_type' => 'gs1',
        //         'productName' => $findApiProduct['productnameenglish'],
        //         'brand' => $findApiProduct['BrandName'],
        //         'desc1' => $findApiProduct['details_page'],
        //         'size' => $findApiProduct['size'],
        //         'price' => 1,
        //         'disc' => 0,
        //         'vat' => 15,
        //         'total_with_vat' => 0,
        //     ];
        //     return ['status' => 200, 'prodArray' => $prodArray];
        // }


        // echo "<pre>"; print_r($product); exit;
        if (isset($product) && !empty($product)) {
            $prodArray = [
                'product_id' => $product->id,
                'product_type' => $product->type,
                'productName' => $product->productnameenglish,
                'gpc' => $product->gpc,
                'brand' => $product->BrandName,
                'desc1' => $product->details_page,
                'size' => $product->size,
                'price' => $product->selling_price,
                'disc' => 0,
                'vat' => 15,
                'vat_amount' => (($product->selling_price * 15) / 100),
                'total_with_vat' => (($product->selling_price * 15) / 100) + $product->selling_price,
                'quantity' => intval($product->quantity) ?? 0
            ];
            return ['status' => 200, 'prodArray' => $prodArray];
        } else {
            return [
                'status' => 404,
                'message' => 'No Data Found!'
            ];
        }
    }
    /*************************************************************/
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
        $create->salesLocation = isset($data['salesLocation']) ? $data['salesLocation'] : '';
        $create->vat_no = isset($data['vat_no']) ? $data['vat_no'] : '';
        $create->order_no = $data['order_no'];
        $create->delivery = $data['delivery'] ?? '';
        $create->remkars = $data['remkars'];
        $create->customer_id = $data['customer_id'];
        $create->total = $data['totalAmount'];
        $create->date = date('Y-m-d');
        $create->time = date('h:i A', strtotime($currentDate));
        $create->net_with_vat = $data['net_with_vat'];
        $create->cashAmount = $data['totalAmount'];
        $create->tender_amount = $data['tender_amount'];
        $create->change_amount = $data['change_amount'];

        $create->status = 'confirmed';
        $create->user_id = isset(Auth::user()->id) ? Auth::user()->id : 0;
        return $create;
    }
    /********************************************************************/
    public function makeItemsArr($data)
    {
        $purchaseItems = [];

        for ($i = 0; $i < count($data['description']); $i++) {
            $purchaseItems[] = array(
                'productName' => $data['description'][$i],
                'gpc' => $data['gpc'][$i],
                'product_id' => $data['product_id'][$i],
                'product_type' => $data['product_type'][$i],
                'barcode' => $data['barcode'][$i],
                'qty' => $data['quantity'][$i],
                'price' => $data['price'][$i],
                'discount' => $data['discount'][$i],
                'vat' => $data['vat'][$i],
                'vat_total' => $data['vat_total'][$i],
                'sub_total' => $data['single_total'][$i],
            );
        }
        return $purchaseItems;
    }

    /********************************************************************/
    public function itmesLog($user_info,$items,$data)
    {
        foreach ($items as $key => $value) {
            $itemsLogs = Http::post('http://gs1ksa.org:7000/api/insertGtrackEPCISLog', [
                        'gs1UserId' => $user_info['memberData']['id'],
                        'TransactionType' => 'receiving',
                        'GTIN' => $value['barcode'],
                        'GLNFrom' => $data['salesLocation'],
                        'GLNTo' => $data['salesLocation'],
                        'IndustryType' => 'retail',
                    ]);
            $itemsLogsBody = $itemsLogs->getBody();
            $itemsLogsApi = json_decode($itemsLogsBody, true);
            // echo "<pre>"; print_r($itemsLogsApi); exit;
        }
    }
    /********************************************************************/
    public function updateStock($items)
    {

        foreach ($items as $key => $value) {

            $checkBarcode = Product::where('barcode', $value['barcode'])->first();

            $newQty = $checkBarcode->quantity - $value['qty'];

            if ($checkBarcode) {
                // echo "<pre>"; print_r('should update'); exit;
                Product::where('barcode',$value['barcode'])->update(['quantity' => $newQty]);
            }
            // echo "<pre>"; print_r("not update"); exit;

        }
    }
}
