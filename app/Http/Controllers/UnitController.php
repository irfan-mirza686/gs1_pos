<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UnitService;
use App\Models\Country;
use App\Http\Requests\UnitRequest;
use Auth;
use DataTables;
use App\Models\Unit;
use Session;

class UnitController extends Controller
{
    private $unitService;

    public function __construct(UnitService $unitService)
    {
        $this->unitService = $unitService;
    }
    /********************************************************************/
    public function authenticateRole($roles = null)
    {


        $permissionRole = [];
        foreach ($roles as $key => $value) {

            $permissionCheck = checkRolePermission($value);

            $permissionRole[] = [
                'role' => $value,
                'access' => $permissionCheck->access
            ];
        }

        if ($permissionRole[0]['access'] == 0 && $permissionRole[1]['access'] == 0) {
            Session::flash('flash_message_warning', 'You have no permission');
            return redirect(route('dashboard'))->send();
        }
    }
    /********************************************************************/

    public function index(Request $request)
    {
        $roles = [
            '0' => 'inventory',
            '1' => 'units'
        ];
        $this->authenticateRole($roles);

        $pageTitle = 'Units';
        return view('user.unit.index', compact('pageTitle'));
    }
    /********************************************************************/
    public function List(Request $request)
    {
        if ($request->ajax()) {
            $data = Unit::get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('status', function ($row) {
                    return strtoupper($row->status);
                })
                ->editColumn('status', function ($row) {
                    if ($row->status == 'active') {
                        return '<span class="badge bg-success" style="width:100px;">' . strtoupper($row->status) . '</span>';
                    } else if ($row->status == 'inactive') {
                        return '<span class="badge bg-danger" style="width:100px;">' . strtoupper($row->status) . '</span>';
                    }
                })
                ->addColumn('action', function ($row) {

                    $btn = '<a href="' . route('unit.update', $row->id) . '" data-toggle="tooltip"  data-name="' . $row->name . '" data-status="' . $row->status . '"  data-id="' . $row->id . '" data-original-title="Edit Unit" class="btn btn-primary btn-sm edit"><i class="fadeIn animated bx bx-edit"></i></a>';
                    $btn = $btn . ' <a href="' . route('unit.delete', $row->id) . '"data-toggle="tooltip"  data-name="' . $row->name . '" data-status="' . $row->status . '"  data-id="' . $row->id . '" data-original-title="Delete Unit" class="btn btn-danger btn-sm del"><i class="fadeIn animated bx bx-trash"></i></a>';
                    return $btn;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }
    }

    /********************************************************************/
    public function store(UnitRequest $request)
    {
        $roles = [
            '0' => 'inventory',
            '1' => 'units'
        ];
        $this->authenticateRole($roles);
        $data = $request->all();
        try {
            $country = $this->unitService->store($data, $id = "");
            if ($country->save()) {
                return response()->json(['status' => 200, 'message' => 'Data has been created successfully']);
            } else {
                return response()->json(['status' => 422, 'message' => 'Data has been not created']);
            }
        } catch (\Throwable $th) {
            return response()->json(['status' => 422, 'message' => 'Oops, Something went wrong']);
        }


    }

    /********************************************************************/
    public function update(UnitRequest $request, $id = null)
    {
        $roles = [
            '0' => 'inventory',
            '1' => 'units'
        ];
        $this->authenticateRole($roles);
        $data = $request->all();
        try {
            $country = $this->unitService->store($data, $id);
            if ($country->save()) {
                return response()->json(['status' => 200, 'message' => 'Data has been updated successfully']);
            } else {
                return response()->json(['status' => 422, 'message' => 'Data has been not updated']);
            }
        } catch (\Throwable $th) {
            return response()->json(['status' => 422, 'message' => 'Oops, Something went wrong']);
        }
    }
    /********************************************************************/
    public function delete($id = null)
    {

        try {
            $roles = [
                '0' => 'inventory',
                '1' => 'units'
            ];
            $this->authenticateRole($roles);
            if (Unit::find($id)->delete()) {
                return response()->json(['status' => 200, 'message' => 'Data has been deleted successfully']);
            } else {
                return response()->json(['status' => 422, 'message' => 'Data has been not deleted']);
            }
        } catch (\Throwable $th) {
            return response()->json(['status' => 422, 'message' => 'Oops, Something went wrong']);
        }
    }
    /********************************************************************/
}
