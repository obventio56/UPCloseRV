<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
use DB;

class ListingAddress extends Model
{

    
    protected $table = 'listing_addresses';
    
    private $geocodingKey = 'AIzaSyCyXHeiC9HRgVmhWkHPyBaM4bM7FC3TuGw'; // Move this to .ENV
    
	// To pull only published listings.
	public function scopeGetPublished($q){
		return $q->where('listings.published', '=', '1')->get();
	}
    
    public function geocodeAddress()
    {
        $address = $this->address.' '.$this->city.' '.$this->state.' '.$this->zipcode;
        $url = 'https://maps.googleapis.com/maps/api/geocode/json?address='.urlencode($address).'&key='.$this->geocodingKey;
        $data = file_get_contents($url);
        $loc = json_decode($data);
		if(isset($loc->results['0']->geometry->location->lat) && isset($loc->results['0']->geometry->location->lng)){
			$this->lat = $loc->results['0']->geometry->location->lat;
			$this->lng = $loc->results['0']->geometry->location->lng;
			$this->save();
			return true;
		} else {
			return false;
		}
    }
    
    
    public function scopeCloseTo(Builder $query, $lat, $lng)
    {
        return $query->whereRaw("
        ST_Distance_Sphere(
        point(lng, lat),
        point(?, ?)
        ) * .000621371192 < 50
        ", [
          $lng, 
          $lat,
        ]);
    }
	
	public function scopeIsAvailable(Builder $query, $startDate, $endDate)
	{
		
		$startDate = new Carbon($startDate);
		$endDate = new Carbon($endDate);
		
		// This ONLY checks exceptions... 
		$query = $query->leftJoin(
							DB::raw("(select `listing_id`, `id` as `listing_exception_id` 
							from `listing_exceptions` 
							where (`start_date` >= ? and `start_date` <= ? ) or 
							(`end_date` > ? and `end_date` <= ? ) and 
							`listing_exceptions`.`listing_id` = 'listid' and `available` = '0' LIMIT 1) as `listings_exceptions`"),
		'listings_exceptions.listing_id', '=', 'listings.id')
			->whereNull('listing_exception_id')
			->addBinding($startDate)
			->addBinding($endDate)
			->addBinding($startDate)
			->addBinding($endDate);
		
		// Now do it again for bookings... 
		$query = $query->leftJoin(
							DB::raw("(select `listing_id`, `id` as `listing_booking_id` 
							from `bookings` 
							where (`start_date` >= ? and `start_date` <= ? ) or 
							(`end_date` > ? and `end_date` <= ? ) and 
							`bookings`.`listing_id` = 'listid' LIMIT 1) as `listing_bookings`"),
							'listing_bookings.listing_id', '=', 'listings.id')
			->whereNull('listing_booking_id')
			->addBinding($startDate)
			->addBinding($endDate)
			->addBinding($startDate)
			->addBinding($endDate);
		
		return $query;
		/*function($q) use ($startDate, $endDate){
				$q->from('listing_exceptions')
				  ->where( function($q) use ($startDate, $endDate) {

					$q->where('start_date', '>=', $startDate)
					  ->where('start_date', '<=', $endDate);
				})
				->orWhere( function($q) use ($startDate, $endDate) {
					$q->where('end_date', '>', $startDate)
					  ->where('end_date', '<=', $endDate);
				})
				->where('listing_exceptions.listing_id', '=', 'listid')
				->where('available', '=', '0');*/ // Didn't want to lose all this... so much mind melting work...
		
	}
    
}
