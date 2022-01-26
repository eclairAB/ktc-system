<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidateContainerReleasing extends FormRequest
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
            'inspected_by'=> 'required',
            'inspected_date'=> 'required',
            'booking_no'=> 'nullable',
            'consignee'=> 'required',
            'container_no'=> 'required',
            'hauler'=> 'nullable',
            'plate_no'=> 'nullable',
            'seal_no'=> 'nullable',
            'container_photo'=> 'nullable',
            'remarks'=> 'nullable',
        ];
    }

    public function messages()
    {
        return [
            'inspected_by.required' => 'Inspected By field required.',
            'inspected_date.required' => 'Inspected Date field required.',
            'container_no.required' => 'Container No. field not match.',
            // 'booking_no.required' => 'Booking No. field not match.',
            'consignee.required' => 'Consignee field required.',
            'hauler.required' => 'Hauler  fieldrequired.',
            'plate_no.required' => 'Plate No. field required.',
            // 'seal_no.required' => 'Seal No. field required.',
            // 'container_photo.required'  => 'Photo is required.',
        ];
    }
}
