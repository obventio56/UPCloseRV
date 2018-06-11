<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\User;

class Listing extends Model
{
	// Traits
	use SoftDeletes;
	
	// Variables
	protected $dates = ['created_at', 'deleted_at'];
	
	// Relationships
	public function rvTypes()
	{
	return $this->hasMany('App\Models\ListingRVTypes');
	}

	public function exceptions()
	{
	return $this->hasMany('App\Models\ListingException');
	}
	
	public function address()
	{
		return $this->hasOne('App\Models\ListingAddress');
	}
	
	public function owner()
	{
		return $this->belongsTo('App\User');
	}
	
	
	// Functions
 
   
  
  public function amenityList() {		
    return 'hello'; //implode(',' array_column ($this->amenities , 'name' ));
  }
	
  
	public function getPrimaryImage()
	{
		$image = ListingImages::where('listing_id', '=', $this->id)
			->where('primary', '=', '1')
			->first();
		
		return $image->url;
	}

	// To pull only published listings.
	public function scopeGetPublished($q)
	{
		return $q->where('listings.published', '=', '1')->get();
	}
	
	public function isAvailable($startDate, $endDate)
	{
		if($this->hasBooking($startDate, $endDate))
		{
			return false;
		} 
	
		$exception = ListingException::
			  where( function($q) use($startDate, $endDate) {
				   $q->where( function($q) use ($startDate, $endDate) {
						$q->where('start_date', '>=', $startDate)
						  ->where('start_date', '<=', $endDate);
					})
					->orWhere( function($q) use ($startDate, $endDate) {
						$q->where('end_date', '>', $startDate)
						  ->where('end_date', '<=', $endDate);
					});
			   })
				->where('listing_id', '=', $this->id)
				->where('available', '=', 0)
				->whereNull('deleted_at')
				->first();
		
        if($exception){
            return false;
        } else {
            return true;
        }
		
		return true;
	}
	
    // Check for an existing exception, optionally pass in an exception id to exclude
    public function hasException($startDate, $endDate, $exception_id)
    {
		if(isset($exception_id)){
			
			$exception = ListingException::
				where('id', '!=', $exception_id)
			   ->where( function($q) use($startDate, $endDate) {
				   $q->where( function($q) use ($startDate, $endDate) {
						$q->where('start_date', '>=', $startDate)
						  ->where('start_date', '<=', $endDate);
					})
					->orWhere( function($q) use ($startDate, $endDate) {
						$q->where('end_date', '>', $startDate)
						  ->where('end_date', '<=', $endDate);
					});
			   })
				->where('listing_id', '=', $this->id)
				->first();
			
		} else {
			$exception = ListingException::
			   where( function($q) use ($startDate, $endDate) {
				   $q->where( function($q) use ($startDate, $endDate) {
						$q->where('start_date', '>=', $startDate)
						  ->where('start_date', '<=', $endDate);
					})
					->orWhere( function($q) use ($startDate, $endDate) {
						$q->where('end_date', '>', $startDate)
						  ->where('end_date', '<=', $endDate);
					});
				})
				->where('listing_id', '=', $this->id)
				->whereNull('deleted_at')
				->first();
		}
		
        if($exception){
            return true;
        } else {
            return false;
        }
    }
    
    public function hasBooking($startDate, $endDate)
    {
		
		$booking = Booking::
            where( function($q) use ($startDate, $endDate) {
				$q->where( function($r) use ($startDate, $endDate){
					$r->where('start_date', '>=', $startDate)
                  	  ->where('start_date', '<=', $endDate);
				})->orWhere( function($r) use ($startDate, $endDate) {
				   $r->where('end_date', '>', $startDate)
                  	 ->where('end_date', '<=', $endDate);
				});
                
            })
            ->where('listing_id', '=', $this->id)
			->whereNull('canceled_at')
			->whereNull('deleted_at')
            ->first();
        /* Flawed
		$booking = Booking::
            where( function($q) use ($startDate, $endDate) {

                $q->where('start_date', '>=', $startDate)
                  ->where('start_date', '<=', $endDate);
            })
            ->orWhere( function($q) use ($startDate, $endDate) {
                $q->where('end_date', '>', $startDate)
                  ->where('end_date', '<=', $endDate);
            })
            ->where('listing_id', '=', $this->id)
			->whereNull('canceled_at')
			->whereNull('deleted_at')
            ->first();*/
        
        if($booking){
            return true;
        } else {
            return false;
        }
    }
    
    public function isListingOwner($id)
    {
        if($this->user_id == $id){
            return true;
        } else {
            return false;
        }
    }
	
