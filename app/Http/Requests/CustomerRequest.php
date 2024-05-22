<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class CustomerRequest extends FormRequest
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
            'name' => ['required', Rule::unique('customers')->ignore($this->id)],
            'mobile' => ['required', Rule::unique('customers')->ignore($this->id)],
            'vat' => Rule::unique('customers')->ignore($this->id)
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Customer Name is Required.',
            'name.unique' => 'Customer Name Must Be Unique.',
            'mobile.required' => 'Mobile Number is Required.',
            'mobile.unique' => 'Mobile Number Must Be Unique.',
            'vat.unique' => 'Vat Must be Unique'
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
