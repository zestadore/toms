<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Quotation;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function dashboard()
    {
        $quotations=Quotation::where('status',0)->count();
        return view('application.dashboard',['pendingQuotationCount'=>$quotations]);
    }

    public function changePassword()
    {
        return view('admin.change_password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:8|confirmed',
        ]);
        $res=Auth::user()->update(['password'=>Hash::make($request->password)]);
        if($res){
            return redirect()->back()->with(['success'=>'Password updated successfully']);
        }else{
            return redirect()->back()->with(['error'=>'Failed to update the password']);
        }
    }

    public function getPendingpayments()
    {
        $payments=Payment::where('status',0)->get();
        return view('application.payments.index',['data'=>$payments]);
    }

    public function approvePayment(Request $request)
    {
        $payment=Payment::find(Crypt::decrypt($request->id));
        $res=$payment->update(['status'=>1]);
        if($res){
            return response()->json(['success'=>"Payment approved successfully!"]);
        }else{
            return response()->json(['error'=>"Failed to approve the payment, kindly try again!"]);
        }
    }

    public function rejectPayment(Request $request)
    {
        $payment=Payment::find(Crypt::decrypt($request->id));
        $res=$payment->update(['status'=>2]);
        if($res){
            return response()->json(['success'=>"Payment rejected successfully!"]);
        }else{
            return response()->json(['error'=>"Failed to reject the payment, kindly try again!"]);
        }
    }
}
