<?php
namespace App\Classes;
use App\Models\Package;
use App\Models\PackageRate;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;

class CopyPackageClass {
    
    public function copyPackage($fromId,$toId)
    {
        $packages=Package::with('packageRates')->where('date_plan_id',Crypt::decrypt($fromId))->where('status',1)->get();
        $rates=[];
        foreach($packages as $package){
            $copy = $package->replicate();
            $copy->date_plan_id = Crypt::decrypt($toId);
            $copy->save();
            $packageRates=$package->packageRates;
            foreach($packageRates as $rate){
                $days=[];
                foreach($rate->days as $day){
                    $days[]=$day;
                }
                $rates[]=[
                    'package_id'=>Crypt::decrypt($copy->id),
                    'room_category_id'=>$rate->room_category_id,
                    'single'=>$rate->single,
                    'double'=>$rate->double,
                    'extra_adult'=>$rate->extra_adult,
                    'extra_child_bed'=>$rate->extra_child_bed,
                    'extra_child_wout_bed'=>$rate->extra_child_wout_bed,
                    'breakfast'=>$rate->breakfast,
                    'lunch'=>$rate->lunch,
                    'dinner'=>$rate->dinner,
                    'child_breakfast'=>$rate->child_breakfast,
                    'child_lunch'=>$rate->child_lunch,
                    'child_dinner'=>$rate->child_dinner,
                    'days'=>json_encode($days),
                    'created_by'=>Auth::user()->id,
                    'updated_by'=>Auth::user()->id,
                    'created_at'=>Now()
                ];
            }
        }
        PackageRate::insert($rates);
    }

}