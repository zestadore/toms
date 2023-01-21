<?php

namespace App\Http\Controllers\Operations\Quotations;
use App\Http\Controllers\Controller;
use App\Models\Quotation;
use App\Models\QuoteRevision;
use App\Models\Agent;
use App\Models\User;
use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DataTables;
use App\Http\Requests\ValidateQuotation;
use App\Http\Requests\ValidateRevision;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;

class QuotationController extends Controller
{
    
    public function index(Request $request)
    {
        if ($request->ajax()) {
            if(Auth::user()->can('isAdmin')){
                $data= Quotation::query();
            }else{
                $data= Quotation::query()->where('assigned_to',Crypt::decrypt(Auth::user()->id));
            }
            $search = $request->search;
            if ($search) {
                $data->where(function ($query) use ($search) {
                    $query->where('package_name', 'like', '%' . $search . '%');
                });
            }
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('agent', function($data){
                    return $data->agent->company_name;
                })
                ->addColumn('assignee', function($data){
                    $html='<img src="' . $data->assignee->image_path . '" class="img-circle elevation-2" alt="User Image" width=35 height=35>';
                    $html=$html . ' ' . $data->assignee->first_name . ' ' . $data->assignee->last_name;
                    return $html;
                })
                ->addColumn('action', 'application.quotations.action')
                ->escapeColumns('aaData')
                ->make(true);
        }
        return view('application.quotations.index');
    }

    public function create()
    {
        $agents=Agent::where('status',1)->get();
        $users=User::get();
        return view('application.quotations.create',['agents'=>$agents,'users'=>$users]);
    }

    public function store(ValidateQuotation $request)
    {
        $quote_id='TRM'.Carbon::parse(Now())->format('his');
        $data=[
            'agent_id'=>Crypt::decrypt($request->agent_id),
            'assigned_to'=>$request->assigned_to,
            'note'=>$request->note,
            'package_name'=>$request->package_name,
            'guest_name'=>$request->guest_name,
            'quote_id'=>$quote_id,
            'status'=>0
        ];
        $res=Quotation::create($data);
        if($res){
            return redirect()->back()->with('success', 'Successfully updated the data.');
        }else{
            return redirect()->back()->with('error', 'Failed to update the data. Please try again.');
        }
    }

    public function show($id,Request $request)
    {
        if ($request->ajax()) {
            $data= QuoteRevision::query()->where('quotation_id',Crypt::decrypt($id));
            $search = $request->search;
            if ($search) {
                $data->where(function ($query) use ($search) {
                    $query->where('package_name', 'like', '%' . $search . '%');
                });
            }
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', 'application.quotations.action')
                ->escapeColumns('aaData')
                ->make(true);
        }
        return view('application.quotations.revisions.index',['quote_id'=>$id]);
    }

    public function createQuoteRevision($quote_id)
    {
        return view('application.quotations.revisions.create',['quote_id'=>$quote_id]);
    }

    public function saveQuoteRevison(ValidateRevision $request)
    {
        $quote_id=Crypt::decrypt($request->quote_id);
        $revId=QuoteRevision::where('quotation_id',$quote_id)->count();
        $revId+=1;
        $data=[
            'quotation_id'=>$quote_id,
            'rev_id'=>$revId,
            'arrival_date'=>Carbon::parse($request->arrival_date)->format('Y-m-d'),
            'no_nights'=>$request->no_nights,
            'adults'=>$request->adults,
            'kids'=>$request->kids??0,
            'meal_plan'=>$request->meal_plan,
            'sgl_rooms'=>$request->sgl_rooms??0,
            'dbl_rooms'=>$request->dbl_rooms??0,
            'ex_bed_adults'=>$request->ex_bed_adults??0,
            'ex_bed_children'=>$request->ex_bed_children??0,
            'ex_children_wout'=>$request->ex_children_wout??0,
            'status'=>0
        ];
        $res=QuoteRevision::create($data)->id;
        if($res){
            return redirect()->route('operations.revision.calculation',$res);
        }else{
            return redirect()->back()->with('error', 'Failed to update the data. Please try again.');
        }
    }

    public function revisionCalculation($rev_id)
    {
        $revison=QuoteRevision::find(Crypt::decrypt($rev_id));
        $quote_id=Crypt::encrypt($revison->quote_id);
        $destinations=Destination::where('status',1)->get();
        return view('application.quotations.revisions.calculate_revision',['revision'=>$revison,'quote_id'=>$quote_id,'destinations'=>$destinations]);
    }

    public function edit(Quotation $quotation)
    {
        //
    }

    public function update(Request $request, Quotation $quotation)
    {
        //
    }

    public function destroy(Quotation $quotation)
    {
        //
    }
}
