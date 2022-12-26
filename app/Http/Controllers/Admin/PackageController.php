<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;
use DataTables;
use App\Http\Requests\ValidatePackage;
use Illuminate\Support\Facades\Crypt;

class PackageController extends Controller
{
    
    public function index($hotel_id,$date_plan_id,Request $request)
    {
        if ($request->ajax()) {
            $data= Package::query()->where('date_plan_id',Crypt::decrypt($date_plan_id));
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
                ->addColumn('action', 'application.hotels.date_plans.packages.action')
                ->make(true);
        }
        return view('application.hotels.date_plans.packages.index',['hotel_id'=>$hotel_id,'date_plan_id'=>$date_plan_id]);
    }

    public function create($hotel_id,$date_plan_id)
    {
        return view('application.hotels.date_plans.packages.create',['hotel_id'=>$hotel_id,'date_plan_id'=>$date_plan_id]);
    }

    public function store($hotel_id,$date_plan_id,ValidatePackage $request)
    {
        $status=0;
        if($request->status){
            $status=1;
        }
        $data=[
            'package'=>$request->package,
            'no_nights'=>$request->no_nights,
            'meal_plan'=>$request->meal_plan,
            'status'=>$status,
            'description'=>$request->description,
            'date_plan_id'=>Crypt::decrypt($date_plan_id)
        ];
        $res=Package::create($data);
        if($res){
            return redirect()->back()->with('success', 'Successfully updated the data.');
        }else{
            return redirect()->back()->with('error', 'Failed to update the data. Please try again.');
        }
    }

    public function show(Package $package)
    {
        //
    }

    public function edit($hotel_id,$date_plan_id,$id)
    {
        $package=Package::find(Crypt::decrypt($id));
        return view('application.hotels.date_plans.packages.edit',['hotel_id'=>$hotel_id,'date_plan_id'=>$date_plan_id,'data'=>$package]);
    }

    public function update(ValidatePackage $request, $hotel_id, $date_plan_id, $id)
    {
        $package=Package::find(Crypt::decrypt($id));
        $status=0;
        if($request->status){
            $status=1;
        }
        $data=[
            'package'=>$request->package,
            'no_nights'=>$request->no_nights,
            'meal_plan'=>$request->meal_plan,
            'status'=>$status,
            'description'=>$request->description,
            'date_plan_id'=>Crypt::decrypt($date_plan_id)
        ];
        $res=$package->update($data);
        if($res){
            return redirect()->back()->with('success', 'Successfully updated the data.');
        }else{
            return redirect()->back()->with('error', 'Failed to update the data. Please try again.');
        }
    }

    public function destroy($hotel_id, $date_plan_id, $id)
    {
        $package=Package::find(Crypt::decrypt($id));
        $res=$package->delete();
        if($res){
            return response()->json(['success'=>"Data deleted successfully!"]);
        }else{
            return response()->json(['error'=>"Failed to delete the data, kindly try again!"]);
        }
    }
}
