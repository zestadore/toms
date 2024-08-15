@php
    use Carbon\Carbon;
@endphp
@extends('layouts.app')
    @section('title')
        <title>TOMS | Transportation Rate</title>
    @endsection
    @section('css')
        <link rel="stylesheet" href="{{asset('assets/admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/admin/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/admin/plugins/toastr/toastr.min.css')}}">
        <meta name="csrf_token" content="{{ csrf_token() }}" />
    @endsection
    @section('breadcrump')
        <div class="col-sm-6">
            <h1 class="m-0">Transportation Rate</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                <li class="breadcrumb-item active">Transportation Rate</li>
            </ol>
        </div><!-- /.col -->
    @endsection
    @section('content')
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissable" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        <p class="mb-0">{{ session('error') }}</p>
                    </div>
                @endif
                @if (session('success'))
                    <div class="alert alert-success alert-dismissable" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        <p class="mb-0">{{ session('success') }}</p>
                    </div>
                @endif
                <div class="card card-default color-palette-box">
                    <div class="card-header">
                      <h3 class="card-title">
                        <i class="fas fa-wallet"></i>
                        Calculate Transportation Rate
                      </h3>
                    </div>
                    <div class="card-body">
                        <h3>Assign destinations & Itinerary</h3><hr>
                        <input type="date" name="arrival_date" id="arrival_date" value="{{Carbon::parse(Now())->format('Y-m-d')}}" class="form-control" required><br>
                        @for ($i = 0; $i < $nights; $i++)
                            <x-layout.vehicle :destinations="[$destinations]" date="{{Carbon::parse(Now())->addDays($i)}}" key="{{$i}}"/>
                        @endfor
                        <h3>Assign vehicle</h3><hr>
                        <div class="alert alert-warning alert-dismissible">
                            <div class="row">
                                <div class="col" style="float:left;">
                                    <h5><i class="icon fas fa-taxi"></i> Transportation rate</h5>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <x-forms.input class="form-control {{ $errors->has('total_kms') ? ' is-invalid' : '' }}" title="Total km" name="total_kms" id="total_kms" type="number" required="True"/>
                                </div>
                                <div class="col">
                                    <x-forms.input class="form-control {{ $errors->has('no_days') ? ' is-invalid' : '' }}" title="Total days" name="no_days" id="no_days" type="number" required="True"/>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="vehicle_id">Select a vehicle</label>
                                        <select name="vehicle_id" id="vehicle_id" class="form-control">
                                            <option value="">Select a vehicle</option>
                                            @foreach ($vehicles as $item)
                                                <option value="{{$item->id}}">{{$item->vehicle}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col">
                                    <x-forms.input class="form-control {{ $errors->has('gross_vehicle_rate') ? ' is-invalid' : '' }}" title="Vehicle rate" name="gross_vehicle_rate" id="gross_vehicle_rate" type="number" required="True"/>
                                </div>
                            </div>
                        </div>
                        <h3>Get net rate</h3><hr>
                        <div class="alert alert-warning alert-dismissible">
                            <div class="row">
                                <div class="col" style="float:left;">
                                    <h5><i class="icon fas fa-rupee-sign"></i> Calculate net rate</h5>
                                </div>
                                <div class="col" style="float:right;">
                                    <button style="float:right;" class="btn btn-primary" id="get_total_net_rate">Get total net rate</button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <x-forms.input class="form-control {{ $errors->has('transportation_cost') ? ' is-invalid' : '' }}" title="Transportation cost" name="transportation_cost" id="transportation_cost" type="number" required="True"/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="markup_type">Markup type</label>
                                        <select name="markup_type" id="markup_type" class="form-control">
                                            <option value="1">Value</option>
                                            <option value="2">Percent</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col">
                                    <x-forms.input class="form-control {{ $errors->has('markup') ? ' is-invalid' : '' }}" title="Markup" name="markup" id="markup" type="number" required="True"/>
                                </div>
                                <div class="col">
                                    <x-forms.input class="form-control {{ $errors->has('markup_amount') ? ' is-invalid' : '' }}" title="Markup amount" name="markup_amount" id="markup_amount" type="number" required="True"/>
                                </div>
                                <div class="col">
                                    <x-forms.input class="form-control {{ $errors->has('gst_amount') ? ' is-invalid' : '' }}" title="GST amount" name="gst_amount" id="gst_amount" type="number" required="True"/>
                                </div>
                            </div>
                            <x-forms.input class="form-control {{ $errors->has('total_net_rate') ? ' is-invalid' : '' }}" title="Total net rate" name="total_net_rate" id="total_net_rate" type="number" required="True"/>
                        </div>
                        <button class="btn btn-success" style="float:right;" id="save-revision">Save</button>
                    </div>
                    <!-- /.card-body -->
                  </div><br>
            </div><!--/. container-fluid -->
        </section>
    @endsection
    @section('scripts')
        <!-- jquery-validation -->
        <script src="{{asset('assets/admin/plugins/jquery-validation/jquery.validate.min.js')}}"></script>
        <script src="{{asset('assets/admin/plugins/jquery-validation/additional-methods.min.js')}}"></script>
        <!-- date-range-picker -->
        <script src="{{asset('assets/admin/plugins/moment/moment.min.js')}}"></script>
        <script src="{{asset('assets/admin/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')}}"></script>
        <!-- Toastr -->
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
        <script>
            $('.select-destination').change(function(){
                var destinationId=$(this).val();
                var key=$(this).data('key');
                var url="{{route('operations.hotels.list','ID')}}";
                url=url.replace('ID',destinationId);
                $.ajax({
                    url: url,
                    type:"get",
                    success:function(response){
                        if(response){
                            var dest=document.getElementsByName('hotels[]');
                            var itin=document.getElementsByName('itinerary[]');
                            let optionItin = itin[key].options;
                            optionItin.length = 0;
                            let optionsitin=response.itineraries;
                            optionItin.add(
                                new Option("Select an itinerary", "", "")
                            )
                            optionsitin.forEach(option =>
                            optionItin.add(
                                    new Option(option.title, option.id, option.selected)
                                )
                            );
                        }
                    },
                });
            });


            $('#vehicle_id').change(function(){
                calculateVehicleRate();
            });
            $('#total_kms').change(function(){
                calculateVehicleRate();
            });

            function calculateVehicleRate(){
                var total_kms=$('#total_kms').val();
                var no_days=$('#no_days').val();
                var vehicle_id=$('#vehicle_id').val();
                var url="{{route('operations.vehicle-rate.get',['TOTALKM','DAYS','VEHICLEID'])}}";
                url=url.replace('TOTALKM',total_kms);
                url=url.replace('DAYS',no_days);
                url=url.replace('VEHICLEID',vehicle_id);
                if(total_kms>0 && no_days>0 && vehicle_id!=""){
                    $.ajax({
                        url: url,
                        type:"get",
                        success:function(response){
                            $('#gross_vehicle_rate').val(response);
                        },
                    });
                }
            }

            $('#get_total_net_rate').click(function(){
                var markupType=$('#markup_type').val();
                if(markupType==1){
                    var markUp=$('#markup').val();
                    $('#markup_amount').val(markUp);
                }else if(markupType==2){
                    var markUp=$('#markup').val();
                    calculateMarkup(markUp,$('#gross_vehicle_rate').val())
                }
                calculateGST();
            });

            function calculateMarkup(markUp,totAccomodationCost){
                var markupAmount=0;
                markupAmount=(markUp/100)*totAccomodationCost;
                $('#markup_amount').val(markupAmount);
            }


            function calculateGST(){
                var gstAmount=0;
                var transCost=$('#gross_vehicle_rate').val();
                $('#transportation_cost').val(transCost);
                var markup=$('#markup_amount').val();
                var totAccomodationCost=parseFloat(transCost);
                totAccomodationCost=totAccomodationCost+parseFloat(markup);
                gstAmount=(5/100)*totAccomodationCost;
                $('#gst_amount').val(gstAmount);
                $('#total_net_rate').val(totAccomodationCost+gstAmount);
            }

            $('#arrival_date').change(function(){
                var arrivalDate = new Date($(this).val());
                for(let i = 0; i < {{$nights}}; i++){
                    var currentDate = new Date(arrivalDate);
                    currentDate.setDate(arrivalDate.getDate() + i);
                    var formattedDate = formatDate(currentDate);
                    $('#dates' + i).text(formattedDate);
                    var destinations=document.getElementsByName('destinations[]');
                    destinations[i].setAttribute('data-date',formattedDate);
                }
            });

            function formatDate(date) {
                const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", 
                                    "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
                const day = date.getDate();
                const month = monthNames[date.getMonth()];
                const year = date.getFullYear();
                return `${day}-${month}-${year}`;
            }

            $('#save-revision').click(function(){
                var destinations=document.getElementsByName('destinations[]');
                var hasValue = false;
                for (var i = 0; i < destinations.length; i++) {
                    var val=destinations[i].value;
                    if (val!="" && val!=null && val!=undefined && val!=0 && val!=false && val!=NaN) {
                        hasValue = true;
                    }else{
                        swal("Oops!", "Please select destinations and itineraries.", "error");
                        return false;
                    }
                }
                var itinerary=document.getElementsByName('itinerary[]');
                var hasValue = false;
                for (var i = 0; i < itinerary.length; i++) {
                    var val=itinerary[i].value;
                    if (val!="" && val!=null && val!=undefined && val!=0 && val!=false && val!=NaN) {
                        hasValue = true;
                    }else{
                        swal("Oops!", "Please select destinations and itineraries.", "error");
                        return false;
                    }
                }
                if (!hasValue) {
                    swal("Oops!", "Please select destinations and itineraries.", "error");
                    return false;
                }
                var arrival_date=$('#arrival_date').val();
                var no_nights='{{$nights}}';
                var total_kms=$('#total_kms').val();
                var vehicle_id=$('#vehicle_id').val();
                var gross_vehicle_rate=$('#gross_vehicle_rate').val();
                var markup_type=$('#markup_type').val();
                var markup=$('#markup').val();
                var markup_amount=$('#markup_amount').val();
                var gst_amount=$('#gst_amount').val();
                var total_net_rate=$('#total_net_rate').val();
                var destArray=[];
                for(i=0;i<destinations.length;i++){
                    destArray[i]=({
                        'destination':destinations[i].value,
                        'itinerary':itinerary[i].value,
                        'checkin':destinations[i].getAttribute("data-date")
                    });
                }
                details=JSON.stringify(destArray);
                destArray=[];
                destArray=[{
                    'arrival_date':arrival_date,
                    'no_nights':no_nights,
                    'total_kms':total_kms,
                    'vehicle_id':vehicle_id,
                    'gross_vehicle_rate':gross_vehicle_rate,
                    'markup_type':markup_type,
                    'markup':markup,
                    'markup_amount':markup_amount,
                    'gst_amount':gst_amount,
                    'total_net_rate':total_net_rate
                }];
                var transportationRate=JSON.stringify(destArray);
                var formData = new FormData();
                formData.append('details', details);
                formData.append('rates', transportationRate);
                if(total_net_rate>0 && total_net_rate!=""){
                    $.ajax({
                        url: '{{route("operations.vehicle-quotations.store")}}',
                        type:"post",
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
                        processData: false,
                        contentType: false,
                        data:formData,
                        success:function(response){
                            if(response){
                                var id=response.id;
                                var url="{{route('operations.vehicle-quotations.edit','ID')}}";
                                url=url.replace('ID',id);
                                window.location.href = url;
                            }
                        },
                    });
                }else{
                    swal("Oops!", "Please calculate total rate first!", "error");
                }
                
            });

            $("#no_days").prop("readonly", true);
            $("#no_days").val('{{$nights+1}}');
            $('#total_kms').val(0);
            $('#markup_amount').val(0);
            $('#markup').val(0);
            
        </script>
    @endsection