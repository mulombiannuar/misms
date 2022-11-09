<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSessionRequest extends FormRequest
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
            'session' => 'required',
            'term' => 'required',
            'opening_date' => 'required|date|before_or_equal:opening_date',
            'closing_date' => 'required|date|after:tomorrow',
         ];
    }
}