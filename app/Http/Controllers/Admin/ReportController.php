<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Quotation;
use App\Models\Booking;
use DataTables;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function leadsReport(Request $request)
    {
        if ($request->ajax()) {
            if(Auth::user()->can('isAdmin')){
                $data= Quotation::query()->latest('created_at');
            }else{
                $data= Quotation::query()->where('assigned_to',Auth::user()->id)->latest('created_at');
            }
            $search = $request->search;
            $fromDate=$request->from_date;
            $toDate=$request->to_date;
            if ($search) {
                $data->where(function ($query) use ($search) {
                    $query->where('package_name', 'like', '%' . $search . '%');
                });
            }
            if($fromDate && $toDate){
                $fromDate=Carbon::parse($fromDate)->format('Y-m-d');
                $toDate=Carbon::parse($toDate)->format('Y-m-d');
                $data->whereBetween('created_at', [$fromDate, $toDate]);
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
                ->escapeColumns('aaData')
                ->make(true);
        }
        return view('application.reports.leads');
    }

    public function bookingsReport(Request $request)
    {
        if ($request->ajax()) {
            $data= Booking::query()->join('quote_revisions','bookings.quote_revision_id','quote_revisions.id')->join('quotations','quote_revisions.quotation_id','quotations.id')-> select(['bookings.*','quotations.*','quote_revisions.rev_id','bookings.id as booking_id','bookings.status as book_status']);
            $status = $request->status_search;
            $fromDate=$request->from_date;
            $toDate=$request->to_date;
            if ($status) {
                $data->where('bookings.status', $status);
            }
            if($fromDate && $toDate){
                $fromDate=Carbon::parse($fromDate)->format('Y-m-d');
                $toDate=Carbon::parse($toDate)->format('Y-m-d');
                $data->whereBetween('bookings.created_at', [$fromDate, $toDate]);
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
                ->addColumn('action', 'application.bookings.action')
                ->make(true);
        }
        return view('application.reports.bookings');
    }
}
