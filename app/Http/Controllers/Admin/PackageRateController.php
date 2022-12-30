<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\PackageRate;
use App\Models\RoomCategory;
use Illuminate\Http\Request;
use DataTables;
use App\Http\Requests\ValidatePackageRate;
use Illuminate\Support\Facades\Crypt;

class PackageRateController extends Controller
{
   
    public function index($hotel_id,$date_plan_id,$package_id,Request $request)
    {
        if ($request->ajax()) {
            $data= PackageRate::query()->where('package_id',Crypt::decrypt($package_id));
            $status = $request->status_search;
            if ($status!=null) {
                $data->where(function ($query) use ($status) {
                    $query->where('status', $status);
                });
            }
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('room', function($data) {
                    if($data->room_category_id){
                        return $data->room->room_category;
                    }else{
                        return Null;
                    }
                })
                ->addColumn('meal', function($data) {
                    return $data->package->meal_plan;
                })
                ->addColumn('room_rates', function($data) {
                    $html="<b>Single : &#8377;</b>" . $data->single . "<br>";
                    $html=$html . "<b>Double : &#8377;</b>" . $data->double . "<br>";
                    $html=$html . "<b>Ex bed: &#8377;</b>" . $data->extra_adult . "<br>";
                    $html=$html . "<b>Ex bed(child): &#8377;</b>" . $data->extra_child_bed . "<br>";
                    $html=$html . "<b>Ex child w/o bed: &#8377;</b>" . $data->extra_child_wout_bed . "<br>";
                    return $html;
                })
                ->addColumn('meal_rates', function($data) {
                    $html="<b>Breakfast : &#8377;</b>" . $data->breakfast  . " / &#8377;" . $data->child_breakfast . "<br>";
                    $html=$html . "<b>Lunch: &#8377;</b>" . $data->lunch . " / &#8377;" . $data->child_lunch . "<br>";
                    $html=$html . "<b>Dinner: &#8377;</b>" . $data->dinner . " / &#8377;" . $data->child_dinner . "<br>";
                    return $html;
                })
                ->addColumn('days', function($data) {
                    $html=[];
                    foreach($data->days as $day){
                        switch ($day) {
                            case 1:
                                $html[]="Sun ";
;                               break;
                            case 2:
                                $html[]="Mon ";
                                break;
                            case 3:
                                $html[]="Tue ";
                                break;
                            case 4:
                                $html[]="Wed ";
                                break;
                            case 5:
                                $html[]="Thu ";
                                break;
                            case 6:
                                $html[]="Fri ";
                                break;
                            case 7:
                                $html[]="Sat ";
                                break;
                          }
                    }
                    return implode(', ',$html);
                })
                ->addColumn('action', 'application.hotels.date_plans.packages.rates.action')
                ->escapeColumns('aaData')
                ->make(true);
        }
        return view('application.hotels.date_plans.packages.rates.index',['hotel_id'=>$hotel_id,'date_plan_id'=>$date_plan_id,'package_id'=>$package_id]);
    }

    public function create($hotel_id,$date_plan_id,$package_id)
    {
        $roomCategories=RoomCategory::where('hotel_id',Crypt::decrypt($hotel_id))->where('status',1)->get();
        return view('application.hotels.date_plans.packages.rates.create',['hotel_id'=>$hotel_id,'date_plan_id'=>$date_plan_id,'package_id'=>$package_id,'roomCategories'=>$roomCategories]);
    }

    public function store($hotel_id,$date_plan_id,$package_id,ValidatePackageRate $request)
    {
        $data=$request->except(['_token','room_category','days']);
        $days=[];
        foreach($request->days as $day){
            $days[]=$day;
        }
        $data=array_merge($data,['room_category_id'=>Crypt::decrypt($request->room_category),'package_id'=>Crypt::decrypt($package_id),'days'=>$days]);
        $res=PackageRate::create($data);
        if($res){
            return redirect()->back()->with('success', 'Successfully updated the data.');
        }else{
            return redirect()->back()->with('error', 'Failed to update the data. Please try again.');
        }
    }

    public function show(PackageRate $packageRate)
    {
        //
    }

    public function edit($hotel_id,$date_plan_id,$package_id,$id)
    {
        $rate=PackageRate::find(Crypt::decrypt($id));
        $roomCategories=RoomCategory::where('hotel_id',Crypt::decrypt($hotel_id))->where('status',1)->get();
        return view('application.hotels.date_plans.packages.rates.edit',['hotel_id'=>$hotel_id,'date_plan_id'=>$date_plan_id,
            'package_id'=>$package_id,'roomCategories'=>$roomCategories,'data'=>$rate]);
    }

    public function update(ValidatePackageRate $request, $hotel_id,$date_plan_id,$package_id,$id)
    {
        $data=$request->except(['_token','room_category','days']);
        $days=[];
        foreach($request->days as $day){
            $days[]=$day;
        }
        $data=array_merge($data,['room_category_id'=>Crypt::decrypt($request->room_category),'package_id'=>Crypt::decrypt($package_id),'days'=>$days]);
        $rate=PackageRate::find(Crypt::decrypt($id));
        $res=$rate->update($data);
        if($res){
            return redirect()->back()->with('success', 'Successfully updated the data.');
        }else{
            return redirect()->back()->with('error', 'Failed to update the data. Please try again.');
        }
    }

    public function destroy($hotel_id,$date_plan_id,$package_id,$id)
    {
        $rate=PackageRate::find(Crypt::decrypt($id));
        $res=$rate->delete();
        if($res){
            return response()->json(['success'=>"Data deleted successfully!"]);
        }else{
            return response()->json(['error'=>"Failed to delete the data, kindly try again!"]);
        }
    }
}
