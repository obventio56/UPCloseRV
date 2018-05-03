<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

    /**
     * This class is only for the initial creation of the Listing
     */
class ValidListing extends FormRequest
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
            'name' => 'required|max:500',
            'propertyType' => 'required',
            'hostType' => 'required'
        ];
    }
}