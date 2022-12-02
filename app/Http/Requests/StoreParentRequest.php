<?php

namespace App\Http\Requests;

use App\Models\Student\Parents;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreParentRequest extends FormRequest
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
            'mobile_no' => [
               'required',
               'string',
               'digits:10',
               Rule::unique(Parents::class, 'mobile_no'),
           ],
           'type' => 'required',
           'gender' => 'required',
           'receive_sms' => 'required',
           'has_student' => 'required',
           'profession' => 'required',
           'address' => 'required'
       ];
    }
}