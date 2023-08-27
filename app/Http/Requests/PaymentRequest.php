<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

class PaymentRequest extends FormRequest
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
            'nome' => 'required|string',
            'email' => 'required|email',
            'valorTotal' => 'required|numeric|min:0',
            'products' => 'required|array|min:1',
            'products.*.name' => 'required|string',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.unitValue' => 'required|numeric|min:0',
            'products.*.category' => 'required|string',
            'paymentMethods' => 'required|array|min:1',
            'paymentMethods.*.id' => 'nullable|string',
            'paymentMethods.*.value' => 'required|numeric|min:0',
            'paymentMethods.*.type' => 'required|string|in:points,credit-card-mercado-pago,credit-card-paypal,voucher',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors(),
        ], 422));
    }
}
