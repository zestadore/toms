<?php

namespace App\Http\Controllers\Operations\Quotations;
use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\DatePlan;
use App\Models\Package;
use App\Models\PackageRate;
use App\Models\RoomCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;

class RateController extends Controller
{
    public function getHotelList($destination_id)
    {
        $hotels=Hotel::where('destination_id',Crypt::decrypt($destination_id))->get();
        return response()->json($hotels);
    }

    public function getPackageList($hotel_id,$date)
    {
        $datePlan=DatePlan::with('packages')->where('hotel_id',Crypt::decrypt($hotel_id))
            ->where('valid_from','<=',Carbon::parse($date)->format('Y-m-d'))->where('valid_to','>=',Carbon::parse($date)->format('Y-m-d'))->first();
        if($datePlan){
            $packages=$datePlan->packages;
        }else{
            $packages=[];
        }
        return response()->json($packages);
    }

    public function getRoomCategories($hotel_id)
    {
        $rooms=RoomCategory::where('hotel_id',Crypt::decrypt($hotel_id))->get();
        return response()->json($rooms);
    }

    public function getRatesWithRoom($room_id,$package_id,$date)
    {
        $packageRates=PackageRate::where('package_id',Crypt::decrypt($package_id))->where('room_category_id',Crypt::decrypt($room_id))->get();
        $packageRate=Null;
        $dayOfTheWeek=Carbon::parse($date)->dayOfWeek;
        $dayOfTheWeek+=1;
        foreach($packageRates as $rate){
            $days=$rate->days;
            if(in_array($dayOfTheWeek, $days)){
                $packageRate=$rate;
            }
        }
        return response()->json($packageRate);
    }
}