	// Check if a listing can be published
	public function canPublish()
	{
		$photos = ListingImages::where('listing_id', '=', $this->id)->get();
		$address = ListingAddress::where('id', '=', $this->id)->first();
		$user = User::where('id', '=', $this->user_id)->first();
		
		if(
			isset($this->name) &&
			isset($this->property_type_id) &&
			isset($this->max_vehicle_length) &&
			isset($this->check_in) &&
			isset($this->check_out) &&
			( 
				( $this->day_rental == 1 && isset($this->day_pricing) ) || 
				( $this->month_rental == 1 && isset($this->month_pricing) )
			) &&
			isset($photos) &&
			isset($address->city) &&
			isset($address->state) &&
			isset($address->zipcode) &&
			isset($address->address) &&
			isset($address->lat) &&
			isset($address->lng) &&
			isset($user->stripe_acc) &&
			$this->admin_lock == 0
		){
			return true;
		} 
		
		return false;
	}
	
	// Return why a listing can't be published
	public function whyCantPublish()
	{
		$photos = ListingImages::where('listing_id', '=', $this->id)->count();
		$address = ListingAddress::where('id', '=', $this->id)->first();
		$user = User::select('stripe_acc')->where('id', '=', $this->user_id)->first();
		
		$reasons = [];
		if(!isset($this->name)) $reasons[] = '<a class="custom-button listing h12" href="'.route('edit-listing-p1', ['id' => $this->id]).'">Give your listing a name</a>';
		if(!isset($this->property_type_id)) $reasons[] = '<a class="custom-button listing h12" href="'.route('edit-listing-p1', ['id' => $this->id]).'">What type of property are you listing?</a>';
		if(!isset($this->max_vehicle_length)) $reasons[] = '<a class="custom-button listing h12" href="'.route('edit-listing-p1', ['id' => $this->id]).'">What is the largest size of vehicle you can support?</a>';
		if(!isset($this->check_in)) $reasons[] = '<a class="custom-button listing h12" href="'.route('edit-listing-p3', ['id' => $this->id]).'">Add a check in time for travelers</a>';
		if(!isset($this->check_out)) $reasons[] = '<a class="custom-button listing h12" href="'.route('edit-listing-p3', ['id' => $this->id]).'">Add a check out time for travelers</a>';
		if( 
				!( $this->day_rental == 1 && isset($this->day_pricing) ) && 
				!( $this->month_rental == 1 && isset($this->month_pricing) )
			) $reasons[] = '<a class="custom-button listing h12" href="'.route('edit-listing-p4', ['id' => $this->id]).'">How much are you renting your property for?</a>';
		if($photos == 0) $reasons[] = '<a class="custom-button listing h12" href="'.route('edit-listing-p6', ['id' => $this->id]).'">Add some photos of your property!</a>';
		if(!isset($address->city) ||
			!isset($address->state) ||
			!isset($address->zipcode) ||
			!isset($address->address) ||
			!isset($address->lat) ||
			!isset($address->lng)) $reasons[] = '<a class="custom-button listing h12" href="'.route('edit-listing-p5', ['id' => $this->id]).'">Add the full address to your property</a>';
		if(!isset($user->stripe_acc)) $reasons[] = '<a class="custom-button payment h12" href="'.route('payment-dashboard').'">Complete the host onboarding to receive payments</a>';
		if($this->admin_lock) $reasons[] = '<a class="custom-button profile h12 disabled" href="">Your listing needs to be unlocked by an admin!</a>';
		
		$incomplete = count($reasons);
		$percentage = round(((10 - $incomplete)/10)*100);
		$reasons['percentage'] = $percentage;
		
		return $reasons;
	}
	
	// Static functions
    
    public static function checkListingOwner($listing_id, $user_id)
    {
        $listing = Listing::find($listing_id);
        
        return $listing->isListingOwner($user_id);
        
    }
    
    public static function checkListingBooking($listing_id, $start_date, $end_date)
    {
        $listing = Listing::find($listing_id);
        
        return $listing->hasBooking($start_date, $end_date);
    }
    
    public static function checkListingException($listing_id, $start_date, $end_date)
    {
        $listing = Listing::find($listing_id);
        
        return $listing->hasException($start_date, $end_date, NULL);
    }
	
	public static function checkOtherListingException($listing_id, $exception_id, $start_date, $end_date)
	{
		$listing = Listing::find($listing_id);
		
		return $listing->hasException($start_date, $end_date, $exception_id);
	}
	

    public function getRating()
	{
		return $this->calculateRating($this->id);
	}
	
	public function getTotalReviews()
	{
		return Review::where('listing_id', '=', $this->id)->count();
	}
	
	public static function calculateRating($id)
	{
		$reviews = Review::where('listing_id', '=', $id)->get();
		$total_points = 0;
		$iterator = 0;
		
			
		foreach($reviews as $review){
			
			$total_points += $review->stars;
			$iterator++;
		}
		
		if($iterator > 0 && $total_points > 0){
			
			return round($total_points / $iterator);
			
		}
		
		return 0;
	}
}