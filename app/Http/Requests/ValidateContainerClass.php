<?php

namespace App\Http\Requests;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ValidateContainerClass extends FormRequest
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
            'class_code' => ['required', Rule::unique('container_classes','class_code')],
            'class_name' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'class_code.required' => 'Class Code field is required.',
            'class_code.unique' => 'Class Code already exists.',
            'class_name.required' => 'Class Name field is required.',
        ];
    }
}
