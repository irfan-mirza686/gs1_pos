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
        // dd($this->id);
        return [
            'productnameenglish' => ['required', Rule::unique('products')->ignore($this->id)],
            'BrandName' => 'required',
            'unit' => 'required',
            'size' => 'required',
            'purchase_price' => 'required_if:type,non_gs1',
            'selling_price' => 'required_if:type,non_gs1'
        ];
    }

    public function messages()
    {
        return [
            'productnameenglish.required' => 'Product Name is Required.',
            'productnameenglish.unique' => 'Product Name Must Be Unique.',
            'BrandName.required' => 'Brand is Required.',
            'unit.required' => 'Unit is Required.',
            'size.required' => 'Size is Required.',
            'purchase_price.required_if' => 'Purchase Price is required if Product Type is Non GS1',
            'selling_price.required_if' => 'Selling Price is required if Product Type is Non GS1'
        ];
    }
    public function failedValidation(Validator $validator)
    {

            throw new HttpResponseException(response()->json([
                'errors' => $validator->errors()
            ], 422));

    }
}
