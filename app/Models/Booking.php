<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
      use SoftDeletes;
      protected $dates = ['start_date', 'end_date', 'created_at', 'updated_at', 'deleted_at'];
    
    protected $table = 'bookings';
	
	
	public function calculateCost()
	{
		if($this->stay_type == 0){
			// Calculate cost by the daily
			
			
		} else {
			// Calculate cost by the monthly
			$start = new DateTime($this->start_date);
			
			$end = new DateTime($this->end_date);
			
			$months = $start->diff($end)->m + ($start->diff($end)->y*12);
			
			$listing = Listing::find($this->listing_id);
			
			$price['months'] = $months;
			$price['price'] = $listing->month_pricing;
			$price['subtotal'] = $months * $listing->month_pricing;
			$price['service_fee'] = round(.06 * $price['subtotal'], 2);
			$price['host_fee'] = round(.03 * $price['subtotal'], 2);
			
		}
		
	}
}