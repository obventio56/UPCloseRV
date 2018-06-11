<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Listing;
use App\Models\Booking;
use App\Models\Transaction;

class BookingConfirmation extends Mailable
{
    use Queueable, SerializesModels;
	
	public $listing;
	public $booking;
	public $transaction;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Listing $listing, Booking $booking, Transaction $transaction)
    {
        $this->listing = $listing;
		$this->booking = $booking;
		$this->transaction = $transaction;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('noreply@upcloserv.com')
					->subject('Your booking has been confirmed!')
					->view('mail.booking.confirmation');
    }
}
