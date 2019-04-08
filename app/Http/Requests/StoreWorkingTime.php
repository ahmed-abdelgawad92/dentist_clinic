<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreWorkingTime extends FormRequest
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
            'day'=>"required|in:1,2,3,4,5,6,7",
            "time_from"=>"required|regex:/^[0-9]{2}:[0-9]{2}:[0-9]{2}$/",
            "time_to"=>"required|regex:/^[0-9]{2}:[0-9]{2}:[0-9]{2}$/"
        ];
    }

    public function messages()
    {
        return [
            "day.in"=>"Please select a day from the list",
            "day.required"=>"Please select a day from the list",
            "time_from.required"=>"Please select when the clinic is opened at this day",
            "time_from.regex"=>"Please select a valid time ",
            "time_to.required"=>"Please select when the clinic is closed at this day",
            "time_to.regex"=>"Please select a valid time ",
        ];
    }
}
