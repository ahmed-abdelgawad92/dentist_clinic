<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddDiscount extends FormRequest
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
            "discount"=>"required|numeric",
            "discount_type"=>"required|boolean"
        ];
    }
    public function messages()
    {
        return [
            "discount.required"=>"Please enter a discount value",
            "discount.numeric"=>"Please enter a valid discount value (ONLY Numbers are allowed)",
            "discount_type.boolean"=>"Please the discount type whether by percent or EGP"
        ];
    }
}
