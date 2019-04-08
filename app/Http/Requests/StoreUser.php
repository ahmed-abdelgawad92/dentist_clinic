<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUser extends FormRequest
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
            'uname'=>["bail","required","regex:/^([a-zA-Z]+([\._@\-]?[0-9a-zA-Z]+)*){3,}$/","unique:users,uname","max:255"],
            'password'=>['required','min:8','regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/'],
            'confirm_password'=>'required|min:8|same:password',
            'phone'=>["required","regex:/^(\+)?[0-9]{8,15}$/"],
            'role'=>["required","regex:/^(0|1)+$/"],
            'photo'=>'image|mimes:jpeg,png,jpg,gif'
        ];
    }

    public function messages()
    {
        return [
            'name.required'=>'Please enter User\'s Full Name',
            'name.regex'=>'Please enter a valid Name that contains only alphabets , spaces and _',
            'uname.required'=>'Please enter Username',
            'uname.regex'=>"Please enter a valid Username that contains only alphabets, numbers, . , @ , _ or -, and not less than 3 alphabets, and starts with at least one alphabet",
            'uname.unique'=>'This Username is already taken, please enter another one',
            'uname.max'=>'Username must not be more than 255 characters',
            'password.required'=>'Please enter a password',
            'password.min'=>'Password must be at least 8 characters',
            'password.regex'=>'Password must contain at least 8 characters, one Uppercase letter, one Lowercase letter, a number, and a special character (#,?,!,@,$,%,^,&,* or -)',
            'confirm_password.required'=>'Please re-type the password',
            'confirm_password.min'=>'Password must be at least 8 characters',
            'confirm_password.same'=>'Password Confirmation must be exactly the same as Password',
            'phone.required'=>'Please enter Phone No.',
            'phone.regex'=>'Please enter a valid Phone No. that contains only numbers and can start with a (+)',
            'role.required'=>'Please select a role',
            'role.regex'=>'Please select a valid role',
            'photo.image'=>'Please upload a valid photo that has png, jpg, jpeg or gif extensions',
            'photo.mimes'=>'Please upload a valid photo that has png, jpg, jpeg or gif extensions'
        ];
    }
}
