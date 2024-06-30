<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\UserRequest;
use App\Http\Requests\CreateUserRequest;
use DataTables;
use App\Services\UserService;
use Illuminate\Support\Facades\Validator;
use Auth;
use Session;
use Hash;

class UsersController extends Controller
{
    protected $userService;
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    /********************************************************************/
    public function authenticateRole($module_page = null)
    {
        $permissionCheck = checkRolePermission($module_page);
        if ($permissionCheck->access == 0) {
            Session::flash('flash_message_warning', 'You have no permission');
            return redirect(route('dashboard'))->send();
        }
    }
    /******************************************************/
    public function index()
    {
        $this->authenticateRole("user_management");
        $this->authenticateRole("users");

        $pageTitle = "Users";
        $user_info = session('user_info');
        return view('user.staff.index', compact('pageTitle', 'user_info'));
    }
    /******************************************************/
    public function List(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->userService->allUsers();
            // echo "<pre>"; print_r($data->toArray()); exit;
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('image', function ($row) {
                    if ($row->image) {
                        $image = getFile('admins', $row->image);
                        // $image = \Config::get('app.url') . '/assets/uploads/admins/' . $row->image;
                    } else {
                        $image = asset('assets/uploads/no-image.png');
                    }

                    return '<img src="' . $image . '" border="0"
                    width="50" class="img-rounded" align="center" style="border-radius:10%;"/>';
                })
                ->editColumn('group_id', function ($row) {
                    return ($row->role) ? strtoupper($row->role->name) : "";
                })

                ->editColumn('status', function ($row) {
                    if ($row->status == 'active') {
                        return '<span class="badge bg-success updateStatus" data-UserID="' . $row->id . '" data-Status="inactive" style="cursor: pointer; width:100px;">' . strtoupper($row->status) . '</span>';
                    } else if ($row->status == 'inactive') {
                        return '<span class="badge bg-danger updateStatus" data-UserID="' . $row->id . '" data-Status="active" style="cursor: pointer; width:100px;">' . strtoupper($row->status) . '</span>';
                    }
                })

                ->addColumn('action', function ($row) {

                    $btn = '<div class="col text-end">
                    <div class="dropdown">
                        <button class="btn btn-primary dropdown-toggle btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">Action</button>
                        <ul class="dropdown-menu" style="">
                        <li><a class="dropdown-item updateUserPass" data-Code="' . $row->code . '" href="' . route('user.pass.update', $row->id) . '"><i class="lni lni-eye" style="color: blue;"></i> Update Password</a>
                            </li>
                            <li><a class="dropdown-item" href="' . route('user.edit', $row->id) . '"><i class="lni lni-pencil-alt" style="color: yelow;"></i> Edit</a>
                            </li>
                            <li><a class="dropdown-item del" href="' . route('user.delete', $row->id) . '"><i class="lni lni-trash" style="color: red;"></i> Delete</a>
                            </li>

                        </ul>
                    </div>
                </div>';

                    return $btn;
                })
                ->rawColumns(['image', 'status', 'action'])
                ->make(true);
        }
    }
    /******************************************************/
    public function profile(Request $request)
    {
        $pageTitle = "Profile";
        $user = Auth::user();
        return view('user.profile.index', compact('pageTitle', 'user'));
    }
    /******************************************************/
    public function updateCurrentUserPassword(Request $request)
    {
        if ($request->ajax()) {
            try {
                $data = $request->all();
                $validator = Validator::make($request->all(), [
                    'current_pass' => 'required',
                    'new_pass' => 'min:6|required_with:confirm_password|same:confirm_password',
                    'confirm_password' => 'min:6'
                ]);

                if ($validator->fails()) {
                    return response()->json([
                        'status' => 400,
                        'errors' => $validator->messages()
                    ]);
                }
                // echo "<pre>"; print_r($request->all()); exit;
                if (Hash::check($data['current_pass'], Auth::user()->password)) {

                    User::find(Auth::user()->id)->update(['password' => bcrypt($data['new_pass']), 'code' => $data['new_pass']]);
                    return response()->json(['status' => 200, 'message' => 'Password updated']);
                } else {
                    return response()->json(['status' => 422, 'message' => 'Current Password is incorrect']);
                }
            } catch (\Throwable $th) {
                return response()->json(['status' => 200, 'message' => $th->getMessage()]);
            }

        }
    }
    /******************************************************/
    public function profileUpdate(UserRequest $request)
    {
        try {
            $data = $request->all();
            $id = Auth::user()->id;
            $user = $this->userService->userProfile($data, $id);
            $user->save();
            Session::flash('flash_message_success', 'Profile Updated Successfully');
            return redirect()->back();
        } catch (\Throwable $th) {
            Session::flash('flash_message_error', $th->getMessage());
            return redirect()->back();
        }
    }
    /******************************************************/
    public function create()
    {
        $this->authenticateRole("user_management");
        $this->authenticateRole("users");

        $pageTitle = "Create User";
        $user_info = session('user_info');
        $groups = Group::get();
        return view('user.staff.create', compact('pageTitle', 'user_info', 'groups'));
    }
    /******************************************************/
    public function store(CreateUserRequest $request)
    {
        try {
            $settings = [
                'themeMode' => 'lightmode',
                'headerColor' => '',
                'sidebarColor' => '',
            ];

            $data = $request->all();
            $user = $this->userService->userProfile($data, $id = '');
            $user->password = bcrypt($data['password']);
            $user->code = $data['password'];
            $user->user_id = Auth::user()->id;
            $user->settings = $settings;
            $user->save();
            Session::flash('flash_message_success', 'User Profile Created Successfully');
            return redirect(route('users'));
        } catch (\Throwable $th) {
            Session::flash('flash_message_error', $th->getMessage());
            return redirect()->back();
        }

    }
    /******************************************************/
    public function edit($id = null)
    {
        try {
            $this->authenticateRole("user_management");
            $this->authenticateRole("users");

            $user_info = session('user_info');
            $pageTitle = "Update User";
            $user = User::with('role')->find($id);
            $groups = Group::get();
            // echo "<pre>"; print_r($user); exit;
            return view('user.staff.edit', compact('pageTitle', 'user', 'user_info', 'groups'));
        } catch (\Throwable $th) {
            Session::flash('flash_message_error', $th->getMessage());
            return redirect()->back();
        }

    }
    /******************************************************/
    public function update(UserRequest $request, $id = null)
    {
        try {
            $data = $request->all();
            $user = $this->userService->userProfile($data, $id);
            $user->save();
            Session::flash('flash_message_success', 'User Updated Successfully');
            return redirect(route('users'));
        } catch (\Throwable $th) {
            Session::flash('flash_message_error', $th->getMessage());
            return redirect()->back();
        }
    }
    /******************************************************/
    public function userPassUpdate(Request $request, $id = null)
    {
        if ($request->ajax()) {
            try {
                User::find($id)->update(['password' => bcrypt($request->password), 'code' => $request->password]);
                return response()->json(['status' => 200, 'message' => 'User Password Updated Successfully']);
            } catch (\Throwable $th) {
                return response()->json(['status' => 200, 'message' => $th->getMessage()]);
            }
        }
    }
    /********************************************************/
    public function delete($id = null)
    {
        try {
            $user = User::find($id);
            if ($user) {
                @unlink(filePath('admins') . '/' . $user->image);
                $user->delete();
                return response()->json(['status' => 200, 'message' => 'User Deleted Successfully']);
            }
        } catch (\Throwable $th) {
            return response()->json(['status' => 500, 'message' => $th->getMessage()], 500);
        }

    }
}
