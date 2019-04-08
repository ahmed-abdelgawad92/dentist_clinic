<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckAvailableUname extends FormRequest
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
            'uname'=>["bail","required","regex:/^([a-zA-Z]+([\._@\-]?[0-9a-zA-Z]+)*){3,}$/","unique:users,uname","max:255"]
        ];
    }

    public function messages()
    {
        return [
            'uname.required'=>'Please enter Username',
            'uname.regex'=>'"Please enter a valid Username that contains only alphabets, numbers, . , @ , _ or -, and not less than 3 alphabets, and starts with at least one alphabet"',
            'uname.unique'=>'This Username is already taken, please enter another one',
            'uname.max'=>'Username must not be more than 255 characters'
        ];
    }
}
