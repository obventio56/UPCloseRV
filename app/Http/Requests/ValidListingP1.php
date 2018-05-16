<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Listing;
use Auth;

    /**
     * This class is only for the initial creation of the Listing
     */
class ValidListingP1 extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
		$listing = Listing::find($this->id);
		
        return ($listing->user_id == Auth::user()->id || Auth::user()->ability('admin, client', 'edit-other-listings');
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
            'hostType' => 'required',
			'vehicleLength' => 'numeric',
			'description' => 'min:3'
        ];
    }
}
