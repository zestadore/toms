<?php

namespace App\Http\Controllers\Operations\Quotations;
use App\Http\Controllers\Controller;
use App\Models\Quotation;
use App\Models\QuoteRevision;
use App\Models\Agent;
use App\Models\User;
use App\Models\Destination;
use App\Models\Vehicle;
use App\Models\QuoteRevisionDetail;
use App\Models\QuotationNote;
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
            // $search = $request->search;
            // if ($search) {
            //     $data->where(function ($query) use ($search) {
            //         $query->where('package_name', 'like', '%' . $search . '%');
            //     });
            // }
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', 'application.quotations.revisions.action')
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
        $revison=QuoteRevision::join('quotations','quote_revisions.quotation_id','quotations.id')
            ->join('agents','agents.id','quotations.agent_id')
            ->select('quote_revisions.*','quotations.*','agents.company_name')->find(Crypt::decrypt($rev_id));
        $quote_id=Crypt::encrypt($revison->quotation_id);
        $destinations=Destination::where('status',1)->get();
        $vehicle=Vehicle::where('status',1)->get();
        return view('application.quotations.revisions.calculate_revision',['revision'=>$revison,'quote_id'=>$quote_id,'destinations'=>$destinations,'vehicles'=>$vehicle]);
    }

    public function saveQuoteRevisonDetails(Request $request)
    {
        $details=json_decode($request->details);
        $accomodationRate=json_decode($request->acomodation_rate);
        $transportationRate=json_decode($request->transportation_rate);
        $netRate=json_decode($request->net_rate);
        // dd($netRate);
        $detArray=[];
        foreach($details as $detail){
            $detArray[]=[
                'revision_id'=>Crypt::decrypt($netRate[0]->revision_id),
                'destination_id'=>Crypt::decrypt($detail->destination),
                'hotel_id'=>Crypt::decrypt($detail->hotel),
                'room_category_id'=>Crypt::decrypt($detail->room),
                'checkin'=>Carbon::parse($detail->checkin)->format('Y-m-d'),
                'single'=>$detail->sgl_rooms,
                'double'=>$detail->dbl_rooms,
                'extra_adult'=>$detail->ex_beds,
                'extra_child_bed'=>$detail->ex_bed_children,
                'extra_child_wout_bed'=>$detail->ex_wouts,
                'created_at'=>Now(),
                'created_by'=>Auth::user()->id
            ];
        }
        QuoteRevisionDetail::insert($detArray);
        $data=[
            'tot_sgl'=>$accomodationRate[0]->gross_sgl,
            'tot_dbl'=>$accomodationRate[0]->gross_dbl,
            'tot_ex_bed_adt'=>$accomodationRate[0]->gross_ex_bed,
            'tot_bed_chd'=>$accomodationRate[0]->gross_ex_chd_bed,
            'tot_chd_wout'=>$accomodationRate[0]->gross_wout,
            'allowed_kms'=>$transportationRate[0]->total_kms,
            'vehicle_id'=>$transportationRate[0]->vehicle_id,
            'vehicle_rate'=>$transportationRate[0]->gross_vehicle_rate,
            'hotel_addons'=>0,
            'vehicle_addons'=>0,
            'grand_total'=>$netRate[0]->accomodation_cost+$netRate[0]->transportation_cost,
            'discount_type'=>$netRate[0]->discount_type,
            'discount'=>$netRate[0]->discount,
            'discount_amount'=>$netRate[0]->discount_amount,
            'markup_type'=>$netRate[0]->markup_type,
            'markup'=>$netRate[0]->markup,
            'markup_amount'=>$netRate[0]->markup_amount,
            'gst'=>5,
            'gst_amount'=>$netRate[0]->gst_amount,
            'net_rate'=>$netRate[0]->total_net_rate,
            'status'=>1,
        ];
        $revision=QuoteRevision::find(Crypt::decrypt($netRate[0]->revision_id));
        $res=$revision->update($data);
        if($res){
            return response()->json('success');
        }
    }

    public function revisionCalculationView($rev_id)
    {
        $revison=QuoteRevision::join('quotations','quote_revisions.quotation_id','quotations.id')
            ->join('agents','agents.id','quotations.agent_id')
            ->select('quote_revisions.*','quotations.*','agents.company_name')->find(Crypt::decrypt($rev_id));
        $quote_id=Crypt::encrypt($revison->quotation_id);
        $destinations=Destination::where('status',1)->get();
        $vehicle=Vehicle::where('status',1)->get();
        $notes=QuotationNote::where('status',1)->get();
        return view('application.quotations.revisions.revision_calculation.view',['revision'=>$revison,'quote_id'=>$quote_id,'destinations'=>$destinations,'vehicles'=>$vehicle,'notes'=>$notes]);
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
