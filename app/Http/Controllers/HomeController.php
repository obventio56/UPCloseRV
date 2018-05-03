<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Rap2hpoutre\LaravelStripeConnect\StripeConnect;
use Stripe\Account as StripeAccount;
use Stripe\Stripe as StripeBase;
use Stripe\OAuth as StripeOAuth;

use App\User;
use App\Models\RVTypes;
use App\Models\Booking;

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
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard, which displays the profile edit 
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$user = User::find(Auth::user()->id);
		$rvtypes = RVTypes::all();


		return view('home')
			->with('user', $user)
			->with('rvtypes', $rvtypes);
    }
  
    public function updateProfile(Request $request){

        $user = User::find(Auth::user()->id);
        
        $user->name = $request->name;
		$user->traveller_rv_type_id = $request->rvType;
		$user->traveller_rv_size = $request->rvSize;
		$user->host_description = $request->description;
		
		$user->save();
        
        return Redirect::route('home');
    }
	
	public function updateProfilePhoto(Request $request)
	{
		\Debugbar::disable();
        $file_url = "";
        if ($request->hasFile('file') && $request->file('file')->isValid()){
            $cloudder = Cloudder::upload($request->file->path());
            $uploadResult = $cloudder->getResult();
            $file_url = $uploadResult["url"];
            
			$user = User::find(Auth::user()->id);
			$user->traveller_photo = $file_url;
			$user->save();
        }
        return response()->json(['file_url' => $file_url], 200);
	}
  
    public function onboardHost(Request $request)
    {
        $user = User::find(Auth::user()->id); 
        StripeBase::setApiKey(config('services.stripe.secret'));
        if(!isset($user->stripe_acc) || $user->stripe_acc == NULL || $user->stripe_acc == ''){
            $result = StripeOAuth::token(['client_secret' => config('services.stripe.secret'), 'code' => $request->code, 'grant_type' => 'authorization_code']);
            $user->stripe_acc = $result->stripe_user_id;
            $user->save();
        } else {
        // They already have an account setup. Maybe direct them to support to figure out what they're trying to do?
        // acct_1CAdavCNdUEV90Bo
        }
    
    return Redirect::route('home');
  }
  
  public function paymentDashboard()
  {
    $user = User::find(Auth::user()->id); 
    if(isset($user->stripe_acc))
    {
      StripeBase::setApiKey(config('services.stripe.secret'));
      $account = StripeAccount::retrieve($user->stripe_acc);
      $login = $account->login_links->create(); 
      $url = $login->url;
    } else {
      $url = 'https://connect.stripe.com/express/oauth/authorize?response_type=code&client_id=ca_CZPOIJTAFwCIvxb3S6hMMdERxIioebWG&scope=read_write';
    }
    return Redirect::to($url);
  }
	
	// Add or remove favorite
	public function favorite(Request $request)
	{
		// check if one exists already... 
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
	
	public function favorites()
	{
		$favorites = Favorite::where('favorite_listings.user_id', '=', Auth::user()->id)->leftJoin('listings', 'listings.id', '=', 'favorite_listings.listing_id')->get();
		
		return view('dashboard.traveller.favorites')
			->with('listings', $favorites);
	}
	
	
	public function upcomingTrips()
	{
		$now = \Carbon\Carbon::now()->format('Y-m-d');
		$listings = Booking::where('traveller_id', '=', Auth::user()->id)
			->where('end_date', '>', $now)
			->leftJoin('listings', 'listings.id', '=', 'bookings.listing_id')
			->leftJoin('listing_addresses', 'listing_addresses.id', '=', 'listings.id')
			->leftJoin(DB::raw("(select url, listing_id 
							from `listings_images` 
							where `primary` = 1 LIMIT 1) as `list_images`"), 'list_images.listing_id', '=', 'listings.id')
			->get();
		
		return view('dashboard.traveller.upcoming')
			->with('listings', $listings);
	}
	
	public function pastTrips()
	{
		$now = \Carbon\Carbon::now()->format('Y-m-d');
		$listings = Booking::where('traveller_id', '=', Auth::user()->id)
			->where('end_date', '<', $now)
			->whereNotNull('transaction_id')
			->leftJoin('listings', 'listings.id', '=', 'bookings.listing_id')
			->leftJoin('listing_addresses', 'listing_addresses.id', '=', 'listings.id')
			->leftJoin(DB::raw("(select url, listing_id 
							from `listings_images` 
							where `primary` = 1 LIMIT 1) as `list_images`"), 'list_images.listing_id', '=', 'listings.id')
			->get();
		
		return view('dashboard.traveller.past')
			->with('listings', $listings);
	}
}

