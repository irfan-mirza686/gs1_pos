<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;

class BrandRequest extends FormRequest
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

        return [
            'name' => ['required', Rule::unique('brands')->ignore($this->id)],
            'status' => 'required|string'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Brand Name is Required',
            'name.string' => 'Brand Name Must be Valid',
            'name.unique' => 'Brand Name is Duplicate',
            'status.required' => 'Status is Required',
            'status.string' => 'Status Must be Valid'
        ];
    }

    public function failedValidation(Validator $validator)
    {
        if ($this->ajax()) {
            throw new HttpResponseException(response()->json([
                'status' => 400,
                'errors' => $validator->errors()
            ]));
        } else {
            throw new HttpResponseException(response()->json([
                'errors' => $validator->errors()
            ], 422));
        }
    }
}
