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
            'email' => 'email',
            'password_confirmation' => 'same:password',
            'code_name' => 'required',
            'contact_no' => 'required',
            'user_id' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'email' => 'Invalid email.',
            'password_confirmation' => 'Password does not match.',
            'code_name' => 'Code name required.',
            'contact_no' => 'Contact number required.',
            'user_id' => 'User ID required.',
        ];
    }
}
