<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditUser extends FormRequest
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
            'name'=>["required","regex:/^[a-zA-Z\s_]+$/"],
            'phone'=>["required","regex:/^(\+)?[0-9]{8,15}$/"],
            'role'=>["required","regex:/^(0|1)+$/"]
        ];
    }

    public function messages()
    {
        return [
            'name.required'=>'Please enter User\'s Full Name',
            'name.regex'=>'Please enter a valid Name that contains only alphabets , spaces and _',
            'phone.required'=>'Please enter Phone No.',
            'phone.regex'=>'Please enter a valid Phone No. that contains only numbers and can start with a (+)',
            'role.required'=>'Please select a role',
            'role.regex'=>'Please select a valid role'
        ];
    }
}
