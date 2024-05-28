<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use App\Rules\DifferentGln;

class StockTransferRequest extends FormRequest
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
            'request_no' => ['required', Rule::unique('stock_transfers')->ignore($this->id)],
            'gln_from' => ['required', 'string', new DifferentGln],
            'gln_to' => ['required', 'string', new DifferentGln]
        ];
    }

    public function messages()
    {
        return [
            'request_no.required' => 'Request # is Required.',
            'request_no.unique' => 'Request # is already taken.',
            'gln_from.required' => 'GLN From is Required.',
            'gln_to.required' => 'GLN To is Required.'
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
