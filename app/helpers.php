<?php
 use App\Models\Availability;
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
