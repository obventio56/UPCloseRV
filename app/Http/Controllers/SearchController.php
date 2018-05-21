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
use Entrust;

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
							where `primary` = 1) as `list_images`"), 'list_images.listing_id', '=', 'listings.id')
			->leftJoin(DB::raw("(select listing_id, COUNT(id) as total_reviews, SUM(stars) as total_stars 
			from reviews GROUP BY listing_id) as review_totals"), 'review_totals.listing_id', '=', 'listings.id');
		
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
		
		// Make any modifications necessary before sending off to the view
		foreach($listings as $listing){
			if($listing->total_reviews == 0){
				$listing->stars = 0;
			}  else {
				$listing->stars = round($listing->total_stars/$listing->total_reviews);
			}
		}
    foreach($listings as $listing){
      if (!is_null($listing->amenities)){
        $listing->amenityList = implode(', ', $amenities->find((array_map('intval', json_decode($listing->amenities))))->pluck('name')->toArray());
      } else {
        $listing->amenityList = '';
      }
    }
      
		
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
	
	
}
