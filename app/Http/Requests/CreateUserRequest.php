<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // dd($request->user_id);
        return [
            'fname' => 'required',
            'email' => 'required|unique:users,email',
            'group_id' => 'required',
            'status' => 'required',
            'password' => 'min:6|required_with:confirm_password|same:confirm_password',
            'confirm_password' => 'min:6'
        ];
    }
    public function messages()
    {
        return [
            'fname.required' => 'Name is Required.',
            'email.required' => 'Email is Required',
            'group_id.required' => 'Role is Required.',
            'status.required' => 'Status is Required.',
            'password.required' => 'Password is Required',
        ];
    }
}
