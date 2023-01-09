<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\AddOn;
use Illuminate\Http\Request;
use App\Http\Requests\ValidateAddon;
use Illuminate\Support\Facades\Crypt;
use DataTables;

class VehicleAddOnController extends Controller
{
    
    public function index($parent_id,Request $request)
    {
        if ($request->ajax()) {
            $data= Addon::query()->where('parent_id',Crypt::decrypt($parent_id))->where('type',0);
            $status = $request->status_search;
            if ($status!=null) {
                $data->where(function ($query) use ($status) {
                    $query->where('status', $status);
                });
            }
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', 'application.vehicles.addons.action')
                ->make(true);
        }
        return view('application.vehicles.addons.index',['hotel_id'=>$parent_id]);
    }

    public function create($parent_id)
    {
        return view('application.vehicles.addons.create',['hotel_id'=>$parent_id]);
    }

    public function store($parent_id,ValidateAddon $request)
    {
        $data=[
            'add_on'=>$request->addon,
            'price'=>$request->price,
            'type'=>0,
            'description'=>$request->description,
            'parent_id'=>Crypt::decrypt($parent_id)
        ];
        $res=Addon::create($data);
        if($res){
            return redirect()->back()->with('success', 'Successfully updated the data.');
        }else{
            return redirect()->back()->with('error', 'Failed to update the data. Please try again.');
        }
    }

    public function show(AddOn $addOn)
    {
        //
    }

    public function edit($parent_id,$id)
    {
        $addOn=Addon::find(Crypt::decrypt($id));
        return view('application.vehicles.addons.edit',['hotel_id'=>$parent_id,'data'=>$addOn]);
    }

    public function update(ValidateAddon $request, $parent_id,$id)
    {
        $addOn=Addon::find(Crypt::decrypt($id));
        $data=[
            'add_on'=>$request->addon,
            'price'=>$request->price,
            'type'=>0,
            'description'=>$request->description,
            'parent_id'=>Crypt::decrypt($parent_id)
        ];
        $res=$addOn->update($data);
        if($res){
            return redirect()->back()->with('success', 'Successfully updated the data.');
        }else{
            return redirect()->back()->with('error', 'Failed to update the data. Please try again.');
        }
    }

    public function destroy($parent_id,$id)
    {
        $addOn=Addon::find(Crypt::decrypt($id));
        $res=$addOn->delete();
        if($res){
            return response()->json(['success'=>"Data deleted successfully!"]);
        }else{
            return response()->json(['error'=>"Failed to delete the data, kindly try again!"]);
        }
    }
}
