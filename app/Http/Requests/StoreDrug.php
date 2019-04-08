<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDrug extends FormRequest
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
            'drug.*'=>'nullable|distinct|unique:drugs,name'
        ];
    }
    public function messages()
    {
        return [
            'drug.*.distinct'=>"Please don't repeat medicine's names",
            'drug.*.unique'=>"some medicines you already have on the database"
        ];
    }
}
