<?php

namespace App\Http\Controllers;

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
use Illuminate\Http\Request;
use App\Http\Requests\ValidBooking;


use Stripe\Stripe as StripeBase;
use Stripe\Charge as StripeCharge;
use Stripe\Refund as StripeRefund;

use DB;
use Auth;
use Redirect;
use Entrust;

class BookingController extends Controller
{
	
	/**
    * Display the listing with the option to book.
    *
    * @return response
    */
	public function listing($id)
	{
		$listing = Listing::where('id', '=', $id)			
			->leftJoin(DB::raw("(select traveller_photo as host_url, name as host_name, host_description, id as host_id
							from `users`) as `host`"), 'host.host_id', '=', 'listings.user_id')
			->leftJoin(DB::raw("(select id as address_id, city, state, lat, lng
							from `listing_addresses`) as `address`"), 'address.address_id', '=', 'listings.id')
			->first();
		$listing->rating = $listing->getRating();
		$listing->reviews = $listing->getTotalReviews();
		
		$listing->nearby_attractions = json_decode($listing->nearby_attractions);
		$listing->nearby_conveniences = json_decode($listing->nearby_conveniences);
		
		// Ensure the listing is published/active.
		if(!$listing->published && !Entrust::can('view-unpublished')){
			return view('errors.404');
		}
		
		// Listing Images
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
        $bookings = Booking::where('listing_id', '=', $id)->get();
        
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
    
	public function start(ValidBooking $request)
	{
		$booking = new Booking();
		$booking->start_date = strtotime($request->checkin);
		$booking->end_date = strtotime($request->checkout);
		$booking->listing_id = $request->listing;
		$booking->save();
		
		return Redirect::route('listing-policies', ['id' => $booking->id]);
	}
	
	public function policy($id)
	{
		
		
		$booking = Booking::find($id);
		
		$listing = Listing::find($booking->listing_id);
		$language = Language::getArray();
		
		
		$booking->calculateCost($listing->day_pricing, $listing->month_pricing, $listing->weeknight_discount);
		
		return view('booking.policies')
			->with('booking', $booking)
			->with('listing', $listing)
			->with('language', $language);
	}
	
	public function policyConfirm(Request $request)
	{
		$booking = Booking::find($request->booking);
		
		$booking->confirmed = 1;
		$booking->traveller_id = Auth::user()->id;
		$booking->save();
		
		return Redirect::route('pay-booking', ['id' => $booking->id]);
	}
	
	public function pay($id)
	{
		$booking = Booking::find($id);
		$listing = Listing::find($booking->listing_id);
		
		if($booking->traveller_id != Auth::user()->id){
			return view('errors.403');		
		}
		
		$booking->calculateCost($listing->day_pricing, $listing->month_pricing, $listing->weeknight_discount);
		
		return view('booking.pay')
			->with('booking', $booking)
			->with('listing', $listing);	
	}
	
	public function finishPay(Request $request)
	{
		StripeBase::setApiKey(config('services.stripe.secret'));
		$token = $request->stripeToken;
		
		$booking = Booking::find($request->booking);
		$listing = Listing::find($booking->listing_id);
		
		$booking->calculateCost($listing->day_pricing, $listing->month_pricing, $listing->weeknight_discount);
		
		$transaction = new Transaction();
		
		$transaction->booking_id = $booking->id;
		
		$charge = StripeCharge::create([
			'amount' => ($booking->total * 100),
			'currency' => 'usd',
			'description' => $booking->id,
			'source' => $token,
		]);
		
		$transaction->charge_id = $charge->id;
		$transaction->amount = $booking->total;
		$transaction->user_id = Auth::user()->id;
		$transaction->save();
		
		$booking->transaction_id = $transaction->id;
		$booking->save();
			
		return Redirect::route('upcoming-trips');	
	}
	
	
	public function cancelBooking(Request $request)
	{
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
		} else {
			// Fail :(
		}
		
		return Redirect::route('upcoming-trips');
	}


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
