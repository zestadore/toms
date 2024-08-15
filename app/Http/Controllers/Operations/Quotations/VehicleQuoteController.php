<?php

namespace App\Http\Controllers\Operations\Quotations;
use App\Http\Controllers\Controller;
use App\Models\VehicleQuote;
use App\Models\VehicleQuoteDetails;
use App\Models\Destination;
use App\Models\Vehicle;
use App\Models\QuotationNote;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use DataTables;

class VehicleQuoteController extends Controller
{
    
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data= VehicleQuote::query();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('duration', function($data){
                    return $data->nights . ' nights / ' . $data->nights + 1 . ' days';
                })
                ->addColumn('arrival_date', function($data){
                    return Carbon::parse($data->arrival_date)->format('d-M-Y');
                })
                ->addColumn('departure_date', function($data){
                    return Carbon::parse($data->departure_date)->format('d-M-Y');
                })
                ->addColumn('action', 'application.quotations.vehicle_quotes.action')
                ->make(true);
        }
        return view('application.quotations.vehicle_quotes.index');
    }

    public function create(Request $request)
    {
        $destinations=Destination::where('status',1)->get();
        $vehicles=Vehicle::where('status',1)->get();
        return view('application.quotations.vehicle_quotes.create',['nights'=>intval($request->nights),'destinations'=>$destinations,'vehicles'=>$vehicles]);
    }

    public function store(Request $request)
    {
        $details=json_decode($request->details);
        $rates=json_decode($request->rates);
        $rates=$rates[0];
        $arrivalDate=Carbon::parse($rates->arrival_date);
        $vehicle=Vehicle::find(Crypt::decrypt($rates->vehicle_id));
        $data=[
            'nights'=>intval($rates->no_nights),
            'unique_id'=>"VQ".date('is')."-".rand(100,999),
            'vehicle_id'=>Crypt::decrypt($rates->vehicle_id),
            'arrival_date'=>Carbon::parse($rates->arrival_date),
            'departure_date'=>$arrivalDate->addDays(intval($rates->no_nights)),
            'vehicle_rate'=>$vehicle->rate,
            'kms_per_day'=>$vehicle->kms_allowed,
            'kms_blocked'=>$rates->total_kms,
            'add_kms_rate'=>$this->calculateAddKMRate($vehicle,$rates->total_kms,(intval($rates->no_nights)+1)),
            'gross_rate'=>$rates->gross_vehicle_rate,
            'gst_percentage'=>5,
            'gst_amount'=>$rates->gst_amount,
            'net_rate'=>$rates->total_net_rate
        ];
        $res=VehicleQuote::create($data)->id;
        $data = [];
        if($res){
            for($i=0;$i<intval($rates->no_nights);$i++){
                $data[]=[
                    'vehicle_quote_id'=>$res,
                    'destination_id'=>Crypt::decrypt($details[$i]->destination),
                    'itinerary_id'=>Crypt::decrypt($details[$i]->itinerary),
                    'date'=>Carbon::parse($details[$i]->checkin)->format('Y-m-d'),
                ];
                // VehicleQuote::create($data);
            }
            $result=VehicleQuoteDetails::insert($data);
            if($result){
                return response()->json(['success'=>true,'message'=>"Data saved successfully!",'id'=>Crypt::encrypt($res)]);
            }else{
                return response()->json(['error'=>"Failed to save the data, kindly try again!"]);
            }
        }else{
            return response()->json(['error'=>"Failed to save the data, kindly try again!"]);
        }
    }

    public function show($id)
    {
        $quote=VehicleQuote::find(Crypt::decrypt($id));
        $notes=QuotationNote::where('status',1)->get();
        return view('application.quotations.vehicle_quotes.view',['quote'=>$quote,'notes'=>$notes]);
    }

    public function edit(VehicleQuote $vehicleQuote)
    {
        //
    }

    public function update(Request $request, VehicleQuote $vehicleQuote)
    {
        //
    }

    public function destroy(VehicleQuote $vehicleQuote)
    {
        //
    }

    private function calculateAddKMRate($vehicle,$totKm,$days)
    {
        $kms=$vehicle->kms_allowed*$days;
        $exKms=$totKm-$kms;
        $exKmsRate=0;
        if($exKms>0){
            $exKmsRate=$exKms*$vehicle->add_km_rate;
        }
        return $exKmsRate;
    }
}
