<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\User;
use App\Models\Booking;
use App\Models\Listing;
use Redirect;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'permission:can-query']);
    }
	
	
	public function listings()
	{
		$listings = Listing::leftJoin(DB::raw("(SELECT id as user_id, name as user_name FROM users) as owner"), 'owner.user_id', '=', 'listings.user_id')->get();
		
		
		return view('admin.listings')->with('listings', $listings);
	}
	
	// Lifts the admin lock, the user must still manually publish the listing
	public function activateListing($id)
	{
		$listing = Listing::find($id);
		
		$listing->admin_lock = 0;
		
		$listing->save();
		
		return Redirect::route('admin-listings');
	}
	
	// Deactivating a listing does not cancel it's bookings, only removes it from being publicly viewed until an admin unlocks it.
	public function deactivateListing($id)
	{
		$listing = Listing::find($id);
		
		$listing->published = 0;
		$listing->admin_lock = 1;
		
		$listing->save();
		
		return Redirect::route('admin-listings');
	}
	
	// Deactivates, locks and cancels any booking associated with the listing. 
	public function suspendListing($id)
	{
		$listing = Listing::find($id);
		
		$listing->published = 0;
		$listing->admin_lock = 1;
		$listing->suspended = 1;
		
		$listing->save();
		
		// and now cancel the bookings...
		
		$bookings = Booking::where('listing_id', '=', $listing->id)
			->whereNull('deleted_at')
			->whereNull('canceled_at')
			->whereNotNull('transaction_id')
			->get();
		
		foreach($bookings as $booking)
		{
			$booking->cancelBooking();
		}
		
		return Redirect::route('admin-listings');
	}
	
	// Removes the suspension and lock on the listing.
	public function unsuspendListing($id)
	{
		
		$listing = Listing::find($id);
		
		$listing->suspended = 0;
		$listing->admin_lock = 0;
		
		$listing->save();
		return Redirect::route('admin-listings');
	}
	
	public function verifyListing($id)
	{
		$listing = Listing::find($id);
		
		$listing->verified = 1;
		
		$listing->save();
		
		return Redirect::route('admin-listings');
	}
	
	public function unverifyListing($id)
	{
		$listing = Listing::find($id);
		
		$listing->verified = 0;
		
		$listing->save();
		
		return Redirect::route('admin-listings');
	}
    
    public function users()
    {
        $users = User::all();
        
        return view('admin.users')->with('users', $users);
    }
    
    public function suspendUser($id)
    {
        $user = User::find($id);
        
        $user->suspended = 1;
        $user->save();
        
        $listings = Listing::where('user_id', '=', $id);
		
		foreach($listings as $listing){
			
			$listing->published = 0;
			$listing->admin_lock = 1;
			$listing->suspended = 1;

			$listing->save();	
				
						
			$bookings = Booking::where('listing_id', '=', $listing->id)
				->whereNull('deleted_at')
				->whereNull('canceled_at')
				->whereNotNull('transaction_id')
				->get();

			foreach($bookings as $booking)
			{
				$booking->cancelBooking();
			}
		}
        
        return Redirect::route('admin-users');
    }
    
    public function unsuspendUser($id)
    {
        $user = User::find($id);
        
        $user->suspended = 0;
        $user->save();
        
        return Redirect::route('admin-users');
    }
    
    public function query()
    {
        
        return view('admin.query');
    }
    
    public function runQuery(Request $request)
    {
        \Debugbar::disable();
        $input = $request->all();
        try{
            $results = DB::connection('mysql_select')->select($input['query'], [1]);
        }
        catch(\Exception $e){
            return view('admin.query')->with('results', $e->getMessage()); 
        }
        $csv = \League\Csv\Writer::createFromFileObject(new \SplTempFileObject());
        
        // Headers
        $variables = get_object_vars($results[0]);
        $array = array ();
        foreach ( $variables as $key => $value ) {
            $array[] = $key;
        }
        $csv->insertOne($array);
        
        foreach ($results as $result) {
            $csv->insertOne( $this->toArray($result) );
        }
        
        $csv->output('report-'.date('d-m-Y-u').'.csv');
        
        //return view('admin.query')->with('results', $results);
        
    }
    
    private function toArray($obj) {
        $vars = get_object_vars ( $obj );
        $array = array ();
        foreach ( $vars as $key => $value ) {
            $array [ltrim ( $key, '_' )] = $value;
        }
        return $array;
    }
	
}
