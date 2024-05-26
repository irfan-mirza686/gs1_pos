<?php

namespace App\Http\Controllers;

use App\Models\StockTransfer;
use DataTables;
use Illuminate\Http\Request;

class StockTransferController extends Controller
{
    /********************************************************************/
    public function index()
    {
        $pageTitle = "Manage Stock";
        $user_info = session('user_info');

        return view('user.stock.stock_transfer.index', compact('pageTitle', 'user_info'));
    }
    public function List(Request $request)
    {
        if ($request->ajax()) {

            $data = StockTransfer::get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('date_time', function ($row) {

                    return date('d-m-Y',strtotime($row['date'])) . ' '. $row['time'];
                })
                ->editColumn('status', function ($row) {
                    // return $row['status'];

                    if ($row['status'] == 'approved') {
                        return '<span class="badge bg-gradient-quepal text-white shadow-sm w-100">' . strtoupper($row['status']) . '</span>';
                        // return '<span class="badge bg-info" style="width:100px;">' . strtoupper($row['status']) . '</span>';
                    } else {
                        return '<span class="badge bg-gradient-blooker text-white shadow-sm w-100">' . strtoupper($row['status']) . '</span>';
                        // return '<span class="badge bg-primary" style="width:100px;">' . strtoupper($row['status']) . '</span>';
                    }
                })


                ->addColumn('action', function ($row) {
                    $stockDetails = htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8');
                    $btn = '<div class="col text-end">
                    <div class="dropdown">
                        <button class="btn btn-primary dropdown-toggle btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">Action</button>
                        <ul class="dropdown-menu" style="">

                            <li><a class="dropdown-item edit" href="javascript:void(0);" data-StockDetails="'.$stockDetails.'"><i class="lni lni-pencil-alt" style="color: yelow;"></i> Post to PROUTE</a>
                            </li>


                        </ul>
                    </div>
                </div>';

                    return $btn;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }
    }
}
