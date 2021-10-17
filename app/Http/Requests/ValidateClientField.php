<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidateClientField extends FormRequest
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
            'email' => 'email|unique',
            'password_confirmation' => 'same:password',
            'code_name' => 'required',
            'contact_no' => 'required',
            'user_id' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'Invalid email.',
            'email.unique' => 'Email already in use.',
            'password_confirmation.same' => 'Password does not match.',
            'code_name.required' => 'Code name required.',
            'contact_no.required' => 'Contact number required.',
            'user_id.required' => 'User ID required.',
        ];
    }
}
