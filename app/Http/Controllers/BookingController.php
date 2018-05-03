<?php

namespace App\Http\Controllers;

use App\Models\Amenities;
use App\Models\Booking;
use App\Models\Language;
use App\Models\Listing;
use App\Models\ListingImages;
use Illuminate\Http\Request;
use Stripe\Stripe as StripeBase;
use Stripe\Charge as StripeCharge;

use DB;
use Redirect;

class BookingController extends Controller
{
    public function index()
    {
        // Results
    }
    
	public function start(Request $request)
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
		
		
		return view('booking.policies')
			->with('booking', $booking)
			->with('listing', $listing)
			->with('language', $language);
	}
	
	public function pay()
	{
		return view('booking.pay');	
	}
	
	public function finishPay(Request $request)
	{
		StripeBase::setApiKey(config('services.stripe.secret'));
		$token = $request->stripeToken;
		
		$charge = StripeCharge::create([
			'amount' => 999,
			'currency' => 'usd',
			'description' => 'Example charge - will be booking id',
			'source' => $token,
		]);
		return view('home');	
	}

}
