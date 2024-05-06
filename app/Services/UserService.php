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

}