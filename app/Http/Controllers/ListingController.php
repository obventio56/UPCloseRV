<?php

namespace App\Http\Controllers;

//use Illuminate\Http\Request;

use App\Http\Requests\ValidListing;
use App\Http\Requests\ValidListingP1;
use App\Http\Requests\ValidListingP2;
use App\Http\Requests\ValidListingP3;
use App\Http\Requests\ValidListingP4;
use App\Http\Requests\ValidListingP5;
use App\Http\Requests\ValidListingP6;
use App\Http\Requests\ValidListingP7;
use App\Models\Listing;
use App\Models\ListingAddress;
use App\Models\ListingImages;
use App\Models\ListingException;
use App\Models\Booking;
use App\Models\Review;
use App\Models\RVTypes;
use App\Models\Amenities;
use Cloudder;
use Illuminate\Http\Request;
use Auth;
use DB;
use Redirect;

class ListingController extends Controller
{
    /**
    * Manages everything about the listings from the dashboard
    *
    * TODO:
    * - Move to Dashboard\ListingController
    */
    

    /**
    * Ensure logged in user
    */
    public function __construct()
    {
      $this->middleware('auth');
    }
  
    /**
    * Get all the user's listings
    *
    * @return response
    */
    public function index()
    {
		// Ensure we're only pulling the logged in user's listings
          $listings = Listing::where('user_id', '=', Auth::user()->id)
			  ->leftJoin(DB::raw("(select city, state, id as listing_id 
							from `listing_addresses`) as `list_address`"), 'list_address.listing_id', '=', 'listings.id')
			  ->leftJoin(DB::raw("(select url, listing_id 
							from `listings_images` 
							where `primary` = 1) as `list_images`"), 'list_images.listing_id', '=', 'listings.id')
			  ->leftJoin(DB::raw("(select listing_id, COUNT(id) as total_reviews, SUM(stars) as total_stars from reviews GROUP BY listing_id) as review_totals"), 'review_totals.listing_id', '=', 'listings.id')
			  ->get();
		
		$listings->count = Listing::where('user_id', '=', Auth::user()->id)->count('id');
		
		foreach($listings as $listing){
			if($listing->total_reviews == 0){
				$listing->stars = 0;
			} else {
				$listings->stars = round($listing->total_stars/$listing->total_reviews);
			}
		}

		
        return view('dashboard.listings.index')->with('listings', $listings);
    }

    /**
    * Display all of the management options for a single listing
    *
    * @param int $id
    * @return response
    */
    public function manage($id)
    {
		
		$listing = Listing::where('user_id', '=', Auth::user()->id)
			->where('id', '=', $id)
			->first();

		if($listing->user_id != Auth::user()->id && !Auth::user()->ability('admin, client', 'edit-other-listings')){
			return view('errors.403');
		}

        return view('dashboard.listings.manage')
            ->with('listing', $listing);
    }

  
  
  // The pages with the forms for each section. 
  // Adding Section 1: Basic Info
  public function addListing()
  {
	 
	  $rvtypes = RVTypes::all();
      return view('dashboard.listings.add')->with('rvtypes', $rvtypes);
  }

  // Editing Section 1: Basic Info
  public function editListing(Listing $listing)
  {
	  if($listing->user_id != Auth::user()->id && !Auth::user()->ability('admin, client', 'edit-other-listings')){
		  return view('errors.403');
	  }
	  
	  $rvtypes = RVTypes::all();
	  if(isset($listing->rv_types)){
		$listing->rv_types = json_decode($listing->rv_types);
	  } 
    
      return view('dashboard.listings.page1')->with('listing', $listing)->with('rvtypes', $rvtypes);
  }

  // Editing Section 2: Amenities
  public function editPage2(Listing $listing)
  {
    if($listing->user_id != Auth::user()->id && !Auth::user()->ability('admin, client', 'edit-other-listings')){
		  return view('errors.403');
	}
	  
	$amenities = Amenities::all();
	if(isset($listing->amenities)){
		$listing->amenities = json_decode($listing->amenities);
  	}
    
    return view('dashboard.listings.page2')->with('listing', $listing)->with('amenities', $amenities);
  }

  // Editing Section 3: Rules & policies
  public function editPage3(Listing $listing)
  {
    if($listing->user_id != Auth::user()->id && !Auth::user()->ability('admin, client', 'edit-other-listings')){
		return view('errors.403');
	}
    
    return view('dashboard.listings.page3')->with('listing', $listing);
  }

  // Editing Section 4: Pricing
  public function editPage4(Listing $listing)
  {
    if($listing->user_id != Auth::user()->id && !Auth::user()->ability('admin, client', 'edit-other-listings')){
		return view('errors.403');
	}
    
    return view('dashboard.listings.page4')->with('listing', $listing);
  }
  
    /**
    * Return Address & Directions listing page
    *
    * @param int $id
    * @return response
    */
    public function editPage5(Listing $listing)
    {
        if($listing->user_id != Auth::user()->id && !Auth::user()->ability('admin, client', 'edit-other-listings')){
			return view('errors.403');
		}
		
        $listingAddress = ListingAddress::find($listing->id);

        return view('dashboard.listings.page5')
            ->with('listing', $listing)
            ->with('listingAddress', $listingAddress);
    }
  
  // Editing Section 6: Photos/Media
  public function editPage6(Listing $listing)
  {
	if($listing->user_id != Auth::user()->id && !Auth::user()->ability('admin, client', 'edit-other-listings')){
		return view('errors.403');
	}
      
      $images = ListingImages::where('listing_id', '=', $listing->id)->get();
    
      return view('dashboard.listings.page6')
		  ->with('listing', $listing)
		  ->with('images', $images); 
  }
  
  // Editing Section 7: Nearby Stuffs
	public function editPage7(Listing $listing)
	{
	if($listing->user_id != Auth::user()->id && !Auth::user()->ability('admin, client', 'edit-other-listings')){
			return view('errors.403');
		}

		$attractions = json_decode($listing->nearby_attractions);
		$conveniences = json_decode($listing->nearby_conveniences);

		return view('dashboard.listings.page7')
			->with('listing', $listing)
			->with('attractions', $attractions)
			->with('conveniences', $conveniences);
	}
  
  // aaaand here comes the storage functions...
  // Store the initial listing. 
  public function createListing(ValidListing $request)
  {
    $listing = new Listing();
    
    $listing->name = $request->name;
    $listing->property_type_id = $request->propertyType;
    $listing->host_type_id = $request->hostType;
	
	$listing->rv_types = json_encode($request->rvTypes);
    
	$listing->max_vehicle_length = $request->vehicleLength;
    $listing->description = $request->description;
    
	$listing->user_id = Auth::user()->id;
	  
    $listing->save();
    
    $id = $listing->id;
	
	return Redirect::route('edit-listing-p2', [$id]);
    
  }
	
	/**
    * Save basic info about the listing
    * TODO:
    * - Confirm listing ownership x.x
    *
    * @param Request request
    * @return response
    */
	public function storePage1(ValidListingP1 $request)
	{
		$listing = Listing::find($request->id);
		
		$listing->name = $request->name;
		$listing->property_type_id = $request->propertyType;
		$listing->host_type_id = $request->hostType;
		
		$listing->rv_types = json_encode($request->rvTypes);
		
		$listing->max_vehicle_length = $request->vehicleLength;
		$listing->description = $request->description;

		$listing->save();
		
		return Redirect::route('edit-listing-p1', [$request->id]);
	}
	
	/**
    * Save amenities
    * TODO:
    * - Confirm listing ownership x.x
    *
    * @param Request request
    * @return response
    */
	public function storePage2(ValidListingP2 $request)
	{
		$listing = Listing::find($request->id);
		
		$listing->outdoor_property_type_id = $request->propertyType;
		
		$listing->amenities = json_encode($request->amenities);
		$listing->other_amenities = $request->otherAmenities;
		
		$listing->electric_hookup = $request->electricHookup;
		
		$listing->sewer_hookup = $request->sewerHookup;
		$listing->water_hookup = $request->waterHookup;

		$listing->save();
		
		return Redirect::route('edit-listing-p2', [$request->id]);
	}
	
	/**
    * Save rules & policies
    * TODO:
    * - Confirm listing ownership x.x
    *
    * @param Request request
    * @return response
    */
	public function storePage3(ValidListingP3 $request)
	{
		$listing = Listing::find($request->id);
		
		$listing->rules = $request->rules;
		
		$listing->check_in = $request->checkin;
		$listing->check_out = $request->checkout;
		
		$listing->checkin_rules = $request->cirules;
		
		$listing->pets_allowed = $request->pets;
		$listing->cancel_policy = $request->cancelPolicy;

		$listing->save();
		
		return Redirect::route('edit-listing-p3', [$request->id]);
	}
	
	/**
    * Save pricing info about the listing
    * TODO:
    *
    * @param Request request
    * @return response
    */
	public function storePage4(ValidListingP4 $request)
	{
		$listing = Listing::find($request->id);
		
		$listing->max_stay_length = $request->maxStayLength;
		
		$listing->day_rental = (isset($request->dayRental) ? 1 : 0);
		$listing->day_pricing = $request->dayPricing;
		$listing->day_guests = $request->dayGuests;
		
		$listing->month_rental = (isset($request->monthRental) ? 1 : 0);
		$listing->month_pricing = $request->monthPricing;
		$listing->month_guests = $request->monthGuests;
		
		$listing->weeknight_discount = $request->weeknightDiscount;

		$listing->save();
		
		return Redirect::route('edit-listing-p4', [$request->id]);
	}
    
    /**
    * Geocode address and store address & directions listing page
    * TODO:
    *
    * @param Request request
    * @return response
    */
    public function storePage5(ValidListingP5 $request)
	{
        // Grab the listing
        $listing = Listing::find($request->id);
        
        // Update instructions/directions
        $listing->instruct_find = $request->drections;
        $listing->instruct_parking = $request->parkingDirections;
        $listing->save();
        
        // Address time
        $listingAddress = ListingAddress::where('id', $request->id)->first();
        if(!$listingAddress){
            $listingAddress = new ListingAddress();
            $listingAddress->id = $request->id;
        }
        
        $listingAddress->address = $request->address;
        $listingAddress->city = $request->city;
        $listingAddress->state = $request->state;
        $listingAddress->zipcode = $request->zip;
        $listingAddress->save();
        
        if(!$listingAddress->geocodeAddress()){
			$request->session()->flash('error', 'The address you provided was invalid, please check everything and try again.');
		}
        
        return Redirect::route('edit-listing-p5', [$request->id]);
    }
    
    // Storing Section 6: Photos
    public function storePage6(ValidListingP6 $request){
        \Debugbar::disable();
        $file_url = "http://yourdomain/defaultimage.png";
        if ($request->hasFile('file') && $request->file('file')->isValid()){
            $cloudder = Cloudder::upload($request->file->path());
            $uploadResult = $cloudder->getResult();
            $file_url = $uploadResult["url"];
            
			$listing = ListingImages::where('listing_id', '=', $request->id)->where('primary', '=', 1);
            $image = new ListingImages();
            $image->listing_id = $request->id;
            $image->url = $file_url;
			if($listing->count() == 0){
				$image->primary = 1;
			}
            $image->save();
        }
		
        return response()->json(['file_url' => $file_url], 200);
    }
	
	
	public function makePrimaryImage(Request $request)
	{
		$images = ListingImages::where('listing_id', $request->listing_id)->get();
		
		foreach($images as $image){
			if($image->id == $request->id){
				$image->primary = 1;
			} else {
				$image->primary = 0;
			}
			$image->save();
		}
		
		return Redirect::route('edit-listing-p6', [$request->listing_id]);
	}
	
	/**
    * Geocode address and store address & directions listing page
    * TODO:
    * - Push the delete to Cloudinary
    *
    * @param Request request
    * @return response
    */
	public function removeImage(Request $request){
		$image = ListingImages::find($request->id);
		
		$image->delete();
		
		return Redirect::route('edit-listing-p6', [$request->listing_id]);
	}
	
	
	/**
    * Store what's nearby
    * TODO:
    * - Confirm listing ownership x.x
    *
    * @param Request request
    * @return response
    */
    public function storePage7(ValidListingP7 $request)
	{
        // Grab the listing
        $listing = Listing::find($request->id);
        
        // Attractions
		$attractions;
		$i = 0;
		foreach($request->nearby as $whocares){
			
			$attractions[$i]['attraction'] = $request->nearby[$i];
			$attractions[$i]['location'] = $request->location[$i];
			
			$i++;
		}
		
		$conveniences;
		$i = 0;
		foreach($request->nearby2 as $whocares){
			
			$conveniences[$i]['attraction'] = $request->nearby2[$i];
			$conveniences[$i]['location'] = $request->location2[$i];
			
			$i++;
		}
		
		
        $listing->nearby_attractions = json_encode($attractions);
        $listing->nearby_conveniences = json_encode($conveniences);
        $listing->save();
        
        return Redirect::route('edit-listing-p7', [$request->id])
			->with('attractions', $request);
    }
	
    
    
    // Listing Calendar Availability
    // TODO: 
    // -Grab bookings
    public function availability($id)
    {
        
        $listing = Listing::find($id);
        // Get the current exceptions
		if($listing->user_id != Auth::user()->id){
			return view('errors.403');
		}

        $listingExceptions = ListingException::where('listing_id', '=', $id)->get();
        // Get the current bookings
        $bookings = Booking::where('listing_id', '=', $id)
			->whereNotNull('transaction_id')
			->get();
        
        foreach($listingExceptions as $exception){
            if($exception->available){
                $exception->title = 'Special Price';
            } else {
                $exception->title = 'Not Available';
            }
            
        }
        
        return view('dashboard.listings.availability')
            ->with('listing', $listing)
            ->with('listingExceptions', $listingExceptions)
            ->with('bookings', $bookings);
    }
    
    
    /**
    * Add a listing condition/exception
    *
    * @param Request request
    * @return response
    */
    public function addException(Request $request)
    {
        // Check for an existing exception. We don't want more than one exception at once.
        $listing = Listing::find($request->id);

		// Ensure proper auth
		if($listing->user_id != Auth::user()->id){
			return view('errors.403');
		}

        if(!$listing->hasException($request->startDate, $request->endDate, NULL) 
           && !$listing->hasBooking($request->startDate, $request->endDate))
        {
            $listingException = new ListingException();
            $listingException->listing_id = $request->id;
            $listingException->start_date = $request->startDate;
            $listingException->end_date = $request->endDate;
            if($request->condition == 'not-available'){
                $listingException->special_price = 0;
                $listingException->available = 0;
            } else {
                $listingException->price = $request->price;
            }
        
            $listingException->save();
			
        	$request->session()->flash('success', 'Exception added successfully');
        } else {
            // Already an exception. NO MORE EXCEPTIONS. Toast it :D
			 $request->session()->flash('error', 'There is a booking or another exception overlapping this exception. The booking must be cancelled before policy changes can be made.');
        }
		
        return Redirect::route('listing-availability', [$request->id]);
    }
    
     /**
    * Modify a listing exception/condition
    *
    * @param Request request
    * @return response
    */
    public function editException(Request $request)
    {
        // Grab the exception we're looking to remove
        $exception = ListingException::find($request->id);
        
        // Check the listing ownership
        if(Listing::checkListingOwner($exception->listing_id, Auth::user()->id)) {
            // Ensure a booking isn't overlapping with this exception
            if(!Listing::checkListingBooking($exception->listing_id, $exception->start_date, $exception->end_date)
			  && !Listing::checkListingBooking($exception->listing_id,  $request->startDateEdit, $request->endDateEdit)
              && !Listing::checkOtherListingException($exception->listing_id, $request->id, $request->startDateEdit, $request->endDateEdit)) {
                // No booking, we're clear to modify
				
                $exception->start_date = $request->startDateEdit;
                $exception->end_date = $request->endDateEdit;
				
                if(!$exception->available){
                    $exception->special_price = 0;
                    $exception->available = 0;
                } else {
                    $exception->special_price = 1;
                    $exception->available = 1;
                    $exception->price = $request->price;
                }
                
                $exception->save();
                
            } else {
                // There's a booking, so this exception cannot be modified 
                 $request->session()->flash('error', 'There is a booking overlapping this exception. The booking must be cancelled before policy changes can be made.');
            }
            
        } else {
            // You're not the owner of that listing!!
            return view('errors.403');
        }
        
		
        $request->session()->flash('success', 'Exception updated successfully');
        return Redirect::route('listing-availability', [$exception->listing_id]);
            
    }
    
    /**
    * Remove a listing condition/exception
    *
    * @param Request request
    * @return response
    */
    public function removeException(Request $request)
    {
        // Grab the exception we're looking to remove
        $exception = ListingException::find($request->id);
        
        // Check the listing ownership
        if(Listing::checkListingOwner($exception->listing_id, Auth::user()->id)) {
            // Ensure a booking isn't overlapping with this exception
            if(!Listing::checkListingBooking($exception->listing_id, $exception->start_date, $exception->end_date)) {
                // No booking, we're clear to remove
                $exception->delete();
            } else {
                // There's a booking, so this exception cannot be removed 
                $request->session()->flash('error', 'There is a booking overlapping this exception. The booking must be cancelled before policy changes can be made.');
            }
            
        } else {
            // You're not the owner of that listing!!
            return view('errors.403');
        }
        $request->session()->flash('success', 'Exception removed successfully');
        return Redirect::route('listing-availability', [$exception->listing_id]);
            
    }
	
	// Publish Listing
	public function publishListing(Request $request)
	{
		
		$listing = Listing::find($request->id);
		// Ensure proper auth
		if($listing->user_id != Auth::user()->id){
			return view('errors.403');
		}
		
		$listing->published = 1;
		$listing->save();
		
		return Redirect::route('manage-listing', [$request->id]);
	}
	
	
	// Unpublish Listing
	public function unpublishListing(Request $request)
	{
		$listing = Listing::find($request->id);
		// Ensure proper auth
		if($listing->user_id != Auth::user()->id){
			return view('errors.403');
		}
		$listing->published = 0;
		$listing->save();
		
		return Redirect::route('manage-listing', [$request->id]);
	}
	
	
	// Manage reservations
	public function manageReservations($id)
	{
		$listing = Listing::find($id);
		
		if($listing->user_id != Auth::user()->id){
		  return view('errors.403');
	  	}
		
		$bookings = Booking::select('*')
			->addSelect('bookings.id as booking_id')
			->where('listing_id', '=', $id)
			->whereNotNull('transaction_id')
			->leftJoin('transaction', 'transaction.id', '=', 'bookings.transaction_id')
			->leftJoin('users', 'users.id', '=', 'bookings.traveller_id')
			->get();
		
		
		
		return view('dashboard.listings.reservations')
			->with('listing', $listing)
			->with('bookings', $bookings);
	}
}
