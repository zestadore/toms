<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ValidateUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use DataTables;

class UserController extends Controller
{
    
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data= User::query()->whereNotIn('id',[Auth::user()->id]);
            $search = $request->search;
            if ($search) {
                $data->where(function ($query) use ($search) {
                    $query->where('first_name', 'like', '%' . $search . '%')->orWhere('last_name', 'like', '%' . $search . '%');
                });
            }
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', 'application.members.action')
                ->make(true);
        }
        return view('application.members.index');
    }

    public function create()
    {
        return view('application.members.create');
    }

    public function store(ValidateUser $request)
    {
        $image=Null;
        if($request->file('image')){
            $file= $request->file('image');
            $filename= date('YmdHi').$file->getClientOriginalName();
            $file-> move(public_path('uploads/profiles'), $filename);
            $image= $filename;
        }
        $data=[
            'first_name'=>$request->first_name,
            'last_name'=>$request->last_name,
            'email'=>$request->email,
            'mobile'=>$request->mobile,
            'password'=>Hash::make($request->password),
            'image'=>$image
        ];
        $res=User::create($data);
        if($res){
            return redirect()->back()->with('success', 'Successfully updated the data.');
        }else{
            return redirect()->back()->with('error', 'Failed to update the data. Please try again.');
        }
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $user=User::find(Crypt::decrypt($id));
        return view('application.members.edit',['data'=>$user]);
    }

    public function update(ValidateUser $request, $id)
    {
        $user=User::find(Crypt::decrypt($id));
        $image=$user->image??Null;
        if($request->file('image')){
            if($user->image!=null){
                $d=unlink(public_path('uploads/profiles/'. $user->image));
            }
            $file= $request->file('image');
            $filename= date('YmdHi').$file->getClientOriginalName();
            $file-> move(public_path('uploads/profiles'), $filename);
            $image= $filename;
        }
        $data=[
            'first_name'=>$request->first_name,
            'last_name'=>$request->last_name,
            'email'=>$request->email,
            'mobile'=>$request->mobile,
            'password'=>Hash::make($request->password),
            'image'=>$image
        ];
        $res=$user->update($data);
        if($res){
            return redirect()->back()->with('success', 'Successfully updated the data.');
        }else{
            return redirect()->back()->with('error', 'Failed to update the data. Please try again.');
        }
    }

    public function destroy($id)
    {
        $user=User::find(Crypt::decrypt($id));
        if($user->image!=null){
            unlink(public_path('uploads/profiles/'. $user->image));
        }
        $res=$user->delete();
        if($res){
            return response()->json(['success'=>"Data deleted successfully!"]);
        }else{
            return response()->json(['error'=>"Failed to delete the data, kindly try again!"]);
        }
    }
}
