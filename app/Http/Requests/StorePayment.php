<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePayment extends FormRequest
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
            "payment"=>"required|numeric"
        ];
    }

    public function messages()
    {
        return [
            "payment.required"=>"Please enter amount of payment to be paid",
            "payment.numeric"=>"Please enter a valid payment (ONLY Numbers are allowed)"
        ];
    }
}
