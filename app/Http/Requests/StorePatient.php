<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePatient extends FormRequest
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
            "pname"=>["required","regex:/^[a-zA-Z\s_]+$/"],
            "gender"=>["required","regex:/^(0|1)$/"],
            "dob"=>"required|numeric",
            "address"=>"required",
            "phone"=>["required","regex:/^(\+)?[0-9]{8,15}$/"],
            "diabetes"=>["required","regex:/^(0|1)$/"],
            "blood_pressure"=>["required","regex:/^(low|normal|high)$/"],
            "photo"=>"image|mimes:jpeg,png,jpg,gif"
        ];
    }

    public function messages()
    {
        return [
            "pname.required"=>"Please enter a patient name",
            "pname.regex"=>"Please enter a valid patient name that contains only alphabets , spaces and _",
            "gender.required"=>"please select a gender",
            "gender.regex"=>"please select a valid gender",
            "dob.required"=>"Please enter a date of birth",
            "dob.numeric"=>"Please enter a valid age",
            "address.required"=>"Please enter an address",
            "phone.required"=>"Please enter a phone no.",
            "phone.regex"=>"Please enter a valid phone no. that contains only numbers and can start with a +",
            "diabetes.required"=>"Please select the diabetes state",
            "diabetes.regex"=>"Please select a valid diabetes state",
            "blood_pressure.required"=>"Please select the blood pressure state",
            "blood_pressure.regex"=>"Please select a valid blood pressure state",
            "photo.mime"=>"Please upload a valid photo that has png, jpg, jpeg or gif extensions"
        ];
    }
}
