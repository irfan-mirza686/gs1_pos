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
        try {
            $data = $request->all();
            $items = $this->makeItemsArr($data);

            foreach ($items as $key => $value) {

                $product = Product::find($value['product_id']);

                //  echo "<pre>"; print_r($product->toArray()); exit;
                if ($product) {
                    $product->quantity = $value['quantity'] + $product->quantity;
                    $product->save();
                    // echo "<pre>"; print_r($product->toArray()); exit;
                    $this->saveManageStock($data, $product, $value);

                } else {
                    $newProduct = new Product;
                    $newProduct->productnameenglish = $value['productnameenglish'];
                    $newProduct->slug = \Str::slug($data['productnameenglish']);
                    $newProduct->barcode = $data['product_code'];

                    $newProduct->save();
                    $this->saveManageStock($data, $newProduct, $value);
                }

            }
            DB::commit();
            return response()->json(['message' => 'Items Received Successfully'], 200);

        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    public function makeItemsArr($data)
    {
        $items = [];

        for ($i = 0; $i < count($data['product_id']); $i++) {
            $items[] = array(
                'product_id' => $data['product_id'][$i],
                'quantity' => $data['quantity'][$i],
                'productnameenglish' => $data['productnameenglish'][$i],
                'sku' => $data['sku'][$i],
                'gtin' => $data['gtin'][$i],
            );
        }
        return $items;
    }
    public function saveManageStock($data, $product, $value)
    {
        // dd($value['quantity']);
        date_default_timezone_set((config('app.timezone')));
        $currentDate = date('Y-m-d h:i A');
        $time = date('h:i A', strtotime($currentDate));
        $checkItem = Receiving::where('gln', $data['gln'])
            ->where(function ($query) use ($value) {
                $query->where('sku', $value['sku'])
                    ->orWhere('barcode', $value['gtin']);
            })
            ->first(['id', 'quantity']);
        if ($checkItem) {
            $checkItem->quantity = $value['quantity'] + $checkItem->quantity;
            $checkItem->save();
        } else {
            $receiving = new Receiving;
            $receiving->product_id = $value['product_id'];
            $receiving->productnameenglish = $value['productnameenglish'];
            $receiving->gln = $data['gln'];
            $receiving->quantity = $value['quantity'];
            $receiving->sku = $value['sku'];
            $receiving->barcode = $value['gtin'];
            $receiving->date = date('Y-m-d', strtotime($currentDate));
            $receiving->time = $time;
            $receiving->save();
        }

    }
}
