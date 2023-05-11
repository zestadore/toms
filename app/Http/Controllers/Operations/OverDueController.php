<?php

namespace App\Http\Controllers\Operations;
use App\Http\Controllers\Controller;
use DataTables;
use App\Models\OverDue;
use App\Models\Quotation;
use App\Models\Payment;
use App\Models\Booking;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Request;

class OverDueController extends Controller
{
    public function index(Request $request)
    {
        $overDues=OverDue::where('status',0)->get();
        return view('application.overdues.index',['data'=>$overDues]);
    }

    public function getBookingsList($id)
    {
        $overDue=OverDue::find(Crypt::decrypt($id));
        $quotation=Quotation::find($overDue->old_booking_id);
        $agentId=$quotation->agent_id;
        $quotations=Quotation::join('bookings','bookings.quotaion_id','quotations.id')
            ->where('quotations.agent_id',$agentId)->where('bookings.status',1)->select(['quotations.*','bookings.*','bookings.id as booking_id'])->get();
        $data=[];
        foreach($quotations as $quotation){
            $amounts=getPendingPayments($quotation->booking_id,$quotation->quote_revision_id);
            $data[]=[
                'guest_name'=>$quotation->guest_name,
                'quote_revision_id'=>$quotation->quote_revision_id,
                'package_name'=>$quotation->package_name,
                'booking_id'=>$quotation->booking_id,
                'quote_id'=>$quotation->quote_id,
                'total_amount'=>$amounts['total_amount'],
                'balance'=>$amounts['balance'],
            ];
        }
        return $data;
    }

    public function applyPayment(Request $request)
    {
        $overDue=OverDue::find(Crypt::decrypt($request->overdue_id));
        $booking=Booking::find($request->booking_id);
        $balanceAmt=getPendingPayments($request->booking_id,$booking->quote_revision_id);
        $payAmount=0;
        if($overDue->balance_amount>= $balanceAmt['balance']){
            $payAmount=$balanceAmt['balance'];
        }else{
            $payAmount=$overDue->balance_amount;
        }
        if($balanceAmt['balance']>0){
            $res=Payment::create([
                'booking_id'=>$request->booking_id,
                'quotation_id'=>$booking->quotaion_id,
                'bank_id'=>$overDue->bank_id,
                'amount'=>$payAmount,
                'payment_details'=>$overDue->payment_details,
                'status'=>1
            ]);
            if($res){
                $balAmount=$overDue->amount-$payAmount;
                $overDue->update(['balance_amount'=>$balAmount,'status'=>$overDue->status]);
                return response()->json(['success'=>"Overdue used successfully!"]);
            }else{
                return response()->json(['error'=>"Failed to update the overdue, kindly try again!"]);
            }
        }else{
            return response()->json(['error'=>"You have already made complete payment for the booking, kindly recheck!"]);
        }
        
    }
}
