<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;

class ClaimRequest extends FormRequest
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
            'product_name' => ['required', Rule::unique('claims')->ignore($this->id)],
            'barcode' => ['required', Rule::unique('claims')->ignore($this->id)],
            'type' => 'required',
            'note' => 'required',
            'status' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'product_name.required' => 'Please Enter ProductName Name',
            'barcode.required' => 'Please Enter Product Barcode',
            'type.required' => 'Please Select Claim Type',
            'note.required' => 'Please Enter some note',
            'status.required' => 'Please Select Status'
        ];
    }

    public function failedValidation(Validator $validator)
    {
       throw new HttpResponseException(response()->json([
         'status'   => 400,
         'errors'      => $validator->errors()
     ]));
   }
}
