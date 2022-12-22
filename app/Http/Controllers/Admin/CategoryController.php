<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use DataTables;
use App\Http\Requests\ValidateCategory;
use Illuminate\Support\Facades\Crypt;

class CategoryController extends Controller
{
    
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data= Category::query();
            $search = $request->search;
            $status = $request->status_search;
            if ($search) {
                $data->where(function ($query) use ($search) {
                    $query->where('category', 'like', '%' . $search . '%');
                });
            }
            if ($status!=null) {
                $data->where(function ($query) use ($status) {
                    $query->where('status', $status);
                });
            }
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', 'application.categories.action')
                ->make(true);
        }
        return view('application.categories.index');
    }

    public function create()
    {
        return view('application.categories.create');
    }

    public function store(ValidateCategory $request)
    {
        $status=0;
        if($request->status){
            $status=1;
        }
        $data=[
            'category'=>$request->category,
            'status'=>$status,
            'star'=>$request->star,
            'description'=>$request->description
        ];
        $res=Category::create($data);
        if($res){
            return redirect()->back()->with('success', 'Successfully updated the data.');
        }else{
            return redirect()->back()->with('error', 'Failed to update the data. Please try again.');
        }
    }

    public function show(Category $category)
    {
        //
    }

    public function edit($id)
    {
        $category=Category::find(Crypt::decrypt($id));
        return view('application.categories.edit',['data'=>$category]);
    }

    public function update(ValidateCategory $request, $id)
    {
        $category=Category::find(Crypt::decrypt($id));
        $status=0;
        if($request->status){
            $status=1;
        }
        $data=[
            'category'=>$request->category,
            'status'=>$status,
            'star'=>$request->star,
            'description'=>$request->description
        ];
        $res=$category->update($data);
        if($res){
            return redirect()->back()->with('success', 'Successfully updated the data.');
        }else{
            return redirect()->back()->with('error', 'Failed to update the data. Please try again.');
        }
    }

    public function destroy($id)
    {
        $category=Category::find(Crypt::decrypt($id));
        $res=$category->delete();
        if($res){
            return response()->json(['success'=>"Data deleted successfully!"]);
        }else{
            return response()->json(['error'=>"Failed to delete the data, kindly try again!"]);
        }
    }
}
