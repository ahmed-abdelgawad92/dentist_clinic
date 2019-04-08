<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDiagnose extends FormRequest
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
            "diagnose_type"=>"required|min:1",
            "teeth_name"=>"required|min:1",
            "teeth_color"=>"required|min:1",
            "description.*"=>"required|string",
            "diagnose_type.*"=>"required|string",
            "teeth_name.*"=>"required|string",
            "teeth_color.*"=>"required|string|max:7",
            "price.*"=>"required|numeric",
            "discount"=>"nullable|numeric",
            "discount_type"=>"required_with:discount"
        ];
    }

    public function messages()
    {
        return [
            "description.*.required"=>"You can't create a Diagnosis with empty description",
            "diagnose_type.*.required"=>"You must enter the diagnosis type",
            "teeth_name.*.required"=>"Please don't try to missuse the dynamic creation process , it's only there to help you",
            "teeth_color.*.required"=>"Please don't try to missuse the dynamic creation process , it's only there to help you",
            "price.*.required"=>"You must enter the price of this case",
            "price.*.numeric"=>"The price must be a valid number",
            "discount.numeric"=>"Please Enter a valid Discount value (only numbers allowed)",
            "discount_type.required_with"=>"Please choose the discount type whether precentage or amount of money"
        ];
    }
}
