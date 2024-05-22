<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Auth;
use Illuminate\Http\Request;
use App\Services\ProductService;
use Session;
use DB;

class ProductController extends Controller
{
    private $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }
    /********************************************************************/
    public function index(Request $request)
    {
        try {

            $token = $request->header('Authorization');

            $gs1MemberID = $request->header('gs1MemberID');
            $products = $this->productService->getAllProducts($token, $gs1MemberID);
            if (isset($products['error']) && !empty($products['error'])) {
                return ['error' => $products['error']];
            }

            return response()->json(['products' => $products], 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }

    }
    /********************************************************************/
    public function store(ProductRequest $request)
    {
        $data = $request->all();
        try {
            $saveData = $this->productService->storeProduct($data, $id = null);
            $saveData->user_id = isset(Auth::user()->id) ? Auth::user()->id : 0;
            DB::beginTransaction();
            if ($saveData->save()) {
                \LogActivity::addToLog(strtoupper(@$data['company_name_eng']) . ' Added a Product (' . $data['productnameenglish'] . ')', null);
                DB::commit();
                return response()->json(['message' => 'Data has been created successfully']);
            } else {
                return response()->json(['message' => 'Data has been not created'], 422);
            }
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }
    /********************************************************************/
    public function edit($id = null)
    {
        try {

            $data = Product::find($id);
            if ($data) {
                $product = [
                    'id' => $data->id,
                    'productnameenglish' => $data->productnameenglish,
                    'BrandName' => $data->BrandName,
                    'unit' => $data->unit,
                    'purchase_price' => $data->purchase_price,
                    'selling_price' => $data->selling_price,
                    'details_page' => $data->details_page,
                    'barcode' => $data->barcode,
                    'size' => $data->size,
                    'user_id' => $data->user_id,
                    'front_image' => getFile('products', $data->front_image),
                    'back_image' => getFile('products', $data->back_image)
                ];
                return response()->json($product, 200);
            } else {
                return response()->json(['message' => 'No Data Found'], 404);
            }

        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }

    }
    /********************************************************************/
    public function update(ProductRequest $request, $id = null)
    {
        $data = $request->all();

        try {
            $saveData = $this->productService->storeProduct($data, $id);
            $saveData->user_id = isset(Auth::user()->id) ? Auth::user()->id : 0;
            DB::beginTransaction();
            if ($saveData->save()) {

                \LogActivity::addToLog(strtoupper(@$data['company_name_eng']) . ' Updated a Product (' . $data['productnameenglish'] . ')', null);
                DB::commit();
                return response()->json(['message' => 'Data has been updated successfully'], 200);
            } else {
                DB::rollBack();
                return response()->json(['message' => 'Data has been not updated'], 422);
            }
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }
    /********************************************************************/
    public function delete($id = null)
    {

        try {
            $product = Product::find($id);
            if ($product) {
                $product->delete();
                return response()->json(['message' => 'Data has been deleted successfully'], 200);
            } else {
                return response()->json(['message' => 'Data has been not deleted'], 422);
            }
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Oops, Something went wrong'], 500);
        }
    }
    /********************************************************************/
}
