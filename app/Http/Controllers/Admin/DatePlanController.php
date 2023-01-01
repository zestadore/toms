<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\DatePlan;
use Illuminate\Http\Request;
use DataTables;
use App\Http\Requests\ValidateDatePlan;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;
use App\Jobs\CopyPackageJob;

class DatePlanController extends Controller
{
   
    public function index($hotel_id,Request $request)
    {
        if ($request->ajax()) {
            $data= DatePlan::query()->where('hotel_id',Crypt::decrypt($hotel_id));
            $status = $request->status_search;
            if ($status!=null) {
                $data->where(function ($query) use ($status) {
                    $query->where('status', $status);
                });
            }
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('hotel', function($data) {
                    if($data->hotel_id){
                        return $data->hotel->hotel;
                    }else{
                        return Null;
                    }
                })
                ->addColumn('action', 'application.hotels.date_plans.action')
                ->make(true);
        }
        return view('application.hotels.date_plans.index',['hotel_id'=>$hotel_id]);
    }

    public function create($hotel_id)
    {
        return view('application.hotels.date_plans.create',['hotel_id'=>$hotel_id]);
    }

    public function store($hotel_id,ValidateDatePlan $request)
    {
        $status=0;
        if($request->status){
            $status=1;
        }
        $data=[
            'valid_from'=>Carbon::parse($request->valid_from)->format('Y-m-d'),
            'valid_to'=>Carbon::parse($request->valid_to)->format('Y-m-d'),
            'hotel_id'=>Crypt::decrypt($hotel_id),
            'status'=>$status,
            'description'=>$request->description
        ];
        $res=DatePlan::create($data);
        if($res){
            return redirect()->back()->with('success', 'Successfully updated the data.');
        }else{
            return redirect()->back()->with('error', 'Failed to update the data. Please try again.');
        }
    }

    public function show(DatePlan $datePlan)
    {
        //
    }

    public function edit($hotel_id,$id)
    {
        $datePlan=DatePlan::find(Crypt::decrypt($id));
        return view('application.hotels.date_plans.edit',['hotel_id'=>$hotel_id,'data'=>$datePlan]);
    }

    public function update(ValidateDatePlan $request, $hotel_id, $id)
    {
        $datePlan=DatePlan::find(Crypt::decrypt($id));
        $status=0;
        if($request->status){
            $status=1;
        }
        $data=[
            'valid_from'=>Carbon::parse($request->valid_from)->format('Y-m-d'),
            'valid_to'=>Carbon::parse($request->valid_to)->format('Y-m-d'),
            'hotel_id'=>Crypt::decrypt($hotel_id),
            'status'=>$status,
            'description'=>$request->description
        ];
        $res=$datePlan->update($data);
        if($res){
            return redirect()->back()->with('success', 'Successfully updated the data.');
        }else{
            return redirect()->back()->with('error', 'Failed to update the data. Please try again.');
        }
    }

    public function destroy($hotel_id,$id)
    {
        $datePlan=DatePlan::find(Crypt::decrypt($id));
        $res=$datePlan->delete();
        if($res){
            return response()->json(['success'=>"Data deleted successfully!"]);
        }else{
            return response()->json(['error'=>"Failed to delete the data, kindly try again!"]);
        }
    }

    public function getDatePlanList($hotel_id)
    {
        $datePlans=DatePlan::with('packages')->where('hotel_id',$hotel_id)->where('status',1)->get();
        $data=[];
        foreach($datePlans as $datePlan){
            if(count($datePlan->packages)>0){
                $data[]=$datePlan;
            }
        }
        return $data;
    }

    public function copyPackages(Request $request)
    {
        CopyPackageJob::dispatch($request->copyFrom,$request->copyTo);
        return response()->json(['message'=>"The packages & rates will be copied within a couple of minutes!"]);
    }
}
