<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PurchaseReturnRequest extends FormRequest
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
            'order_no' => ['required',Rule::unique('purchase_returns')->ignore($this->id)],
            'supplier_id' => 'required',
            'status' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'order_no.required' => 'OrderNo is Required.',
            'order_no.unique' => 'OrderNo Must Be Unique.',
            'supplier_id.required' => 'Supplier is Required.',
            'status.required' => 'Status is Required.'
        ];
    }
}
