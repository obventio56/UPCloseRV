<?php

namespace App\Console;

use App\Models\Transaction;

use Carbon\Carbon;
use DB;

use Stripe\Stripe as StripeBase;
use Stripe\Transfer as StripeTransfer;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
		
		// Release booking dates that haven't been completed in 30 minutes
		$schedule->call(function(){
			DB::table('bookings')->whereNull('transaction_id')->where('created_at', '<', Carbon::now()->subMinutes(30)->toDateTimeString())->delete();
		})->everyMinute();
		
		// Transfer funds to hosts... 
		$schedule->call(function(){
			StripeBase::setApiKey(config('services.stripe.secret'));	
			
			$transfers = DB::table('bookings')
				->leftJoin('transaction', 'bookings.transaction_id', '=', 'transaction.id')
				->leftJoin('listings', 'bookings.listing_id', '=', 'listings.id')
				->leftJoin('users', 'listings.user_id', '=', 'users.id')
				->where('end_date', '<', Carbon::now())
				->whereNull('host_transfer')
				->get();
	
			foreach($transfers as $transfer){
				
				$amount = round(($transfer->amount * .85)*100);
				
				$stripe = \Stripe\Transfer::create(array(
				  "amount" => $amount,
				  "currency" => "usd",
				  "destination" => $transfer->stripe_acc,
				  "transfer_group" => $transfer->booking_id,
				));
				
				$transaction = Transaction::find($transfer->transaction_id);
				$transaction->paid_to_host = ($amount/100);
				$transaction->host_transfer = $stripe->id;
				$transaction->save();
	
			}
			
		})->daily();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
