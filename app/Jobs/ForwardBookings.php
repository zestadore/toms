<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\QuoteRevisionDetail;
use App\Models\QuoteRevision;
use App\Models\Booking;
use App\Models\Quotation;
use App\Models\BookingDetails;
use Illuminate\Support\Facades\Mail;
use App\Mail\HotelBookingMail;
use Illuminate\Support\Facades\Crypt;

class ForwardBookings implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $bookingId;

    public function __construct($bookingId)
    {
        $this->bookingId=$bookingId;
    }

    public function handle()
    {
        $booking=Booking::find(Crypt::decrypt($this->bookingId));
        $bookingDetails=BookingDetails::where('booking_id',Crypt::decrypt($this->bookingId))->get();
        foreach($bookingDetails as $detail){
            $revision=QuoteRevisionDetail::find($detail->quote_revision_details_id);
            $email=$revision->hotel->email;
            $quotation=Quotation::find($booking->quotaion_id);
            $guest=$quotation->guest_name;
            $mainRevision=QuoteRevision::find($revision->revision_id);
            $rooms="";
            if($mainRevision->sgl_rooms>0){
                $rooms=$rooms . " " . $mainRevision->sgl_rooms . " SGL room/s, ";
            }
            if($mainRevision->dbl_rooms>0){
                $rooms=$rooms . " " . $mainRevision->dbl_rooms . " DBL room/s, ";
            }
            if($mainRevision->ex_bed_adults>0){
                $rooms=$rooms . " " . $mainRevision->ex_bed_adults . " Ex bed/s(Adult), ";
            }
            if($mainRevision->ex_bed_children>0){
                $rooms=$rooms . " " . $mainRevision->ex_bed_children . " Ex bed/s(Children), ";
            }
            if($mainRevision->ex_children_wout>0){
                $rooms=$rooms . " " . $mainRevision->ex_children_wout . " Ex children without bed";
            }
            $pax="";
            if($mainRevision->adults>0){
                $pax=$pax . "" . $mainRevision->adults . " Adults, ";
            }
            if($mainRevision->kids>0){
                $pax=$pax . "" . $mainRevision->kids. " Kids, ";
            }
            Mail::to($email)->send(new HotelBookingMail($revision,$guest,$mainRevision->meal_plan,$rooms,$pax));
        }
    }
}
