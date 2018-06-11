<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class BookingNotification extends Mailable
{
    use Queueable, SerializesModels;

	
	public $listing;
	public $booking;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Listing $listing, Booking $booking)
    {
        $this->listing = $listing;
		$this->booking = $booking;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('noreply@upcloserv.com')
					->subject('You have a new booking!')
					->view('mail.booking.notification');
    }
}
