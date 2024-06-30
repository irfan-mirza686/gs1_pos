<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class SaleRequest extends FormRequest
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
            'transactions' => 'required',
            'salesLocation' => 'required',
            'vat_no' => 'required',
            'order_no' => 'required',
            // 'delivery' => 'required',
            'customerName' => 'required',
            'mobile' => 'required',
            'type' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'transactions.required' => 'Transactions is required',
            'salesLocation.required' => 'Sales Location is required',
            'vat_no.required' => 'VAT # is required',
            'order_no.required' => 'Invoice # is not valid',
            // 'delivery.required' => 'Delivery is required',
            'customerName.required' => 'Customer Name is required',
            'mobile.required' => 'Customer Mobile is required',
            'type.required' => 'Type is required'

        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => 400,
            'errors' => $validator->errors()
        ]));
    }
}
