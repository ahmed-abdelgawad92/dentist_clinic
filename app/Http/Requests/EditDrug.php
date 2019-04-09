<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Drug;

class EditDrug extends FormRequest
{
    private $id;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $this->id = $this->route('id');
        $drug = Drug::find($this->id);
        return $drug;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {   
        return [
            'drug'=>[
                'required',
                'unique:drugs,name,'.$this->id
            ]
        ];
    }
    public function message()
    {
        return [
            'drug.required'=>"Please enter a medicine name to edit", 
            'drug.unique'=>"This Medicine's name already existed in database"
        ];
    }
}
