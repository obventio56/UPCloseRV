<?php

namespace App\Http\Controllers;


use App\User;
use App\Models\RVTypes;
use App\Models\Booking;
use App\Models\Listing;
use App\Models\ListingImages;
use App\Models\Amenities;
use App\Models\Review;

// Stripe
use Rap2hpoutre\LaravelStripeConnect\StripeConnect;
use Stripe\Account as StripeAccount;
use Stripe\Stripe as StripeBase;
use Stripe\OAuth as StripeOAuth;

// Validators
use Illuminate\Http\Request;
use App\Http\Requests\ValidProfile;

// Other
use Auth;
use DB;
use Cloudder;
use Redirect;
use Input;
use App\Models\Favorite;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
		// User must be logged in to access anything here
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard, which displays the profile edit 
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		
		$rvtypes = RVTypes::all();
		
		return view('home')
			->with('rvtypes', $rvtypes);
    }
  
	 /**
     * Update the user profile
     *
     * @return \Illuminate\Http\Response
     */
    public function updateProfile(ValidProfile $request){

		// Ensure only the logged in user can update their profile. 
        $user = User::find(Auth::user()->id); 
        
        $user->name = $request->name;
		$user->traveller_rv_type_id = $request->rvType;
		$user->traveller_rv_size = $request->rvSize;
		$user->host_description = $request->description;
		
		$user->save();
        
        return Redirect::route('home');
    }
	
	/**
     * Update the user profile picture
     *
     * @return \Illuminate\Http\Response
     */
	public function updateProfilePhoto(Request $request)
	{
		// Ajax request so no debugbar on the response
		\Debugbar::disable();
		
		// Upload the file to Cloudinary & update the user's info
        if ($request->hasFile('file') && $request->file('file')->isValid()){
            $cloudder = Cloudder::upload($request->file->path());
            $uploadResult = $cloudder->getResult();
            $file_url = $uploadResult["url"];
            
			// Ensure only the logged in user is being updated
			$user = User::find(Auth::user()->id);
			$user->traveller_photo = $file_url;
			$user->save();
        }
		
        return response()->json(['file_url' => $file_url], 200);
	}
  
	/**
     * Onboard user as a host
	 * TODO:
	 * - What happens when they remove their account on Stripe's side?
     *
     * @return \Illuminate\Http\Response
     */
    public function onboardHost(Request $request)
    {
		// Ensure only the logged in user is being updated
        $user = User::find(Auth::user()->id); 
		
        StripeBase::setApiKey(config('services.stripe.secret'));
		
		// Check if the user is already a host
        if(!isset($user->stripe_acc) || $user->stripe_acc == NULL || $user->stripe_acc == ''){
            $result = StripeOAuth::token(['client_secret' => config('services.stripe.secret'), 'code' => $request->code, 'grant_type' => 'authorization_code']);
            $user->stripe_acc = $result->stripe_user_id;
            $user->save();
        } else {
        	// They already have an account setup. 
        }
    
    	return Redirect::route('view-own-listings');
 	}
  
	/**
     * Redirect the user to the proper Stripe url
     *
     * @return \Illuminate\Http\Response
     */  
	public function paymentDashboard()
	{
		// Ensure only the logged in user is being pulled
		$user = User::find(Auth::user()->id); 
		// If they have been onboarded, grab their login link. Otherwise grab the onboarding link
		if(isset($user->stripe_acc)) {
			StripeBase::setApiKey(config('services.stripe.secret'));
			$account = StripeAccount::retrieve($user->stripe_acc);
			$login = $account->login_links->create(); 
			$url = $login->url;
		} else {
			$url = 'https://connect.stripe.com/express/oauth/authorize?response_type=code&client_id=ca_CZPOIJTAFwCIvxb3S6hMMdERxIioebWG&scope=read_write';
		}
		
		return Redirect::to($url);
	}
	
	  
	/**
     * Add or Remove favorite
     *
     * @return \Illuminate\Http\Response
     */
	public function favorite(Request $request)
	{
		// Ajax request so no debugbar on the response
		\Debugbar::disable();
		
		// Check if it exists, cause we're going to undo it if it's there
		$favorite = Favorite::where('user_id', '=', Auth::user()->id)->where('listing_id', '=', $request->listing_id)->first();
		
		if(isset($favorite)){
			// Delete it
			$favorite->delete();
		} else {
			// Add it
			$favorite = new Favorite();
			$favorite->listing_id = $request->listing_id;
			$favorite->user_id = Auth::user()->id;
			$favorite->save();
		}
		
		return 200;
	}
	
	/**
     * Display favorites
     *
     * @return \Illuminate\Http\Response
     */
	public function favorites()
	{
		// Ensure only the logged in user's favorites are bieng pulled
		$favorites = Favorite::where('favorite_listings.user_id', '=', Auth::user()->id)
			->leftJoin('listings', 'listings.id', '=', 'favorite_listings.listing_id')
			->leftJoin(DB::raw("(select city, state, id as listing_id 
							from `listing_addresses`) as `list_address`"), 'list_address.listing_id', '=', 'listings.id')
			->leftJoin(DB::raw("(select url, listing_id 
							from `listings_images` 
							where `primary` = 1) as `list_images`"), 'list_images.listing_id', '=', 'listings.id')
			 ->leftJoin(DB::raw("(select listing_id, COUNT(id) as total_reviews, SUM(stars) as total_stars from reviews GROUP BY listing_id) as review_totals"), 
						'review_totals.listing_id', '=', 'listings.id')
			->where('published', '=', 1)
			->get();
		
		foreach($favorites as $fave){
			if($fave->total_reviews == 0){
				$fave->stars = 0;
			} else {
				$fave->stars = round($fave->total_stars/$fave->total_reviews);
			}
		}
		
		return view('dashboard.traveller.favorites')
			->with('listings', $favorites);
	}
	
	/**
     * Display bookings that haven't happened or are currently happening
     *
     * @return \Illuminate\Http\Response
     */
	public function upcomingTrips()
	{
		
		$now = \Carbon\Carbon::now()->format('Y-m-d');
		
		// Ensure only the logged in user's bookings are being pulled
		$listings = Booking::addSelect('*')->addSelect('bookings.id as booking_id')->where('traveller_id', '=', Auth::user()->id)
			->where('end_date', '>', $now)
			->whereNotNull('transaction_id')
			->whereNull('canceled_at')
			->leftJoin('listings', 'listings.id', '=', 'bookings.listing_id')
			->leftJoin('listing_addresses', 'listing_addresses.id', '=', 'listings.id')
			->leftJoin(DB::raw("(select url, listing_id 
							from `listings_images` 
							where `primary` = 1) as `list_images`"), 'list_images.listing_id', '=', 'listings.id')
			->orderBy('start_date')
			->get();
		
		return view('dashboard.traveller.upcoming')
			->with('listings', $listings);
	}
	
	/**
     * Display bookings where the end date has passed
     *
     * @return \Illuminate\Http\Response
     */
	public function pastTrips()
	{
		
		$now = \Carbon\Carbon::now()->format('Y-m-d');
		
		// Ensure only the logged in user's bookings are being pulled
		$listings = Booking::where('traveller_id', '=', Auth::user()->id)
			->where('end_date', '<', $now)
			->whereNotNull('transaction_id')
			->whereNull('canceled_at')
			->leftJoin('listings', 'listings.id', '=', 'bookings.listing_id')
			->leftJoin('listing_addresses', 'listing_addresses.id', '=', 'listings.id')
			->leftJoin(DB::raw("(select url, listing_id 
							from `listings_images` 
							where `primary` = 1) as `list_images`"), 'list_images.listing_id', '=', 'listings.id')
			->leftJoin(DB::raw("(select booking_id as review from `reviews`) as `listing_review`"), 'listing_review.review', '=', 'bookings.id')
			->get();
		
		
		return view('dashboard.traveller.past')
			->with('listings', $listings);
	}
	
	/**
     * Display the listing info related to the booking
     *
     * @return \Illuminate\Http\Response
     */
	public function viewBooking($id)
	{
		// Checks for are you the traveller of this booking??? otherwise 403 MF. 
		$booking = Booking::find($id);
		
		$listing = Listing::where('id', '=', $booking->listing_id)			
			->leftJoin(DB::raw("(select traveller_photo as host_url, name as host_name, host_description, id as host_id
							from `users`) as `host`"), 'host.host_id', '=', 'listings.user_id')
			->leftJoin(DB::raw("(select id as address_id, city, state, lat, lng
							from `listing_addresses`) as `address`"), 'address.address_id', '=', 'listings.id')
			->first();
		$listing->rating = $listing->getRating();
		$listing->reviews = $listing->getTotalReviews();
		
		$listing->nearby_attractions = json_decode($listing->nearby_attractions);
		$listing->nearby_conveniences = json_decode($listing->nearby_conveniences);
		
		
		// Listing Images
		$listing->images = ListingImages::where('listing_id', '=', $booking->listing_id)
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
			$listing->favorite = Favorite::where('listing_id', '=', $booking->listing_id)
							->where('user_id', '=', Auth::user()->id)
							->first();
		}
		
		// Grab the listing owner's other listings
		$other_listings = Listing::where('user_id', '=', $listing->user_id)
			  ->where('id', '!=', $listing->id)
			  ->leftJoin(DB::raw("(select city, state, id as listing_id 
							from `listing_addresses` ) as `list_address`"), 'list_address.listing_id', '=', 'listings.id')
			  ->leftJoin(DB::raw("(select url, listing_id 
							from `listings_images` 
							where `primary` = 1) as `list_images`"), 'list_images.listing_id', '=', 'listings.id')
			->limit('3')
			->getPublished();
		
		$reviews = Review::where('listing_id', '=', $listing->id)
			->leftJoin('users', 'users.id', '=', 'reviews.user_id')
			->get();
		
		return view('dashboard.traveller.booking')
			->with('listing', $listing)
			->with('amenities', $amenities)
			->with('other_listings', $other_listings)
			->with('reviews', $reviews)
			->with('booking', $booking);
	}
}

