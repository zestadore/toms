<?php
 use App\Models\Availability;
 use App\Models\Itinerary;
 use App\Models\BookingDetails;
 use App\Models\Payment;
use App\Models\QuoteRevision;
use Illuminate\Support\Facades\Crypt;
 use Illuminate\Support\Facades\Auth;

function getAvailabilityStatus($id){
    $availability=Availability::where('quote_revision_id',Crypt::decrypt($id))->where('status',1)->first();
    if($availability){
        return true;
    }else{
        return false;
    }
}

function getAvailabilityResult($id){
    $availability=Availability::where('quote_revision_id',Crypt::decrypt($id))->where('status',1)->first();
    if($availability){
        return $availability->availability_note;
    }else{
        return "";
    }
}

function getItinerary($id){
    $itinerary=Itinerary::find($id);
    if($itinerary){
        return $itinerary->itinerary;
    }else{
        return null;
    }
}

function getBookingDetailsId($id){
    $detail=BookingDetails::where('quote_revision_details_id',Crypt::decrypt($id))->first();
    return $detail->id;
}

function checkPayments($bookingId,$quoteRevisionId){
    $revision=QuoteRevision::find($quoteRevisionId);
    $payments=Payment::where('booking_id',Crypt::decrypt($bookingId))->whereIn('status',[0,1])->sum('amount');
    if($revision->net_rate<=$payments){
        return True;
    }else{
        return False;
    }
}

function getPendingPayments($bookingId,$quoteRevisionId){
    $revision=QuoteRevision::find($quoteRevisionId);
    $payments=Payment::where('booking_id',$bookingId)->whereIn('status',[0,1])->sum('amount');
    return ['balance'=>($revision->net_rate-$payments),'amount_paid'=>$payments,'total_amount'=>$revision->net_rate];
}
