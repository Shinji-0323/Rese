<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

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
        $qrCodeData = route('reservation.verify', ['reservation_id' => $this->reservation->id]);

        $qrCode = QrCode::format('png')->size(200)->generate($qrCodeData);

        return $this->subject('予約リマインダー')
                    ->markdown('emails.reminder')
                    ->with([
                        'user' => $this->user,
                        'reservation' => $this->reservation,
                        'qrCode' => $qrCode,
                    ]);
    }
}
