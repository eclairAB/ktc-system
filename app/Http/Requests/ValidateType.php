<?php

namespace App\Http\Requests;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ValidateType extends FormRequest
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
            'code' => ['required', Rule::unique('types','code')],
            'name' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'code.required' => 'Code field is required.',
            'code.unique' => 'Code already exists.',
            'name.required' => 'Name field is required.',
        ];
    }
}
