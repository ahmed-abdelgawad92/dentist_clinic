<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDrugDiagnose extends FormRequest
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
            "drug.*"=>"nullable|string|unique:drugs,name",
            "drug_list.*"=>"nullable|string",
            "dose.*"=>"string"
        ];
    }
    public function messages()
    {
        return [
            'drug.*.unique'=>':input already exists in the database, better choose it from the list',
            'dose.*.string'=>'one of the dose is empty' 
        ];
    }
}
