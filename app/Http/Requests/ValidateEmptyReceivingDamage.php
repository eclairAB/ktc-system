<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidateEmptyReceivingDamage extends FormRequest
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
            'damage_id'=> 'required',
            'component_id'=> 'required',
            'repair_id'=> 'required',
            'location'=> 'required',
            'length'=> 'nullable',
            'width'=> 'nullable',
            'quantity'=> 'nullable',
            'description'=> 'required'
        ];
    }

    public function messages()
    {
        return [
            'damage_id.required'=> 'Container Damage required',
            'component_id.required'=> 'Container Component required',
            'repair_id.required'=> 'Container Repair required',
            'location.required'=> 'Location required',
            // 'length.required'=> 'Length required',
            // 'width.required'=> 'Width required',
            // 'quantity.required'=> 'Quantity required',
            'description.required'=> 'Description required'
        ];
    }
}
