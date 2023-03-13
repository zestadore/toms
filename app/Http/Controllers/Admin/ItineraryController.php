<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Itinerary;
use App\Models\Destination;
use Illuminate\Http\Request;
use App\Http\Requests\ValidateItinerary;
use Illuminate\Support\Facades\Crypt;
use DataTables;

class ItineraryController extends Controller
{
    
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data= Itinerary::query();
            $search = $request->search;
            $status = $request->status_search;
            if ($search) {
                $data->where(function ($query) use ($search) {
                    $query->where('title', 'like', '%' . $search . '%');
                });
            }
            if ($status!=null) {
                $data->where(function ($query) use ($status) {
                    $query->where('status', $status);
                });
            }
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('destination', function($data) {
                    if($data->destination_id){
                        return $data->destination->destination;
                    }else{
                        return Null;
                    }
                })
                ->addColumn('action', 'application.itinerary.action')
                ->make(true);
        }
        return view('application.itinerary.index');
    }

    public function create()
    {
        $destinations=Destination::where('status',1)->get();
        return view('application.itinerary.create',['destinations'=>$destinations]);
    }

    public function store(ValidateItinerary $request)
    {
        $status=0;
        if($request->status){
            $status=1;
        }
        $data=[
            'title'=>$request->title,
            'destination_id'=>Crypt::decrypt($request->destination_id),
            'itinerary'=>$request->itinerary,
            'status'=>$status
        ];
        $res=Itinerary::create($data);
        if($res){
            return redirect()->back()->with('success', 'Successfully updated the data.');
        }else{
            return redirect()->back()->with('error', 'Failed to update the data. Please try again.');
        }
    }

    public function show(Itinerary $itinerary)
    {
        //
    }

    public function edit($id)
    {
        $itinerary=Itinerary::find(Crypt::decrypt($id));
        $destinations=Destination::where('status',1)->get();
        return view('application.itinerary.edit',['destinations'=>$destinations,'data'=>$itinerary]);
    }

    public function update(ValidateItinerary $request, $id)
    {
        $itinerary=Itinerary::find(Crypt::decrypt($id));
        $status=0;
        if($request->status){
            $status=1;
        }
        $data=[
            'title'=>$request->title,
            'destination_id'=>Crypt::decrypt($request->destination_id),
            'itinerary'=>$request->itinerary,
            'status'=>$status
        ];
        $res=$itinerary->update($data);
        if($res){
            return redirect()->back()->with('success', 'Successfully updated the data.');
        }else{
            return redirect()->back()->with('error', 'Failed to update the data. Please try again.');
        }
    }

    public function destroy($id)
    {
        $itinerary=Itinerary::find(Crypt::decrypt($id));
        $res=$itinerary->delete();
        if($res){
            return response()->json(['success'=>"Data deleted successfully!"]);
        }else{
            return response()->json(['error'=>"Failed to delete the data, kindly try again!"]);
        }
    }
}
