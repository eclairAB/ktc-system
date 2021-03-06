<?php

namespace App\Http\Requests;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ValidateSizeType extends FormRequest
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
            // 'code' => 'required',
            // 'name' => 'required',
            'size' => ['required', Rule::unique('container_size_types','code')],
            // 'type' => 'required',
        ];
    }

    public function messages()
    {
        return [
            // 'code.required' => 'Code field is required.',
            // 'name.required' => 'Name field is required.',
            'size.required' => 'Size field is required.',
            'size.unique' => 'Size already exist.',
            // 'type.required' => 'Type field is required.',
        ];
    }
}
