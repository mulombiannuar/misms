<?php

namespace App\Http\Requests;

use App\Models\Academic\Subject;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSubjectRequest extends FormRequest
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
            'subject_name' => [
                'required', 
                'string', 
                'max:255',
                Rule::unique(Subject::class, 'subject_name'),
            ],
            'subject_code' => [
                'required', 
                 Rule::unique(Subject::class, 'subject_code')
            ],
            'subject_short' => [
                'required',
                'string',
            ],
        ];
    }
}