<?php
namespace App\Services;

use App\Models\{
    Group,
    GroupModule,
    GroupPermission,
};
use DB;

class GroupService
{
    public function allRoles()
    {
        return Group::with('group_permissions')->get();
    }
    /********************************************************************/
    public function groupModules()
    {
        return GroupModule::get();
    }
    /********************************************************************/
    public function storeRole($request)
    {
        // echo "<pre>"; print_r($request->all()); exit;
        $groupName = new Group;

        $groupName->name = $request->name;

        $groupName->save();

        $groupId = DB::getPdo()->lastInsertId();

        /*Get Last Insert ID of Group_Name_Table and Insert into Group_Permissions_Table */
        $insertData = $request->all();

        $module_id = $request->get('txtModID');
        $permission_modulename = $request->get('txtModname');
        $permission_modulepage = $request->get('txtModpage');
        $permission_access = $request->get('txtaccess');

        $permission = [];
        foreach ($permission_access as $key => $val) {

            $permission[$val] = isset($permission_modulepage[$val]) ? 1 : 0;
        }
        // $checkPermission = ['txtaccess' => $permission];
        foreach ($permission_modulepage as $Pkey => $PID) {

            $insertData = new GroupPermission;
            $insertData->group_id = $groupId;
            $insertData->module_id = $module_id[$Pkey];
            $insertData->module_name = $permission_modulename[$Pkey];
            $insertData->module_page = $permission_modulepage[$Pkey];
            $insertData->access = isset($permission[$Pkey]) ? 1 : 0;
            $insertData->save();
        }
    }
    /********************************************************************/
    public function updateRole($request, $id = null)
    {
        $groups = Group::findorfail($id);
        $groups->name = $request->get('name');
        $groups->save();

        $module_id = $request->get('txtModID');
        $permission_modulename = $request->get('txtModname');
        $permission_modulepage = $request->get('txtModpage');
        $permission_access = $request->get('txtaccess');
        $permission = [];
        foreach ($permission_access as $key => $val) {

            $permission[$val] = isset($permission_modulepage[$val]) ? 1 : 0;
        }
        GroupPermission::where('group_id', $id)->delete();
        foreach ($permission_modulepage as $Pkey => $PID) {

            $insertData = new GroupPermission;
            $insertData->group_id = $id;
            $insertData->module_id = $module_id[$Pkey];
            $insertData->module_name = $permission_modulename[$Pkey];
            $insertData->module_page = $permission_modulepage[$Pkey];
            $insertData->access = isset($permission[$Pkey]) ? 1 : 0;
            $insertData->save();
        }
    }
    /********************************************************************/
}
