<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use DataTables;
use App\Http\Requests\ValidateVehicle;
use Illuminate\Support\Facades\Crypt;

class VehicleController extends Controller
{
    
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data= Vehicle::query();
            $search = $request->search;
            if ($search) {
                $data->where(function ($query) use ($search) {
                    $query->where('vehicle', 'like', '%' . $search . '%');
                });
            }
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', 'application.vehicles.action')
                ->make(true);
        }
        return view('application.vehicles.index');
    }

    public function create()
    {
        return view('application.vehicles.create');
    }

    public function store(ValidateVehicle $request)
    {
        $status=0;
        if($request->status){
            $status=1;
        }
        $data=[
            'vehicle'=>$request->vehicle,
            'status'=>$status,
            'condition'=>$request->condition,
            'seating_capacity'=>$request->seating_capacity,
            'category'=>$request->category,
            'kms_allowed'=>$request->kms_allowed,
            'rate'=>$request->rate,
            'add_km_rate'=>$request->add_km_rate
        ];
        $res=Vehicle::create($data);
        if($res){
            return redirect()->back()->with('success', 'Successfully updated the data.');
        }else{
            return redirect()->back()->with('error', 'Failed to update the data. Please try again.');
        }
    }

    public function show(Vehicle $vehicle)
    {
        //
    }

    public function edit($id)
    {
        $vehicle=Vehicle::find(Crypt::decrypt($id));
        return view('application.vehicles.edit',['data'=>$vehicle]);
    }

    public function update(ValidateVehicle $request, $id)
    {
        $vehicle=Vehicle::find(Crypt::decrypt($id));
        $status=0;
        if($request->status){
            $status=1;
        }
        $data=[
            'vehicle'=>$request->vehicle,
            'status'=>$status,
            'condition'=>$request->condition,
            'seating_capacity'=>$request->seating_capacity,
            'category'=>$request->category,
            'kms_allowed'=>$request->kms_allowed,
            'rate'=>$request->rate,
            'add_km_rate'=>$request->add_km_rate
        ];
        $res=$vehicle->update($data);
        if($res){
            return redirect()->back()->with('success', 'Successfully updated the data.');
        }else{
            return redirect()->back()->with('error', 'Failed to update the data. Please try again.');
        }
    }

    public function destroy($id)
    {
        $vehicle=Vehicle::find(Crypt::decrypt($id));
        $res=$vehicle->delete();
        if($res){
            return response()->json(['success'=>"Data deleted successfully!"]);
        }else{
            return response()->json(['error'=>"Failed to delete the data, kindly try again!"]);
        }
    }
}
