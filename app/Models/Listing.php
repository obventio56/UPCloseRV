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
	
	
	// Functions

	// To pull only published listings.
	public function scopeGetPublished($q){
		return $q->where('listings.published', '=', '1')->get();
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

					$q->where('start_date', '>=', $startDate)
					  ->where('start_date', '<=', $endDate);
				})
				->orWhere( function($q) use ($startDate, $endDate) {
					$q->where('end_date', '>', $startDate)
					  ->where('end_date', '<=', $endDate);
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

                $q->where('start_date', '>=', $startDate)
                  ->where('start_date', '<=', $endDate);
            })
            ->orWhere( function($q) use ($startDate, $endDate) {
                $q->where('end_date', '>', $startDate)
                  ->where('end_date', '<=', $endDate);
            })
            ->where('listing_id', '=', $this->id)
            ->first();
        
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
			isset($user->stripe_acc)
		){
			return true;
		} 
		
		return false;
	}
	
	// Return why a listing can't be published
	public function whyCantPublish()
	{
		$photos = ListingImages::where('listing_id', '=', $this->id)->get();
		$address = ListingAddress::where('id', '=', $this->id)->first();
		$user = User::select('stripe_acc')->where('id', '=', $this->user_id)->first();
		
		$reasons = 'Your listing is missing... ';
		if(!isset($this->name)) $reasons .= '<br/> -name';
		if(!isset($this->property_type_id)) $reasons .= '<br/> -property type';
		if(!isset($this->max_vehicle_length)) $reasons .= '<br/> -max vehicle length';
		if(!isset($this->check_in)) $reasons .= '<br/> -checkin time';
		if(!isset($this->check_out)) $reasons .= '<br/> -checkout time';
		if( 
				!( $this->day_rental == 1 && isset($this->day_pricing) ) && 
				!( $this->month_rental == 1 && isset($this->month_pricing) )
			) $reasons .= '<br/> -pricing or rental type';
		if(!isset($photos)) $reasons .= '<br/> -photos';
		if(!isset($address->city) ||
			!isset($address->state) ||
			!isset($address->zipcode) ||
			!isset($address->address) ||
			!isset($address->lat) ||
			!isset($address->lng)) $reasons .= '<br/> -valid address';
		if(!isset($user->stripe_acc)) $reasons .= '<br/> -host verification';
		
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
	

    
}