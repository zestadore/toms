<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class HotelBookingMail extends Mailable
{
    use Queueable, SerializesModels;
    public $revision,$guest,$mealPlan,$rooms,$pax;

    public function __construct($revsion,$guest,$mealPlan,$rooms,$pax)
    {
        $this->revision=$revsion;
        $this->guest=$guest;
        $this->mealPlan=$mealPlan;
        $this->rooms=$rooms;
        $this->pax=$pax;
    }

    public function envelope()
    {
        $subject="Booking for " . $this->guest . " - " . $this->revision->destination->destination . " - " . $this->revision->hotel->hotel;
        return new Envelope(
            subject: $subject,
        );
    }

    public function content()
    {
        return new Content(
            view: 'mails.hotel_booking',
        );
    }

    public function attachments()
    {
        return [];
    }
}
