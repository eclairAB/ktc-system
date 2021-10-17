<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidateStaffField extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
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
            'id_no' => 'required',
            'firstname' => 'required',
            'lastname' => 'required',
            'user_id' => 'required',
            'user_type' => 'required',
            'contact_no' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'Invalid email.',
            'email.unique' => 'Email already in use.',
            'password_confirmation.required' => 'Password does not match.',
            'id_no.required' => 'ID number is required.',
            'firstname.required' => 'First name is required.',
            'lastname.required' => 'Last name is required.',
            'user_id.required' => 'User ID is required.',
            'user_type.required' => 'User Type is required.',
            'contact_no.required' => 'Contact number is required.',
        ];
    }
}
