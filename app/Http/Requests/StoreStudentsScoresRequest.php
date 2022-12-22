<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentsScoresRequest extends FormRequest
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
            'students' => 'required|array',
            'scores' => 'required|array',
            'section_id' => 'required|integer',
            'subject_id' => 'required|integer',
            'section_numeric' => 'required|integer',
            'exam_record_id' => 'required|integer',
        ];
    }
}
