<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\QuotationNote;
use Illuminate\Http\Request;
use DataTables;
use App\Http\Requests\ValidateNote;
use Illuminate\Support\Facades\Crypt;

class QuotationNoteController extends Controller
{
    
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data= QuotationNote::query();
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
                ->addColumn('action', 'application.quotation_notes.action')
                ->make(true);
        }
        return view('application.quotation_notes.index');
    }

    public function create()
    {
        return view('application.quotation_notes.create');
    }

    public function store(ValidateNote $request)
    {
        $status=0;
        if($request->status){
            $status=1;
        }
        $data=[
            'title'=>$request->title,
            'status'=>$status,
            'description'=>$request->description,
        ];
        $res=QuotationNote::create($data);
        if($res){
            return redirect()->back()->with('success', 'Successfully updated the data.');
        }else{
            return redirect()->back()->with('error', 'Failed to update the data. Please try again.');
        }
    }

    public function show(QuotationNote $quotationNote)
    {
        //
    }

    public function edit($id)
    {
        $note=QuotationNote::find(Crypt::decrypt($id));
        return view('application.quotation_notes.edit',['data'=>$note]);
    }

    public function update(ValidateNote $request, $id)
    {
        $note=QuotationNote::find(Crypt::decrypt($id));
        $status=0;
        if($request->status){
            $status=1;
        }
        $data=[
            'title'=>$request->title,
            'status'=>$status,
            'description'=>$request->description,
        ];
        $res=$note->update($data);
        if($res){
            return redirect()->back()->with('success', 'Successfully updated the data.');
        }else{
            return redirect()->back()->with('error', 'Failed to update the data. Please try again.');
        }
    }

    public function destroy($id)
    {
        $note=QuotationNote::find(Crypt::decrypt($id));
        $res=$note->delete();
        if($res){
            return response()->json(['success'=>"Data deleted successfully!"]);
        }else{
            return response()->json(['error'=>"Failed to delete the data, kindly try again!"]);
        }
    }
}
