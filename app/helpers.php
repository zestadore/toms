<?php
 use App\Models\Availability;
 use App\Models\Itinerary;
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
