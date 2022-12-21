<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Destination;
use Illuminate\Http\Request;
use DataTables;
use App\Http\Requests\ValidateDestination;
use Illuminate\Support\Facades\Crypt;

class DestinationController extends Controller
{
    
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data= Destination::query();
            $search = $request->search;
            $status = $request->status_search;
            if ($search) {
                $data->where(function ($query) use ($search) {
                    $query->where('destination', 'like', '%' . $search . '%');
                });
            }
            if ($status!=null) {
                $data->where(function ($query) use ($status) {
                    $query->where('status', $status);
                });
            }
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', 'application.destinations.action')
                ->make(true);
        }
        return view('application.destinations.index');
    }

    public function create()
    {
        return view('application.destinations.create');
    }

    public function store(ValidateDestination $request)
    {
        $status=0;
        $image=Null;
        if($request->status){
            $status=1;
        }
        if($request->file('image')){
            $file= $request->file('image');
            $filename= date('YmdHi').$file->getClientOriginalName();
            $file-> move(public_path('uploads/destinations'), $filename);
            $image= $filename;
        }
        $data=[
            'destination'=>$request->destination,
            'status'=>$status,
            'image'=>$image,
            'description'=>$request->description
        ];
        $res=Destination::create($data);
        if($res){
            return redirect()->back()->with('success', 'Successfully updated the data.');
        }else{
            return redirect()->back()->with('error', 'Failed to update the data. Please try again.');
        }
    }

    public function show(Destination $destination)
    {
        //
    }

    public function edit($id)
    {
        $destination=Destination::find(Crypt::decrypt($id));
        return view('application.destinations.edit',['data'=>$destination]);
    }

    public function update(ValidateDestination $request, $id)
    {
        $destination=Destination::find(Crypt::decrypt($id));
        $status=0;
        $image=$destination->image??null;
        if($request->status){
            $status=1;
        }
        if($request->file('image')){
            if($destination->image!=null){
                $d=unlink(public_path('uploads/destinations/'. $destination->image));
            }
            $file= $request->file('image');
            $filename= date('YmdHi').$file->getClientOriginalName();
            $file-> move(public_path('uploads/destinations'), $filename);
            $image= $filename;
        }
        $data=[
            'destination'=>$request->destination,
            'status'=>$status,
            'image'=>$image,
            'description'=>$request->description
        ];
        $res=$destination->update($data);
        if($res){
            return redirect()->back()->with('success', 'Successfully updated the data.');
        }else{
            return redirect()->back()->with('error', 'Failed to update the data. Please try again.');
        }
    }

    public function destroy($id)
    {
        $destination=Destination::find(Crypt::decrypt($id));
        $res=$destination->delete();
        if($res){
            return response()->json(['success'=>"Data deleted successfully!"]);
        }else{
            return response()->json(['error'=>"Failed to delete the data, kindly try again!"]);
        }
    }
}
