<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
            'name' => 'required|max:255',
            'password' => 'required|max:255|min:8',
            'birth_date' => 'required|date|before:today',
            'gender' => 'required|max:255',
            'county' => 'required',
            'religion' => 'required',
            'sub_county' => 'required',
            'email' => [
                'required',
                'email', 
                'max:255', 
                Rule::unique('users')->ignore($this->id),
            ],
            'mobile_no' => [
                'required',
                'digits:10', 
                'max:10', 
                'min:10',
                Rule::unique('profiles')->ignore($this->id),
            ],
            'national_id' => [
                'required',
                'string',
                'max:255',
                Rule::unique('profiles')->ignore($this->id),
            ],
           
        ];
    }
}