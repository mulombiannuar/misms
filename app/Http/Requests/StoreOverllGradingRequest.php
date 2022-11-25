<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOverllGradingRequest extends FormRequest
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
            'form_numeric' => 'integer|required',
            'grade_name' => 'array|required',
            'min_score' => 'array|required',
            'max_score' => 'array|required',
            'score_remarks' => 'array|required',
            'principal_remarks' => 'array|required',
         ];
    }
}