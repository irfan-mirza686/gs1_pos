<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Receiving;
use App\Models\StockTransfer;
use Illuminate\Http\Request;
use DB;

class StockTransferController extends Controller
{
    public function index(Request $request)
    {
        try {
            $data = StockTransfer::where('request_no', $request->request_no)->first();
            if ($data) {
                $items = $data->items;
// echo "<pre>"; print_r($items); exit;
                return response()->json(['items' => $items], 200);
            } else {
                return response()->json(['message' => 'No Data Found'], 200);
            }
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }
    /********************************************************/
    public function itemsReceiving(Request $request)
    {
        DB::beginTransaction();
        // try {
            $data = $request->all();
            $items = $this->makeItemsArr($data);

            foreach ($items as $key => $value) {

                $product = Product::find($value['product_id']);
                $checkItem = Receiving::where('gln', $data['gln'])
                ->where(function ($query) use ($value) {
                    $query->where('barcode', $value['gtin']);
                })
                ->first(['id', 'req_quantity','receive_quantity']);
                $rec_qty = intval(@$checkItem->receive_quantity) ?? 0;
                $req_qty = intval(@$checkItem->req_quantity) ?? 0;
                //  echo "<pre>"; print_r($rec_qty); exit;
                if (intval($value['receive_quantity']) + $rec_qty > intval($value['req_quantity'])) {
                    return response()->json(['message' => 'already received '. $rec_qty . ' Qty, cannot received more than ' . $req_qty . ' Qty'], 200);
                }
                if ($product) {
                    $product->quantity = $value['receive_quantity'] + $product->quantity;
                    $product->save();
                    // echo "<pre>"; print_r($product->toArray()); exit;
                    $this->saveManageStock($data, $product, $value,$checkItem);

                } else {
                    $newProduct = new Product;
                    $newProduct->productnameenglish = $value['productnameenglish'];
                    $newProduct->slug = \Str::slug($data['productnameenglish']);
                    $newProduct->barcode = $data['product_code'];

                    $newProduct->save();
                    $this->saveManageStock($data, $newProduct, $value,$checkItem);
                }

            }
            DB::commit();
            return response()->json(['message' => 'Items Received Successfully'], 200);

        // } catch (\Throwable $th) {
        //     DB::rollBack();
        //     return response()->json(['message' => $th->getMessage()], 500);
        // }
    }

    public function makeItemsArr($data)
    {
        $items = [];

        for ($i = 0; $i < count($data['product_id']); $i++) {
            $items[] = array(
                'product_id' => $data['product_id'][$i],
                'req_quantity' => $data['req_quantity'][$i],
                'receive_quantity' => $data['receive_quantity'][$i],
                'productnameenglish' => $data['productnameenglish'][$i],
                // 'sku' => $data['sku'][$i],
                'gtin' => $data['gtin'][$i],
            );
        }
        return $items;
    }
    public function saveManageStock($data, $product, $value,$checkItem)
    {
        // echo "<pre>"; print_r($value); exit;
        date_default_timezone_set((config('app.timezone')));
        $currentDate = date('Y-m-d h:i A');
        $time = date('h:i A', strtotime($currentDate));

        if ($checkItem) {
            $checkItem->receive_quantity = $value['receive_quantity'] + $checkItem->receive_quantity;
            $checkItem->save();
        } else {
            $receiving = new Receiving;
            $receiving->request_no = $data['request_no'];
            $receiving->product_id = $value['product_id'];
            $receiving->productnameenglish = $value['productnameenglish'];
            $receiving->gln = $data['gln'];
            $receiving->req_quantity = $value['req_quantity'];
            $receiving->receive_quantity = $value['receive_quantity'];
            $receiving->sku = \Str::slug($value['productnameenglish']);
            $receiving->barcode = $value['gtin'];
            $receiving->date = date('Y-m-d', strtotime($currentDate));
            $receiving->time = $time;
            $receiving->save();
        }

    }
}
