<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;


class MessageNotification extends Mailable
{
    use Queueable, SerializesModels;

	public $message;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($message)
    {
        $this->message = $message;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('noreply@upcloserv.com')
					->subject('You have a new message on upCLOSE-RV!')
					->view('mail.message')
					->with(['message' => $this->message]);
    }
}
