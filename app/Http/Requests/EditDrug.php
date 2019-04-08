<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditDrug extends FormRequest
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
            'drug'=>'required|unique:drugs,name'
        ];
    }
    public function message()
    {
        return [
            'drug.required'=>"Please enter a medicine name to edit", 
            'drug.unique'=>"This Medicine's name already existed in database"
        ];
    }
}
