<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UnitRequest;
use App\Models\Unit;
use Illuminate\Http\Request;
use App\Services\UnitService;
use Session;

class UnitController extends Controller
{
    private $unitService;

    public function __construct(UnitService $unitService)
    {
        $this->unitService = $unitService;
    }
    /********************************************************************/
    public function index()
    {
        try {
            $data = Unit::get();
            return response()->json(['brands' => $data], 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }

    }
    /********************************************************************/
    public function store(UnitRequest $request)
    {
        $data = $request->all();

        try {
            $saveData = $this->unitService->store($data, $id = "");
            if ($saveData->save()) {
                \LogActivity::addToLog(strtoupper($data['company_name_eng']) . ' Added a new Unit (' . $data['name'] . ')', null);
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
            $data = Unit::find($id);
            return response()->json($data, 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }

    }
    /********************************************************************/
    public function update(UnitRequest $request, $id = null)
    {
        $data = $request->all();
        try {
            $saveData = $this->unitService->store($data, $id);
            if ($saveData->save()) {
                \LogActivity::addToLog(strtoupper(@$data['company_name_eng']) . ' Update a Unit (' . $data['name'] . ')', null);
                return response()->json(['message' => 'Data has been updated successfully'], 200);
            } else {
                return response()->json(['message' => 'Data has been not updated'], 422);
            }
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }
    /********************************************************************/
    public function delete(Request $request, $id = null)
    {

        try {
            $data = $request->all();
            $unit = Unit::find($id);
            Session::put('unit_name', $unit->name);
            if ($unit->delete()) {
                \LogActivity::addToLog(strtoupper($data['company_name_eng']) . ' Delete a Unit (' . session('unit_name') . ')', null);
                return response()->json(['message' => 'Data has been deleted successfully'], 200);
            } else {
                return response()->json(['message' => 'Data has been not deleted'], 422);
            }
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }
    /********************************************************************/
}
