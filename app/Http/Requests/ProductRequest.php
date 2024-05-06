<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class ProductRequest extends FormRequest
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
            'name' => ['required',Rule::unique('products')->ignore($this->id)],
            'brand' => 'required',
            'unit_id' => 'required',
            'status' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Product Name is Required.',
            'name.unique' => 'Product Name Must Be Unique.',
            'brand.required' => 'Brand is Required.',
            'unit_id.required' => 'Unit is Required.',
            'status.required' => 'Status is Required.'
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
