<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClaimRequest;
use App\Models\Claim;
use DataTables;
use Illuminate\Http\Request;
use App\Services\ClaimService;

class ClaimController extends Controller
{
    private $claimService;

    public function __construct(ClaimService $claimService)
    {
        $this->claimService = $claimService;
    }
    /********************************************************************/

    public function index(Request $request)
    {
        $pageTitle = 'Claims';
        return view('user.claims.index', compact('pageTitle'));
    }
    /********************************************************************/
    public function List(Request $request)
    {
        if ($request->ajax()) {
            $data = Claim::with('user')->get();
            // echo "<pre>"; print_r($data->toArray()); exit;
            return DataTables::of($data)
                ->addIndexColumn()
                // ->editColumn('status', function ($row) {
                //     return strtoupper($row->status);
                // })
                ->editColumn('status', function ($row) {
                    if ($row->status == 'complete') {
                        return '<span class="badge bg-success" style="width:100px;">' . strtoupper($row->status) . '</span>';
                    } else if ($row->status == 'pending') {
                        return '<span class="badge bg-danger" style="width:100px;">' . strtoupper($row->status) . '</span>';
                    }
                })
                ->editColumn('type',function($row){
                    return strtoupper($row->type);
                })
                ->editColumn('added_by',function($row){
                    return ($row->user)?$row->user->name:'';
                })
                ->addColumn('action', function ($row) {

                    $btn = '<a href="javascript:void(0);" data-URL="' . route('claim.update',$row->id) . '" data-toggle="tooltip"  data-product_name="' . $row->product_name . '"  data-barcode="' . $row->barcode . '"  data-type="' . $row->type . '"  data-note="' . $row->note . '" data-status="' . $row->status . '"  data-id="' . $row->id . '" data-original-title="Edit Unit" class="btn btn-primary btn-sm edit"><i class="fadeIn animated bx bx-edit"></i></a>';
                    $btn = $btn . ' <a href="' . route('claim.delete',$row->id) . '"data-toggle="tooltip"  data-name="' . $row->name . '" data-status="' . $row->status . '"  data-id="' . $row->id . '" data-original-title="Delete Unit" class="btn btn-danger btn-sm del"><i class="fadeIn animated bx bx-trash"></i></a>';
                    return $btn;
                })
                ->rawColumns(['status','action'])
                ->make(true);
        }
    }

    /********************************************************************/
    public function store(ClaimRequest $request)
    {

        $data = $request->all();
        try {
            $country = $this->claimService->store($data, $id = "");
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
    public function update(ClaimRequest $request, $id = null)
    {
        $data = $request->all();
        try {
            $country = $this->claimService->store($data, $id);
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
            if (Claim::find($id)->delete()) {
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
