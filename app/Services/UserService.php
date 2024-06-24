<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Session;
use App\Models\{
	User,
};
use Auth;

class UserService
{
	public function allUsers()
	{
		return User::get();
	}
	public function userProfile($data, $id = null)
	{
		if ($id == null) {
			$user = new User;
		} else if ($id != null) {
			$user = User::find($id);
		}

		if (isset($data['profile_pic']) && !empty($data['profile_pic'])) {
			$filename = uploadImage($data['profile_pic'], filePath('admins'), $user->image);
			$user->image = $filename;
		}

		$user->name = $data['name'];
		$user->email = $data['email'];
		$user->mobile = $data['mobile'];
		$user->group_id = $data['group_id'];
		$user->status = $data['status'];
		return $user;
	}

    /******************************************************************/
    public function migrateGs1Member($data)
    {
        $settings = [
            'themeMode' => 'lightmode',
            'headerColor' => '',
            'sidebarColor' => '',
        ];
// echo "<pre>"; print_r($data); exit;
        $user = $data['memberData'];
        $newUser = new User;
        $newUser->group_id = 1;
        $newUser->user_type = $user['user_type'];
        $newUser->parent_memberID = 0;
        $newUser->slug = $user['slug'];
        $newUser->have_cr = $user['have_cr'];
        $newUser->cr_documentID = $user['cr_documentID'];
        $newUser->document_number = $user['document_number'];
        $newUser->fname = $user['fname'];
        $newUser->lname = $user['lname'];
        $newUser->email = $user['email'];
        $newUser->mobile = $user['mobile'];
        $newUser->image = $user['image'];
        $newUser->companyID = $user['companyID'];
        $newUser->cr_number = $user['cr_number'];
        $newUser->cr_activity = $user['cr_activity'];
        $newUser->company_name_eng = $user['company_name_eng'];
        $newUser->company_name_arabic = $user['company_name_arabic'];
        $newUser->gcpGLNID = $user['gcpGLNID'];
        $newUser->gln = $user['gln'];
        $newUser->gcp_expiry = date('Y-m-d h:i:s',strtotime($user['gcp_expiry']));
        $newUser->password = bcrypt('123456');
        $newUser->code = '123456';
        $newUser->status = 'active';
        $newUser->settings = $settings;
        return $newUser;
    }

}
