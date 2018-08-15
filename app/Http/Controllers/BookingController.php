<?php

namespace App\Http\Controllers;

/* Models */
use App\Models\Amenities;
use App\Models\Booking;
use App\Models\Favorite;
use App\Models\Language;
use App\Models\Listing;
use App\Models\ListingImages;
use App\Models\ListingException;
use App\Models\Message;
use App\Models\Review;
use App\Models\Transaction;
use App\User;

/* Requests */
use Illuminate\Http\Request;
use App\Http\Requests\ValidBooking;

/* Stripe */
use Stripe\Stripe as StripeBase;
use Stripe\Charge as StripeCharge;
use Stripe\Refund as StripeRefund;

/* Email */
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingConfirmation;
use App\Mail\BookingCancellationNotification;

/* Other */
use DB;
use Auth;
use Redirect;
use Entrust;

class BookingController extends Controller
{
	
	/**
    * Display the listing with the option to book.
    *
    * @param int $id
    * @return response
    */
	public function listing($id)
	{
        
        // Get all the info for the listings
		$listing = Listing::where('id', '=', $id)			
			->leftJoin(DB::raw("(select traveller_photo as host_url, name as host_name, host_description, id as host_id
							from `users`) as `host`"), 'host.host_id', '=', 'listings.user_id')
			->leftJoin(DB::raw("(select id as address_id, city, state, lat, lng
							from `listing_addresses`) as `address`"), 'address.address_id', '=', 'listings.id')
			->first();
        
		// Ensure the listing is published/active before we continue
		if(!$listing->published && !Entrust::can('view-unpublished') && Auth::user()->id != $listing->user_id){
			return view('errors.404');
		}
        
        $listing->rating = $listing->getRating();
		$listing->reviews = $listing->getTotalReviews();
		
		$listing->nearby_attractions = json_decode($listing->nearby_attractions);
		$listing->nearby_conveniences = json_decode($listing->nearby_conveniences);
		
		// Listing Images
		$listing->images = ListingImages::where('listing_id', '=', $id)
			->orderBy('primary')
			->get();
		
        // Amenities - If the # of amenities exceeds 100 this might need to be rewritten... 
		$amenities = Amenities::all();
		$listing->amenities = json_decode($listing->amenities);
		
		if(isset($listing->amenities)){
			foreach($amenities as $amenity){
				if(in_array($amenity->id, $listing->amenities)){
					$amenity->active = "yes";
				}
			}
		}
        
        // Grabbing the favorite
		if(isset(Auth::user()->id)){
			$listing->favorite = Favorite::where('listing_id', '=', $id)
							->where('user_id', '=', Auth::user()->id)
							->first();
		}
		
		// Grab the listing owner's other listings
		$other_listings = Listing::where('user_id', '=', $listing->user_id)
			  ->where('id', '!=', $listing->id)
			  ->leftJoin(DB::raw("(select city, state, id as listing_id 
							from `listing_addresses`) as `list_address`"), 'list_address.listing_id', '=', 'listings.id')
			  ->leftJoin(DB::raw("(select url, listing_id 
							from `listings_images` 
							where `primary` = 1) as `list_images`"), 'list_images.listing_id', '=', 'listings.id')
			->getPublished();
		
		$reviews = Review::where('listing_id', '=', $listing->id)
			->leftJoin('users', 'users.id', '=', 'reviews.user_id')
			->get();
		
		
        // Get the current exceptions
        $listingExceptions = ListingException::where('listing_id', '=', $id)->where('available', '=', 0)->get();
        
        // Get the current bookings
        $bookings = Booking::where('listing_id', '=', $id)->whereNull('canceled_at')->get();
        
        foreach($listingExceptions as $exception){
            if($exception->available){
                $exception->title = 'Special Price';
            } else {
                $exception->title = 'Not Available';
            }
            
        }
		
		return view('booking.listing')
			->with('listing', $listing)
			->with('amenities', $amenities)
			->with('other_listings', $other_listings)
			->with('reviews', $reviews)
			->with('bookings', $bookings)
			->with('listingExceptions', $listingExceptions);
	}
    
    
    /**
    * Start booking request with dates.
    *
    * @param App\Http\Requests\ValidBooking $request
    * @return response
    */
	public function start(ValidBooking $request)
	{
        // At this point, both dates have been validated as dates, that they are in the correct order (start date comes before end date) 
        // and the start date is at least 1 day out.
        
		// Validates the listing is available for the requested dates. 
		$listing = Listing::where('id', '=', $request->listing)->first();
		if(!$listing->isAvailable($request->checkin, $request->checkout)){

			$request->session()->flash('error', 'Whoops, looks like there is something blocking those dates from being reserved.');
			
			return Redirect::route('view-listing', ['id' => $request->listing]);
		}
		
		// And finally validating that the request does not violate any settings set by the host such as max stay length. 
		$days = round( (strtotime($request->checkout) - strtotime($request->checkin)) / (60 * 60 *24));
        
		if(isset($listing->max_stay_length) && $days > $listing->max_stay_length && $listing->max_stay_length != 0){
			
			$request->session()->flash('error', 'Whoops, this listing only allows a maximum stay of '.$listing->max_stay_length.' days.');
			
			return Redirect::route('view-listing', ['id' => $request->listing]);
		} 
		
        // Validation has all passed start the booking.
		$booking = new Booking();
		$booking->start_date = strtotime($request->checkin);
		$booking->end_date = strtotime($request->checkout);
		$booking->listing_id = $request->listing;
		$booking->save();
		
		return Redirect::route('listing-policies', ['booking' => $booking->id]);
	}
	
    
    /**
    * Display host policies for approval
    *
    * @param App\Models\Booking $booking
    * @return response
    */
	public function policy(Booking $booking)
	{
		
		$listing = Listing::find($booking->listing_id);
		$language = Language::getArray();

		$booking->calculateCost($listing->day_pricing, $listing->month_pricing, $listing->weeknight_discount);
		
		return view('booking.policies')
			->with('booking', $booking)
			->with('listing', $listing)
			->with('language', $language);
	}
	
    
    /**
    * Store confirmation of policy approval
    *
    * @param Illuminate\Http\Request $request
    * @return response
    */
	public function policyConfirm(Request $request)
	{
        
		$booking = Booking::find($request->booking);
		
		$booking->confirmed = 1;
		$booking->traveller_id = Auth::user()->id;
		$booking->save();
		
		return Redirect::route('pay-booking', ['booking' => $booking->id]);
	}
		
    
    /**
    * Page that offers the stripe form for payment.
    *
    * @param App\Models\Booking $booking
    * @return response
    */
	public function pay(Booking $booking)
	{
		//$booking = Booking::find($id);
		$listing = Listing::find($booking->listing_id);
		
		if($booking->traveller_id != Auth::user()->id){
			return view('errors.403');		
		}
		
		$booking->calculateCost($listing->day_pricing, $listing->month_pricing, $listing->weeknight_discount);
		
		return view('booking.pay')
			->with('booking', $booking)
			->with('listing', $listing);	
	}
    
    
	/**
    * Uses the Stripe API to handle the charge and set up the transaction records for the payment.
    *
    * @param Illuminate\Http\Request $request
    * @return response
    */
	public function finishPay(Request $request)
	{
		// Booking & Listing info, calculate the cost
		$booking = Booking::find($request->booking);
		$listing = Listing::find($booking->listing_id);
		
		$booking->calculateCost($listing->day_pricing, $listing->month_pricing, $listing->weeknight_discount);
        
        // Set up stripe credentials and make the charge
		StripeBase::setApiKey(config('services.stripe.secret'));
		$token = $request->stripeToken;
		
        $charge = StripeCharge::create([
			'amount' => ($booking->total * 100),
			'currency' => 'usd',
			'description' => $booking->id,
			'source' => $token,
			'transfer_group' => $booking->id,
		]);
        
        // Start a transaction for the booking
		$transaction = new Transaction();
		$transaction->booking_id = $booking->id;
		$transaction->charge_id = $charge->id; // Stripe charge id, needed for refunds
		$transaction->amount = $booking->total;
		$transaction->user_id = Auth::user()->id;
		$transaction->save();
		
        // This is unnecessary, need to check out if it's being used anywhere before removal though
		$booking->transaction_id = $transaction->id;
		$booking->save();
		
        // Send confirmation to the traveller & notification to the host
		Mail::to(Auth::user()->email)->send(new BookingConfirmation($listing, $booking, $transaction));
		Mail::to($listing->user_id)->send(new BookingNotification($listing, $booking));
			
		return Redirect::route('upcoming-trips');	
	}
	
    
	/**
    * Handle a cancel booking request
    *
    * @param Illuminate\Http\Request $request
    * @return response
    */
	public function cancelBooking(Request $request)
	{
        // Get all necessary info for handling the booking
		$booking = Booking::find($request->booking_id);
		$listing = Listing::find($booking->listing_id);
		
		if( $booking->cancelBooking() ){
			// Success
			if(isset($request->message)){
				
				  $message = new Message();
      
				  $message->message = $request->message;
				  $message->to = $listing->user_id;
				  $message->from = Auth::user()->id;
				  $message->save();

				  $message->thread = $message->id;
				  $message->save();
			}
			
            
		    $host = User::find($listing->user_id);
		    $traveller = User::find($booking->traveller_id);
            
			Mail::to($host->email)->send(new BookingCancellationNotification($booking, $listing));
			Mail::to($traveller->email)->send(new BookingCancellationNotification($booking, $listing));
            
		} else {
			// Fail :(
            
			$request->session()->flash('error', 'The request to cancel this booking has failed, if you think this is an error please contact upCLOSE-RV staff.');
            
		}
		
		return Redirect::route('upcoming-trips');
	}

    
	/**
    *  Returns a partial template on a page for import onto the marketing site's homepage.
    *
    *
    * @return response
    */
	public function featuredListings()
	{
		\Debugbar::disable();
		$listings = Listing::select('*')->addSelect('listings.id as listid')->leftJoin(DB::raw("(select id as address_id, city, state, lat, lng
							from `listing_addresses`) as `address`"), 'address.address_id', '=', 'listings.id')
							->leftJoin(DB::raw("(select * from `listings_images` where `primary` = 1) as `list_images`"), 'list_images.listing_id', '=', 'listings.id')
							->limit(6)
							->getPublished();
		
		foreach($listings as $listing){
			$listing->rating = $listing->getRating();
			$listing->reviews = $listing->getTotalReviews();
		}
		return view('ptemp.featured')->with('listings', $listings);

	}
	
}
