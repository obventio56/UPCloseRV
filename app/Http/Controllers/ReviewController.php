<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\Models\Listing;
use App\Models\Review;
use Auth;
use Redirect;

class ReviewController extends Controller
{
    // Must be logged in to see or use any functions on this page. 
    public function __construct()
    {
        $this->middleware('auth');
    }
	
	public function reviewListing($id)
	{
		// Do the checks
		
		// Continue
		$listing = Listing::find($id);
		
		return view('reviews.listing')
			->with('listing', $listing);
	}

	public function submitReviewListing(Request $request)
	{
		
		$review = new Review();
		
		$review->listing_id = $request->listing;
		$review->user_id	= Auth::user()->id;
		$review->stars		= $request->stars;
		$review->review		= $request->review;
		$review->booking_id	= 1;
		
		$review->save();
		
		// If the review drops the total rating below 3(1 or 2 stars) we are automatically going to deactivate
		// the listing and require an admin turn it back on. 
		if(Listing::calculateRating($request->listing) < 3){
			$listing = Listing::find($request->listing);
			$listing->published = 0;
			$listing->admin_lock = 1;
		}
		
		return Redirect::route('home');
	}
	
	public function reviewUser()
	{
		return view('reviews.user');
	}
	
	public function submitReviewUser(Request $request)
	{
		$review = new Review();
		
		$review->traveller_id = $request->traveller_id;
		$review->user_id	= Auth::user()->id;
		$review->stars		= $request->stars;
		$review->reviews	= $request->review;
		
		$review->save();
		
		
		return Redirect::view('home');
	}
}
