<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChangePassword extends FormRequest
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
            'old_password'=>['required'],
            'new_password'=>['required','regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/'],
            'confirm_new_password'=>['required','same:new_password']
        ];
    }
    public function messages()
    {
        return [
            "old_password.required"=>"Please enter your old password",
            "new_password.required"=>"Please enter your new password",
            "new_password.regex"=>"Password must contain at least 8 characters, one Uppercase letter, one Lowercase letter, a number, and a special character (#,?,!,@,$,%,^,&,* or -)",
            "confirm_new_password.required"=>"Please Re-type password",
            "confirm_new_password.same"=>"Passwords don't match"
        ];
    }
}
