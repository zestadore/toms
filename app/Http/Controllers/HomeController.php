<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\Company;
use App\Models\Payment;
use App\Models\Quotation;
use App\Models\Availability;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function dashboard()
    {
        if(Auth::user()->role=="admin"){
            return redirect()->route('admin.dashboard');
        }elseif(Auth::user()->role=="user"){
            return view('application.dashboard',$this->userDashboard());
        }else{
            return view('application.dashboard');
        }
    }

    public function authUserProfile()
    {
        return view('application.profile.profile');
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
			'first_name' => 'required',
            'mobile'=>'required',
            'image'=>'nullable|mimes:jpeg,jpg,png|max:2048',
		]);
        $image=Null;
        $user=Auth::user();
        if($request->file('image')){
            if($user->image!=null){
                unlink(public_path('uploads/profiles/'. $user->image));
            }
            $file= $request->file('image');
            $filename= date('YmdHi').$file->getClientOriginalName();
            $file-> move(public_path('uploads/profiles'), $filename);
            $image= $filename;
        }
        $data=[
            'first_name'=>$request->first_name,
            'last_name'=>$request->last_name,
            'mobile'=>$request->mobile,
            'image'=>$image
        ];
        $res=$user->update($data);
        if($res){
            return redirect()->back()->with('success', 'Successfully updated the data.');
        }else{
            return redirect()->back()->with('error', 'Failed to update the data. Please try again.');
        }
    }

    public function changePassword()
    {
        return view('application.profile.change_password');
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

    public function getCompanyDetails()
    {
        $company=Company::find(1);
        return view('application.company_details',['data'=>$company]);
    }

    public function updateCompanyDetails(Request $request)
    {
        $request->validate([
			'company_name' => 'required',
            'contact_1'=>'required',
            'logo'=>'nullable|mimes:jpeg,jpg,png|max:2048',
            'email_id'=>'required',
            'gst_number'=>'required',
            'gst'=>'required',
		]);
        $data=Company::find(1);
        $image=$data?->logo??Null;
        if($request->file('logo')){
            if($data?->logo!=null){
                unlink(public_path('uploads/company/'. $data?->logo));
            }
            $file= $request->file('logo');
            $filename= date('YmdHi').$file->getClientOriginalName();
            $file-> move(public_path('uploads/company'), $filename);
            $image= $filename;
        }
        $dataSet=[
            'company_name'=>$request->company_name,
            'contact_1'=>$request->contact_1,
            'contact_2'=>$request->contact_2,
            'email_id'=>$request->email_id,
            'gst_number'=>$request->gst_number,
            'gst'=>$request->gst,
            'address'=>$request->address,
            'url'=>$request->url,
            'logo'=>$image
        ];
        $res=Company::updateOrCreate(['id'=>1],$dataSet);
        if($res){
            return redirect()->back()->with(['success'=>'Data updated successfully']);
        }else{
            return redirect()->back()->with(['error'=>'Failed to update the data']);
        }
    }

    private function userDashboard()
    {
        $quotations=Quotation::where('status',0)->where('assigned_to',Auth::user()->id)->count();
        $availability=Availability::where('status',0)->where('created_by',Auth::user()->id)->count();
        $bookings=Booking::where('status',0)->where('created_by',Auth::user()->id)->count();
        $payments=Payment::where('status',0)->where('created_by',Auth::user()->id)->count();
        //get total payments added this month using created_at field
        $paymentsThisMonth=Payment::where('created_by',Auth::user()->id)->where('created_at','>=',date('Y-m-d',strtotime('first day of this month')))->where('created_at','<=',date('Y-m-d',strtotime('last day of this month')))->count();
        //get total approved payments added this month using created_at field
        $approvedPaymentsThisMonth=Payment::where('created_by',Auth::user()->id)->where('status',1)->where('created_at','>=',date('Y-m-d',strtotime('first day of this month')))->where('created_at','<=',date('Y-m-d',strtotime('last day of this month')))->count();
        //get total pending payments added this month using created_at field
        $pendingPaymentsThisMonth=Payment::where('created_by',Auth::user()->id)->where('status',0)->where('created_at','>=',date('Y-m-d',strtotime('first day of this month')))->where('created_at','<=',date('Y-m-d',strtotime('last day of this month')))->count();
        //get total rejected payments added this month using created_at field
        $rejectedPaymentsThisMonth=Payment::where('created_by',Auth::user()->id)->where('status',2)->where('created_at','>=',date('Y-m-d',strtotime('first day of this month')))->where('created_at','<=',date('Y-m-d',strtotime('last day of this month')))->count();
        return ['pendingQuotationCount'=>$quotations,
            'pendingAvailabilityCount'=>$availability,
            'pendingBookingCount'=>$bookings,
            'pendingPaymentCount'=>$payments,
            'paymentsThisMonth'=>$paymentsThisMonth,
            'approvedPaymentsThisMonth'=>$approvedPaymentsThisMonth,
            'pendingPaymentsThisMonth'=>$pendingPaymentsThisMonth,
            'rejectedPaymentsThisMonth'=>$rejectedPaymentsThisMonth];
    }
}
