<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOralRadiology extends FormRequest
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
            'xray'=>'required|image|mimes:jpeg,png,jpg,gif',
            'xray_description'=>'string|nullable'
        ];
    }
    public function messages()
    {
        return [
            'xray.required'=>'You can\'t save an empty dental X-ray',
            'xray.mimes'=>'The Dental X-ray must be one of these types: JPEG, JPG, PNG or GIF'
        ];
    }
}
