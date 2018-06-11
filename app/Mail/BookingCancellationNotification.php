<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;


use App\Models\Booking;
use App\Models\Listing;

class BookingCancellationNotification extends Mailable
{
    use Queueable, SerializesModels;
	
	public $booking;
	
	public $listing;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Booking $booking, Listing $listing)
    {
        $this->booking = $booking;
		
		$this->listing = $listing;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('noreply@upcloserv.com')
					->subject('Booking cancellation confirmation')
					->view('mail.booking.cancellation')
					->with([
						'booking' => $this->booking,
						'listing' => $this->listing
						   ]);
    }
}
