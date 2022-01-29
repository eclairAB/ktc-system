<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidateContainerAging extends FormRequest
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
        if($this->option == 'ALL') {
            return [
                'date_in_from' => 'required',
                'date_in_to' => 'required',
                'date_out_from' => 'required',
                'date_out_to' => 'required',
            ];
        }
        else if($this->option == 'IN') {
            return [
                'date_in_from' => 'required',
                'date_in_to' => 'required',
            ];
        }
        else if($this->option == 'OUT') {
            return [
                'date_out_from' => 'required',
                'date_out_to' => 'required',
            ];
        }
    }
}
