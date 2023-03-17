<?php

namespace App\Http\Controllers\Operations;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Quotation;
use App\Models\QuoteRevision;
use Illuminate\Support\Facades\Crypt;
use DataTables;
use App\Jobs\ForwardBookings;
use App\Models\BookingDetails;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data= Booking::query()->join('quote_revisions','bookings.quote_revision_id','quote_revisions.id')->join('quotations','quote_revisions.quotation_id','quotations.id')-> select(['bookings.*','quotations.*','quote_revisions.rev_id','bookings.id as booking_id']);
            $status = $request->status_search;
            
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
        return view('application.bookings.index');
    }

    public function show($id)
    {
        $id=Crypt::decrypt($id);
        $booking=Booking::find($id);
        $revision=QuoteRevision::join('quotations','quote_revisions.quotation_id','quotations.id')
            ->select(['quote_revisions.*','quotations.guest_name'])->find($booking->quote_revision_id);
        return view('application.bookings.view.index',['revision'=>$revision,'booking'=>$booking]);
    }

    public function forwardBookings($id)
    {
        ForwardBookings::dispatch($id);
        return response()->json(['success'=>"Successfully forwarded the mails to the respective hotels!"]);
    }

    public function changeGuestName(Request $request)
    {
        $id=Crypt::decrypt($request->id);
        $quote=Quotation::find($id);
        $res=$quote->update(['guest_name'=>$request->name]);
        if($res){
            return response()->json(['success'=>"Guest name updated successfully!"]);
        }else{
            return response()->json(['error'=>"Failed to update the guest name, kindly try again!"]);
        }
    }

    public function getBookingDetails($bookingId)
    {
        $details=BookingDetails::find(Crypt::decrypt($bookingId));
        return response()->json(['data'=>$details]);
    }

    public function saveBookingDetails(Request $request)
    {
        $details=BookingDetails::find(Crypt::decrypt($request->id));
        $res=$details->update($request->except(['_token','id']));
        if($res){
            return response()->json(['success'=>"Booking details updated successfully!"]);
        }else{
            return response()->json(['error'=>"Failed to update the booking details, kindly try again!"]);
        }
    }
}
