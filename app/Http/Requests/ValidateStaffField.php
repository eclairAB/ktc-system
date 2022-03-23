<?php

namespace App\Http\Requests;
use Illuminate\Validation\Rule;
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
            // 'email' => ['required', Rule::unique('users','email')],
            'password_confirmation' => 'same:password',
            'id_no' => 'required',
            'firstname' => 'required',
            'lastname' => 'required',
            'user_id' => 'required',
            // 'contact_no' => 'nullable',
        ];
    }

    public function messages()
    {
        return [
            // 'email.required' => 'Invalid email.',
            // 'email.unique' => 'Email already in use.',
            'id_no.required' => 'ID number is required.',
            'firstname.required' => 'First name is required.',
            'lastname.required' => 'Last name is required.',
            'user_id.required' => 'User ID is required.',
            // 'contact_no.required' => 'Contact number is required.',
        ];
    }
}
