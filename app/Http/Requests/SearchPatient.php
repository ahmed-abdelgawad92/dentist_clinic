<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchPatient extends FormRequest
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
            "patient"=>["required","regex:/^([a-zA-Z\s_]+|[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}|[0-9]+)$/"]
        ];
    }
    public function messages()
    {
        return [
            "patient.required"=>"You can't search a patient with an empty input",
            "patient.regex"=>"You can search a patient only with Patient's File Number, Name or date of birth (in this format YYYY-MM-DD)"
        ];
    }
}
