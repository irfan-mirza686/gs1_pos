<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Services\BrandService;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Services\CustomerService;
use App\Http\Requests\CustomerRequest;
use Auth;
use Session;
use DataTables;

class BrandController extends Controller
{
    private $brandService;

    public function __construct(BrandService $brandService)
    {
        $this->brandService = $brandService;
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
            '1' => 'brands'
        ];
        $this->authenticateRole($roles);
        $pageTitle = 'Brands';
        $user_info = session('user_info');
        return view('user.brands.index', compact('pageTitle','user_info'));
    }
    /********************************************************************/
    public function List(Request $request)
    {
        if ($request->ajax()) {
            $data = Brand::get();
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

                    $btn = '<a href="' . route('brand.update',$row->id) . '" data-toggle="tooltip"  data-name="' . $row->name . '" data-status="' . $row->status . '"  data-id="' . $row->id . '" data-original-title="Edit Brand" class="btn btn-primary btn-sm edit"><i class="fadeIn animated bx bx-edit"></i></a>';
                    $btn = $btn . ' <a href="' . route('brand.delete',$row->id) . '"data-toggle="tooltip"  data-name="' . $row->name . '" data-status="' . $row->status . '"  data-id="' . $row->id . '" data-original-title="Delete Brand" class="btn btn-danger btn-sm del"><i class="fadeIn animated bx bx-trash"></i></a>';
                    return $btn;
                })
                ->rawColumns(['status','action'])
                ->make(true);
        }
    }

    /********************************************************************/
    public function store(Request $request)
    {
        $roles = [
            '0' => 'inventory',
            '1' => 'brands'
        ];
        $this->authenticateRole($roles);
        $data = $request->all();
        try {
            $country = $this->brandService->store($data, $id = "");
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
    public function update(Request $request, $id = null)
    {
        $roles = [
            '0' => 'inventory',
            '1' => 'brands'
        ];
        $this->authenticateRole($roles);

        $data = $request->all();
        try {
            $country = $this->brandService->store($data, $id);
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
                '1' => 'brands'
            ];
            $this->authenticateRole($roles);

            if (Brand::find($id)->delete()) {
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
