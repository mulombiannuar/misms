<?php

namespace App\Http\Requests;

use App\Models\Student\Student;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreStudentRequest extends FormRequest
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
            'name' => [
                'required', 
                'string', 
                'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'admission_no' => [
                'required',
                'string',
                'max:255',
                Rule::unique(Student::class, 'admission_no'),
            ],
            'upi' => [
                'required',
                'string',
                'max:255',
                Rule::unique(Student::class, 'upi'),
            ],
            
            'birth_date' => [
                'required', 
                'date'],
            'admission_date' => [
                'required', 
                'date'],
            'student_image' => [
                'image', 
                'nullable', 
                'max:1999',
                'dimensions:max_width=300,max_height=300'
            ],
                
            'gender' => ['required'],
            'county' => ['required'],
            'address' => ['required'],
            'impaired' => ['required'],
            'religion' => ['required'],
            'sub_county' => ['required'],
            'kcpe_year' => ['required'],
            'kcpe_marks' => ['required'],
        ];
    }
}