<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Stripe\Stripe as StripeBase;
use Stripe\Charge as StripeCharge;
use Stripe\Refund as StripeRefund;
use Auth;
use Entrust;

class Booking extends Model
{
	use SoftDeletes;
	
	protected $dates = ['start_date', 'end_date', 'created_at', 'updated_at', 'canceled_at', 'deleted_at'];

    protected $table = 'bookings';
	
	protected $fillable = ['id', 'listing_id', 'traveller_id', 'transaction_id', 'start_date', 'end_date', 'stay_type', 'confirmed', 'created_at', 'updated_at', 'canceled_at', 'deleted_at'];
	
	public $subtotal, $fee, $total, $fee_percentage, $pricing, $days, $discount, $weeknight, $weeknights;
	
	
	public function calculateCost($day_pricing, $month_pricing, $weeknight_discount)
	{
		$datediff = strtotime($this->end_date) - strtotime($this->start_date);
		$this->days = round($datediff / (60 * 60 *24));
		
		if($this->days < 30){
			// Daily pricing
			$this->pricing = $day_pricing;
			$this->fee_percentage = 12;
			
			
			if($weeknight_discount > 0){
				$this->weeknights = $this->getWeeknightCount($this->start_date, $this->days);
				$this->discount = round(($this->getWeeknightCount($this->start_date, $this->days) * ($weeknight_discount / 100) * $this->pricing), 2);
			} else {
				$this->discount = 0;
			}
		} else {
			// Monthly pricing
			$this->pricing = round(($month_pricing / 30) , 2);
			$this->fee_percentage = 6;
		}
		
		$this->subtotal = round($this->pricing * $this->days, 2);

		$this->fee = round( (($this->subtotal - $this->discount) * ($this->fee_percentage / 100)), 2);
		$this->total = $this->subtotal - $this->discount + $this->fee;
		
		return true;
	}
	
	
	protected function getWeeknightCount($start_date, $days)
	{
		$weeknum = date('N', strtotime($start_date));
		$weeknight = 0;
		
		$it = 0;
		// Weeknights are Sunday-Thursday
		while($it < $days){
			
			if($weeknum == 5 || $weeknum == 6) {
				// If friday or saturday, don't do anything
			} else {
				// It's a weeknight
				$weeknight++;
			}
			
			// Iterate the day of the week
			if($weeknum == 7){
				$weeknum = 1;
			} else {
				$weeknum++;
			}
			
			// Iterate the day
			$it++;
			
		}
		return $weeknight;
	}
	
	
	public function cancelBooking()
	{
	
		$listing = Listing::find($this->listing_id);
		$transaction = Transaction::find($this->transaction_id);
		
		$fee = 0;
		$issueRefund = 0;
        
		// Check if we're authorized to cancel. Must be owner, traveller or admin.
		$userId = Auth::user()->id;
		if($userId != $listing->user_id && $userId != $this->traveller_id && !Entrust::can('cancel-bookings')){
			
			return false;
		}
		// Authorized, continue
		
		$timeToEndDate = strtotime($this->end_date) - time();
		if($timeToEndDate <= 0){
			// The booking is past the END date, it can't be cancelled.
			
			return false;
		}
		
		// Get a date diff
		$timeToStartDate = strtotime($this->start_date) - time();
		$daysUntilBooking = round($timeToStartDate / (60 * 60 *24), 2);
		
		// If the start date is too close & the issuer of the cancellation is the traveller, no refund is issued.
		if($userId == $this->traveller_id) {
			
			// check against the cancellation policies
			if($transaction->cancel_policy == 1 && $daysUntilBooking > 1){ // 1 - minimum 24 hours 
				$issueRefund = 1;
			} elseif ($transaction->cancel_policy == 2 && $daysUntilBooking > 3){ // 2 - minimum 3 days
				$issueRefund = 1;
			} elseif ($transaction->cancel_policy == 3 && $daysUntilBooking > 7){ // 3 - minimum 7 days
				$issueRefund = 1;
			}
			$fee = 10;
			
		} elseif($userId == $listing->user_id) {
			
			// Issue a full refund if the booking hasn't yet begun & the cancelling party is the host.
			if($timeToStartDate > 0) {
				$issueRefund = 1;
			}
			
		} else {
			
			// Admin cancellation either through user or listing suspension, must be at least 7 days out to cancel automatically. 
			if($daysUntilBooking > 7){
				$issueRefund = 1;
			} else {
				return false;
			}
			
		}
		
		if($issueRefund)
		{
			// Calculate the refund amount and include a fee if applicable
			$refund_amount = $transaction->amount - $fee;
			
			// Initiate the refund
			StripeBase::setApiKey(config('services.stripe.secret'));
		
			$refund = StripeRefund::create([
				'charge' => $transaction->charge_id,
				'amount' => ($refund_amount * 100),
			]);
			
			if($refund->status == 'succeeded'){
				
				$transaction->refund_id = $refund->id;
				$transaction->refunded_amount = $refund->amount;
				$transaction->save();
				
			} else {
				// The refund did not work properly, so don't cancel the booking.
				return false;
				
			}
		}
		
		$this->canceled_at = time();
		$this->save();
		// Everything is good to go.
		return true;

	}
}