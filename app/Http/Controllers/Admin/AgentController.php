<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Agent;
use Illuminate\Http\Request;
use DataTables;
use App\Http\Requests\ValidateAgent;
use Illuminate\Support\Facades\Crypt;

class AgentController extends Controller
{
    
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data= Agent::query();
            $search = $request->search;
            if ($search) {
                $data->where(function ($query) use ($search) {
                    $query->where('company_name', 'like', '%' . $search . '%');
                });
            }
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', 'application.agents.action')
                ->make(true);
        }
        return view('application.agents.index');
    }

    public function create()
    {
        return view('application.agents.create');
    }

    public function store(ValidateAgent $request)
    {
        $status=0;
        if($request->status){
            $status=1;
        }
        $data=[
            'company_name'=>$request->company_name,
            'status'=>$status,
            'email'=>$request->email,
            'address'=>$request->address,
            'state'=>$request->state,
            'contact'=>$request->contact,
            'website'=>$request->website,
            'contact_person'=>$request->contact_person,
            'person_contact'=>$request->person_contact,
            'person_email'=>$request->person_email,
        ];
        $res=Agent::create($data);
        if($res){
            return redirect()->back()->with('success', 'Successfully updated the data.');
        }else{
            return redirect()->back()->with('error', 'Failed to update the data. Please try again.');
        }
    }

    public function show(Agent $agent)
    {
        //
    }

    public function edit($id)
    {
        $agent=Agent::find(Crypt::decrypt($id));
        return view('application.agents.edit',['data'=>$agent]);
    }

    public function update(ValidateAgent $request, $id)
    {
        $agent=Agent::find(Crypt::decrypt($id));
        $status=0;
        if($request->status){
            $status=1;
        }
        $data=[
            'company_name'=>$request->company_name,
            'status'=>$status,
            'email'=>$request->email,
            'address'=>$request->address,
            'state'=>$request->state,
            'contact'=>$request->contact,
            'website'=>$request->website,
            'contact_person'=>$request->contact_person,
            'person_contact'=>$request->person_contact,
            'person_email'=>$request->person_email,
        ];
        $res=$agent->update($data);
        if($res){
            return redirect()->back()->with('success', 'Successfully updated the data.');
        }else{
            return redirect()->back()->with('error', 'Failed to update the data. Please try again.');
        }
    }

    public function destroy($id)
    {
        $agent=Agent::find(Crypt::decrypt($id));
        $res=$agent->delete();
        if($res){
            return response()->json(['success'=>"Data deleted successfully!"]);
        }else{
            return response()->json(['error'=>"Failed to delete the data, kindly try again!"]);
        }
    }
}
