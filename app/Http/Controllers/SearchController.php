<?php

namespace App\Http\Controllers;

use App\Models\Amenities;
use App\Models\Listing;
use App\Models\ListingAddress;
use App\Models\ListingImages;
use App\Models\Review;
use App\Models\RVTypes;
use App\Models\Favorite;
use Illuminate\Http\Request;
use DB;
use Auth;

class SearchController extends Controller
{
    public function index()
    {
        // Results
    }
    
	/**
    * Handle the search request with as FEW queries to the DB as possible. 
	* Strap in, this logic is probably going to get a little crazy.
	* 
    * TODO:
	* - Vehicle Type
	* - Amenities
    *
    * @param Request request
    * @return response
    */
    public function search(Request $request)
    {
		
    $location = $this::geocode($request->search);
		$rvTypes = RVTypes::all();
		$amenities = Amenities::all();
		
		// Lets start this sucka.
		$query = ListingAddress::select('*')
			->addSelect('listings.id as listid')
            ->leftJoin('listings', 'listing_addresses.id', '=', 'listings.id')
			->leftJoin(DB::raw("(select * 
							from `listings_images` 
							where `primary` = 1 LIMIT 1) as `list_images`"), 'list_images.listing_id', '=', 'listings.id');
		
		// Do we need to filter by arrival & departure?
		if(isset($request->arrival) && isset($request->departure)) {
			$query = $query->isAvailable($request->arrival, $request->departure);
		}
		
		// filter by price & rental type
		if(isset($request->price)) {
			if(isset($request->rentalType) && $request->rentalType == 2){
				// They want monthly pricing
				$query = $query->where('month_rental', '=', 1)
							->where('month_pricing', '<=', $request->price);
			} else {
				// Daily pricing
				$query = $query->where('day_rental', '=', 1)
							->where('day_pricing', '<=', $request->price);
			}
		} else {
			// Pricing isn't set, but rental type might be
			if(isset($request->rentalType) && $request->rentalType == 2){
				$query = $query->where('month_rental', '=', 1);
			} elseif(isset($request->rentalType) && $request->rentalType == 1){
				$query = $query->where('day_rental', '=', 1);
			}
		
		}
		
		// Filter by lot type
		if(isset($request->lotType) && $request->lotType != ''){
			$query = $query->where('property_type_id', '=', $request->lotType);
		}
		
		// Filter by amenities
		if(isset($request->amenities)){
			$query = $query->where('amenities', 'like', '%'.$request->amenities.'%');
		}
		
		// Filter by RV types
		if(isset($request->rvTypes)){
			$query = $query->where('rv_types', 'like', '%'.$request->rvTypes.'%');
		}
		
		// Filter by location
		$query = $query->closeTo($location['lat'], $location['lng']);
		
		// Go get it... 
		$listings = $query->getPublished();
		/*
		echo '<pre>';
		var_dump($listings);
		echo '</pre>';
		*/
		return view('search.index')
			->with('request', $request)
			->with('listings', $listings)
			->with('location', $location)
			->with('rvTypes', $rvTypes)
			->with('amenities', $amenities);
    }
    
    
    public static function geocode($query)
    {
        $url = 'https://maps.googleapis.com/maps/api/geocode/json?address='.urlencode($query).'&key=AIzaSyCyXHeiC9HRgVmhWkHPyBaM4bM7FC3TuGw';
        $data = file_get_contents($url);
        $loc = json_decode($data);
        $location['lat'] = $loc->results['0']->geometry->location->lat;
        $location['lng'] = $loc->results['0']->geometry->location->lng;
        
        return $location;
    }
	
	/**
    * Display the listing with the option to book.
	* 
    *
    * @return response
    */
	public function listing($id)
	{
		$listing = Listing::where('id', '=', $id)			
			->leftJoin(DB::raw("(select traveller_photo as host_url, name as host_name, host_description, id as host_id
							from `users` LIMIT 1) as `host`"), 'host.host_id', '=', 'listings.user_id')
			->leftJoin(DB::raw("(select id as address_id, city, state, lat, lng
							from `listing_addresses` LIMIT 1) as `address`"), 'address.address_id', '=', 'listings.id')
			->first();
		$listing->rating = $listing->getRating();
		$listing->reviews = $listing->getTotalReviews();
		
		// Ensure the listing is published/active.
		if(!$listing->published){
			return view('errors.404');
		}
		
		$listing->images = ListingImages::where('listing_id', '=', $id)
			->orderBy('primary')
			->get();
		
		$amenities = Amenities::all();
		$listing->amenities = json_decode($listing->amenities);
		
		if(isset($listing->amenities)){
			foreach($amenities as $amenity){
				if(in_array($amenity->id, $listing->amenities)){
					$amenity->active = "yes";
				}
			}
		}
		if(isset(Auth::user()->id)){
			$listing->favorite = Favorite::where('listing_id', '=', $id)
							->where('user_id', '=', Auth::user()->id)
							->first();
		}
		
		// Grab the listing owner's other listings
		$other_listings = Listing::where('user_id', '=', $listing->user_id)
			  ->where('id', '!=', $listing->id)
			  ->leftJoin(DB::raw("(select city, state, id as listing_id 
							from `listing_addresses` LIMIT 1) as `list_address`"), 'list_address.listing_id', '=', 'listings.id')
			  ->leftJoin(DB::raw("(select url, listing_id 
							from `listings_images` 
							where `primary` = 1 LIMIT 1) as `list_images`"), 'list_images.listing_id', '=', 'listings.id')
			->getPublished();
		
		$reviews = Review::where('listing_id', '=', $listing->id)
			->leftJoin('users', 'users.id', '=', 'reviews.user_id')
			->get();
		
		return view('booking.listing')
			->with('listing', $listing)
			->with('amenities', $amenities)
			->with('other_listings', $other_listings);
	}
	
}
