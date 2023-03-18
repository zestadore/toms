<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Bank;
use Illuminate\Http\Request;
use App\Http\Requests\ValidateBank;
use DataTables;
use Illuminate\Support\Facades\Crypt;

class BankController extends Controller
{
    
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data= Bank::query();
            $search = $request->search;
            if ($search) {
                $data->where(function ($query) use ($search) {
                    $query->where('bank_name', 'like', '%' . $search . '%');
                });
            }
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', 'application.banks.action')
                ->make(true);
        }
        return view('application.banks.index');
    }

    public function create()
    {
        return view('application.banks.create');
    }

    public function store(ValidateBank $request)
    {
        $status=0;
        if($request->status){
            $status=1;
        }
        $data=[
            'bank_name'=>$request->bank_name,
            'status'=>$status,
            'branch'=>$request->branch,
            'address'=>$request->address,
            'account_name'=>$request->account_name,
            'account_number'=>$request->account_number,
            'ifsc'=>$request->ifsc,
        ];
        $res=Bank::create($data);
        if($res){
            return redirect()->back()->with('success', 'Successfully updated the data.');
        }else{
            return redirect()->back()->with('error', 'Failed to update the data. Please try again.');
        }
    }

    public function show(Bank $bank)
    {
        //
    }

    public function edit($id)
    {
        $bank=Bank::find(Crypt::decrypt($id));
        return view('application.banks.edit',['data'=>$bank]);
    }

    public function update(ValidateBank $request, $id)
    {
        $bank=Bank::find(Crypt::decrypt($id));
        $status=0;
        if($request->status){
            $status=1;
        }
        $data=[
            'bank_name'=>$request->bank_name,
            'status'=>$status,
            'branch'=>$request->branch,
            'address'=>$request->address,
            'account_name'=>$request->account_name,
            'account_number'=>$request->account_number,
            'ifsc'=>$request->ifsc,
        ];
        $res=$bank->update($data);
        if($res){
            return redirect()->back()->with('success', 'Successfully updated the data.');
        }else{
            return redirect()->back()->with('error', 'Failed to update the data. Please try again.');
        }
    }

    public function destroy($id)
    {
        $bank=Bank::find(Crypt::decrypt($id));
        $res=$bank->delete();
        if($res){
            return response()->json(['success'=>"Data deleted successfully!"]);
        }else{
            return response()->json(['error'=>"Failed to delete the data, kindly try again!"]);
        }
    }
}
