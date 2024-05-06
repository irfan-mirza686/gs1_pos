<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GroupService;
use Auth;
use Session;
use DataTables;
use App\Models\{
    Group,
    GroupModule,
    GroupPermission,
};

class GroupController extends Controller
{
    private $groupService;

    public function __construct(GroupService $groupService)
    {
        $this->groupService = $groupService;
    }
    /********************************************************************/
    public function index()
    {
        $pageTitle = "Roles";
        return view('roles.index', compact('pageTitle'));
    }
    /********************************************************************/
    public function List(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->groupService->allRoles();
            // echo "<pre>"; print_r($data->toArray()); exit;
            return Datatables::of($data)
                ->addIndexColumn()


                ->editColumn('role', function ($row) {
                    return strtoupper($row->name);
                })
                ->editColumn('permissions', function ($row) {
                    if ($row->group_permissions) {
                        $badge = [];
                        foreach ($row->group_permissions as $value) {
                            if ($value['access'] == 1) {
                                $badge[] = '<span class="badge bg-info style="cursor: pointer; width:100px;">' . $value['module_name'] . '</span>';
                            }
                        }
                        return implode(' | ', $badge);
                    }

                })

                ->addColumn('action', function ($row) {

                    $btn = '<div class="col text-end">
                    <div class="dropdown">
                        <button class="btn btn-primary dropdown-toggle btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">Action</button>
                        <ul class="dropdown-menu" style="">

                            <li><a class="dropdown-item edit" href="' . route('role.edit', $row->id) . '" ><i class="lni lni-pencil-alt" style="color: yelow;"></i> Edit</a>
                            </li>
                            <li><a class="dropdown-item edit" href="' . route('role.delete', $row->id) . '" ><i class="lni lni-trash" style="color: red;"></i> Delete</a>
                            </li>
                        </ul>
                    </div>
                </div>';

                    return $btn;
                })
                ->rawColumns(['permissions', 'action'])
                ->make(true);
        }
    }
    /********************************************************************/
    public function create()
    {
        $pageTitle = "Create Role";
        $groupModule = $this->groupService->groupModules();
        return view('roles.create', compact('pageTitle', 'groupModule'));
    }
    /********************************************************************/
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required'
        ]);
        try {
            $this->groupService->storeRole($request);
            \LogActivity::addToLog(strtoupper(Auth::user()->name) . ' Add a new Admin Role (' . $request->name . ')', route('roles'));
            Session::flash('flash_message_success', 'Data has been added successfully');
            return redirect(route('roles'));
        } catch (\Throwable $th) {
            Session::flash('flash_message_error', $th->getMessage());
            return redirect()->back();
        }
    }
    /********************************************************************/
    public function edit($id = null)
    {
        try {
            $pageTitle = "Edit Role";
            $groupModule = GroupModule::get()->toArray();
            $editGroup = Group::where(['id' => $id])->first();
            $data['editPermission'] = GroupPermission::select('module_page')->where(['group_id' => $id])->where('access', 1)->get()->toArray();
            $mergeArr = array_column($data['editPermission'], 'module_page');
            return view('roles.edit', compact('pageTitle', 'editGroup', 'groupModule', 'mergeArr'));
        } catch (\Throwable $th) {
            Session::flash('flash_message_error', $th->getMessage());
            return redirect()->back();
        }
    }
    /********************************************************************/
    public function update(Request $request, $id = null)
    {

        $request->validate([
            'name' => 'required'
        ]);

        try {
            $this->groupService->updateRole($request,$id);
            \LogActivity::addToLog(strtoupper(Auth::user()->name) . ' Update a Admin Role (' . $request->name . ')', route('roles'));
            Session::flash('flash_message_success', 'Data has been updated successfully');
            return redirect(route('roles'));
        } catch (\Throwable $th) {
            Session::flash('flash_message_error', $th->getMessage());
            return redirect()->back();
        }
    }
    /********************************************************************/
}
