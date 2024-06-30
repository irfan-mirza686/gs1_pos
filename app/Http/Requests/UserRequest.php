<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class UserRequest extends FormRequest
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
    public function rules(Request $request)
    {
        // dd($request->user_id);
        return [
            'fname' => 'required',
            'email' => ['required', Rule::unique('users')->ignore($request->user_id)],
            'group_id' => 'required',
            'status' => 'required',
        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'Name is Required.',
            'email.required' => 'Email is Required',
            'group_id.required' => 'Role is Required.',
            'status.required' => 'Status is Required.',
        ];
    }
}
