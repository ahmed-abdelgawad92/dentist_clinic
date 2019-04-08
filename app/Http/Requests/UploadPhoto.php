<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadPhoto extends FormRequest
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
            'photo'=>'required|image|mimes:jpeg,png,jpg,gif'
        ];
    }
    public function messages()
    {
        return [
            'photo.required'=>'Please select a photo to upload',
            "photo.mime"=>"Please upload a valid photo that has png, jpg, jpeg or gif extensions"
        ];
    }
}
