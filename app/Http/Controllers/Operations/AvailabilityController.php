<?php

namespace App\Http\Controllers\Operations;
use App\Http\Controllers\Controller;
use App\Models\Availability;
use App\Models\QuoteRevision;
use App\Models\QuotationNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use DataTables;

class AvailabilityController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data= Availability::query()->join('quote_revisions','availabilities.quote_revision_id','quote_revisions.id')->join('quotations','quote_revisions.quotation_id','quotations.id')-> select(['availabilities.*','quotations.*','quote_revisions.rev_id']);
            $status = $request->status_search;
            if ($status!=null) {
                $data->where(function ($query) use ($status) {
                    $query->where('availabilities.status', $status);
                });
            }
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('quote_revision', function($data) {
                    if($data->quote_revision){
                        return $data->quote_revision;
                    }else{
                        return Null;
                    }
                })
                ->addColumn('action', 'application.availabilities.action')
                ->make(true);
        }
        return view('application.availabilities.index');
    }

    public function askAvailability(Request $request)
    {
        $data=[
            'quote_revision_id'=>Crypt::decrypt($request->revision_id),
            'primary_note'=>$request->primary_note,
            'status'=>0
        ];
        $availability=Availability::where('quote_revision_id',Crypt::decrypt($request->revision_id))->first();
        if($availability){
            return response()->json(['error'=>"You have already request for availability!"]);
        }
        $res=Availability::create($data);
        $revision=QuoteRevision::find(Crypt::decrypt($request->revision_id));
        $revision->update(['status'=>4]);
        if($res){
            return response()->json(['success'=>"Data updated successfully!"]);
        }else{
            return response()->json(['error'=>"Failed to update the data, kindly try again!"]);
        }
    }

    public function reportAvailability(Request $request)
    {
        $data=[
            'availability_note'=>$request->availability_note,
            'status'=>1
        ];
        $availability=Availability::where('quote_revision_id',Crypt::decrypt($request->revision_id))->first();
        $res=$availability->update($data);
        if($res){
            return response()->json(['success'=>"Data updated successfully!"]);
        }else{
            return response()->json(['error'=>"Failed to update the data, kindly try again!"]);
        }
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
       $id=Crypt::decrypt($id);
       $availability=Availability::find($id);
       $revision=QuoteRevision::find($availability->quote_revision_id);
       $notes=QuotationNote::where('status',1)->get();
       return view('application.availabilities.view',['revision'=>$revision,'availability'=>$availability,'notes'=>$notes]);
    }

    public function edit(Availability $availability)
    {
        //
    }

    public function update(Request $request, Availability $availability)
    {
        //
    }

    public function destroy(Availability $availability)
    {
        //
    }
}
