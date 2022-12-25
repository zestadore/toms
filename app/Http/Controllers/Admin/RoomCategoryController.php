<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\RoomCategory;
use Illuminate\Http\Request;
use DataTables;
use App\Http\Requests\ValidateRoom;
use Illuminate\Support\Facades\Crypt;

class RoomCategoryController extends Controller
{
    
    public function index($hotel_id,Request $request)
    {
        if ($request->ajax()) {
            $data= RoomCategory::query()->where('hotel_id',Crypt::decrypt($hotel_id));
            $search = $request->search;
            $status = $request->status_search;
            if ($search) {
                $data->where(function ($query) use ($search) {
                    $query->where('room_category', 'like', '%' . $search . '%');
                });
            }
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
                ->addColumn('action', 'application.hotels.room_categories.action')
                ->make(true);
        }
        return view('application.hotels.room_categories.index',['hotel_id'=>$hotel_id]);
    }

    public function create($hotel_id)
    {
        return view('application.hotels.room_categories.create',['hotel_id'=>$hotel_id]);
    }

    public function store($hotel_id,ValidateRoom $request)
    {
        $status=0;
        $image=Null;
        if($request->status){
            $status=1;
        }
        if($request->file('image')){
            $file= $request->file('image');
            $filename= date('YmdHi').$file->getClientOriginalName();
            $file-> move(public_path('uploads/hotels/rooms'), $filename);
            $image= $filename;
        }
        $data=[
            'room_category'=>$request->room_category,
            'hotel_id'=>Crypt::decrypt($hotel_id),
            'room_space'=>$request->room_space,
            'inventory'=>$request->inventory??0,
            'status'=>$status,
            'image'=>$image,
            'description'=>$request->description
        ];
        $res=RoomCategory::create($data);
        if($res){
            return redirect()->back()->with('success', 'Successfully updated the data.');
        }else{
            return redirect()->back()->with('error', 'Failed to update the data. Please try again.');
        }
    }

    public function show(RoomCategory $roomCategory)
    {
        //
    }

    public function edit($hotel_id,$id)
    {
        $room=RoomCategory::find(Crypt::decrypt($id));
        return view('application.hotels.room_categories.edit',['hotel_id'=>$hotel_id,'data'=>$room]);
    }

    public function update(ValidateRoom $request, $hotel_id,$id)
    {
        $room=RoomCategory::find(Crypt::decrypt($id));
        $status=0;
        $image=$room->image??null;
        if($request->status){
            $status=1;
        }
        if($request->file('image')){
            if($room->image!=null){
                $d=unlink(public_path('uploads/hotels/rooms/'. $room->image));
            }
            $file= $request->file('image');
            $filename= date('YmdHi').$file->getClientOriginalName();
            $file-> move(public_path('uploads/hotels/rooms'), $filename);
            $image= $filename;
        }
        $data=[
            'room_category'=>$request->room_category,
            'hotel_id'=>Crypt::decrypt($hotel_id),
            'room_space'=>$request->room_space,
            'inventory'=>$request->inventory??0,
            'status'=>$status,
            'image'=>$image,
            'description'=>$request->description
        ];
        $res=$room->update($data);
        if($res){
            return redirect()->back()->with('success', 'Successfully updated the data.');
        }else{
            return redirect()->back()->with('error', 'Failed to update the data. Please try again.');
        }
    }

    public function destroy($hotel_id,$id)
    {
        $room=RoomCategory::find(Crypt::decrypt($id));
        $res=$room->delete();
        if($res){
            return response()->json(['success'=>"Data deleted successfully!"]);
        }else{
            return response()->json(['error'=>"Failed to delete the data, kindly try again!"]);
        }
    }
}
