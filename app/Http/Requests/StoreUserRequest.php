<?php

namespace App\Http\Requests;

use App\Models\Profile;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
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
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|max:255|min:8',
            'birth_date' => 'required|date|before:today',
            'gender' => 'required|max:255',
            'county' => 'required',
            'religion' => 'required',
            'sub_county' => 'required',
            'mobile_no' => [
                'required',
                'digits:10', 
                'max:10', 
                'min:10',
                Rule::unique(Profile::class),
            ],
            'national_id' => [
                'required',
                'string',
                'max:255',
                Rule::unique(Profile::class),
            ],
           
        ];
    }
}