<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BrandRequest;
use App\Models\Brand;
use Illuminate\Http\Request;
use App\Services\BrandService;

class BrandController extends Controller
{
    private $brandService;

    public function __construct(BrandService $brandService)
    {
        $this->brandService = $brandService;
    }
    /********************************************************************/
    public function index()
    {
        try {
            $data = Brand::get();
            return response()->json(['brands' => $data], 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }

    }
    /********************************************************************/
    public function store(BrandRequest $request)
    {
        $data = $request->all();
        // echo "<pre>"; print_r($data); exit;
        try {
            $country = $this->brandService->store($data, $id = "");
            if ($country->save()) {
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
            $data = Brand::find($id);
            return response()->json($data, 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }

    }
    /********************************************************************/
    public function update(BrandRequest $request, $id = null)
    {
        $data = $request->all();

        try {
            $country = $this->brandService->store($data, $id);
            if ($country->save()) {
                \LogActivity::addToLog(strtoupper(@$data['company_name_eng']) . ' Update a Brand (' . $data['name'] . ')', null);
                return response()->json(['message' => 'Data has been updated successfully'], 200);
            } else {
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
            if (Brand::find($id)->delete()) {
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
