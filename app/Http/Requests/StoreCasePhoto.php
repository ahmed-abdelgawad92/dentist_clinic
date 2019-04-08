<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCasePhoto extends FormRequest
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
            "case_photo"=>"bail|required|image|mimes:jpeg,png,jpg,gif",
            "before_after"=>"bail|required|boolean"
        ];
    }
    public function messages()
    {
        return [
            "case_photo.required"=>"Please upload a photo",
            "case_photo.mime"=>"Please upload a valid photo that has png, jpg, jpeg or gif extensions",
            "before_after.required"=>"Please select whether this case photo is before or after treatment",
            "before_after.boolean"=>"Please select whether this case photo is before or after treatment",
        ];
    }
}
