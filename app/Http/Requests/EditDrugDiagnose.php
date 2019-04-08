<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditDrugDiagnose extends FormRequest
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
            "drug"=>"nullable|string|unique:drugs,name",
            "drug_list"=>"numeric|nullable",
            "dose"=>"string"
        ];
    }
    public function messages()
    {
        return [
            "drug_list.numeric"=>"Please select a right medicine from the list",
            "drug.unique"=>"The new medicine you wanted to create already existed in the database",
            "dose.string"=>"Please write down the dose of the medicine"
        ];
    }
}
