<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\Destination;
use App\Models\Category;
use Illuminate\Http\Request;
use DataTables;
use App\Http\Requests\ValidateHotel;
use Illuminate\Support\Facades\Crypt;

class HotelController extends Controller
{
   
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data= Hotel::query();
            $search = $request->search;
            $status = $request->status_search;
            if ($search) {
                $data->where(function ($query) use ($search) {
                    $query->where('hotel', 'like', '%' . $search . '%');
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
                ->addColumn('action', 'application.hotels.action')
                ->make(true);
        }
        return view('application.hotels.index');
    }

    public function create()
    {
        $destinations=Destination::where('status',1)->get();
        $categories=Category::where('status',1)->get();
        return view('application.hotels.create',['destinations'=>$destinations,'categories'=>$categories]);
    }

    public function store(ValidateHotel $request)
    {
        $status=0;
        $image=Null;
        if($request->status){
            $status=1;
        }
        if($request->file('image')){
            $file= $request->file('image');
            $filename= date('YmdHi').$file->getClientOriginalName();
            $file-> move(public_path('uploads/hotels'), $filename);
            $image= $filename;
        }
        $data=[
            'hotel'=>$request->hotel,
            'destination_id'=>Crypt::decrypt($request->destination_id),
            'location'=>$request->location,
            'inventory'=>$request->inventory??0,
            'category_id'=>Crypt::decrypt($request->category_id)??null,
            'contact'=>$request->contact,
            'reservation_contact'=>$request->reservation_contact,
            'email'=>$request->email,
            'website'=>$request->website,
            'address'=>$request->address,
            'status'=>$status,
            'image'=>$image,
            'description'=>$request->description
        ];
        $res=Hotel::create($data);
        if($res){
            return redirect()->back()->with('success', 'Successfully updated the data.');
        }else{
            return redirect()->back()->with('error', 'Failed to update the data. Please try again.');
        }
    }

    public function show(Hotel $hotel)
    {
        //
    }

    public function edit($id)
    {
        $hotel=Hotel::find(Crypt::decrypt($id));
        $destinations=Destination::where('status',1)->get();
        $categories=Category::where('status',1)->get();
        return view('application.hotels.edit',['destinations'=>$destinations,'categories'=>$categories,'data'=>$hotel]);
    }

    public function update(ValidateHotel $request, $id)
    {
        $hotel=Hotel::find(Crypt::decrypt($id));
        $status=0;
        $image=$hotel->image??null;
        if($request->status){
            $status=1;
        }
        if($request->file('image')){
            if($hotel->image!=null){
                $d=unlink(public_path('uploads/hotels/'. $hotel->image));
            }
            $file= $request->file('image');
            $filename= date('YmdHi').$file->getClientOriginalName();
            $file-> move(public_path('uploads/hotels'), $filename);
            $image= $filename;
        }
        $data=[
            'hotel'=>$request->hotel,
            'destination_id'=>Crypt::decrypt($request->destination_id),
            'location'=>$request->location,
            'inventory'=>$request->inventory??0,
            'category_id'=>Crypt::decrypt($request->category_id)??null,
            'contact'=>$request->contact,
            'reservation_contact'=>$request->reservation_contact,
            'email'=>$request->email,
            'website'=>$request->website,
            'address'=>$request->address,
            'status'=>$status,
            'image'=>$image,
            'description'=>$request->description
        ];
        $res=$hotel->update($data);
        if($res){
            return redirect()->back()->with('success', 'Successfully updated the data.');
        }else{
            return redirect()->back()->with('error', 'Failed to update the data. Please try again.');
        }
    }

    public function destroy($id)
    {
        $hotel=Hotel::find(Crypt::decrypt($id));
        $res=$hotel->delete();
        if($res){
            return response()->json(['success'=>"Data deleted successfully!"]);
        }else{
            return response()->json(['error'=>"Failed to delete the data, kindly try again!"]);
        }
    }
}
