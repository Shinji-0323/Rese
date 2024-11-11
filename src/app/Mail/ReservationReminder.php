<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReservationReminder extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $reservation;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $reservation)
    {
        $this->user = $user;
        $this->reservation = $reservation;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('予約リマインダー')
                    ->markdown('emails.reminder')
                    ->with([
                        'user' => $this->user,
                        'reservation' => $this->reservation,
                    ]);
    }
}
