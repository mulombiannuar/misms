<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SchoolDetailsRequest extends FormRequest
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
            'name' => 'string|required|max:255',
            'code' => 'string|required|max:255',
            'email' => 'email|required|max:255',
            'address' => 'string|required|max:255',
            'domain' => 'string|required|max:255',
            'motto' => 'string|required|max:255',
            'principal' => 'string|required|max:255',
            'category' => 'string|required|max:255',
            'logo' => 'image|required|max:1999',
            'telephone' => 'string|required|digits:10|min:10|max:10',
        ];
    }
}