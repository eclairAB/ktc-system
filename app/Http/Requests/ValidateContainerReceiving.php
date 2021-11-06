<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidateContainerReceiving extends FormRequest
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
            'container_no'=> 'required',
            'client_id'=> 'required',
            'size_type'=> 'required',
            'class'=> 'required',
            'type'=> 'required',
            // 'height'=> 'required',
            'empty_loaded'=> 'required',
            'manufactured_date'=> 'required',
            'yard_location'=> 'required',
            // 'acceptance_no'=> 'required',
            'consignee'=> 'required',
            'hauler'=> 'required',
            'plate_no'=> 'required',
            // 'upload_photo'=> 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'container_photo'=> 'required',
            'signature'=> 'required',
            'remarks'=> 'nullable',
        ];
    }

    public function messages()
    {
        return [
            'inspected_by.required' => 'Inspected By field required.',
            'inspected_date.required' => 'Inspected Date field required.',
            'container_no.required' => 'Container No. field required.',
            'size_type.required' => 'Size Type field required.',
            'class.required' => 'Class field required.',
            'type.required' => 'Type field required.',
            // 'height.required' => 'Height field required.',
            'empty_loaded.required' => 'Empty Loaded field required.',
            'manufactured_date.required' => 'Manufactured Date field required.',
            'yard_location.required' => 'Yard Location field required.',
            'hauler.required' => 'Hauler  fieldrequired.',
            'plate_no.required' => 'Plate No. field required.',
            // 'acceptance_no.required' => 'Acceptance No. field required.',
            // 'upload_photo.max'  => 'Maximum size to upload is 2MB.',
            'container_photo.required'  => 'Photo is required.',
            'signature.required'  => 'Signature field is required.',
        ];
    }
}
