@extends('layouts.app')
    @section('title')
        <title>TOMS | Quote revision</title>
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
            <h1 class="m-0">Quotations</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{route('operations.quotations.index')}}">Quotations</a></li>
            <li class="breadcrumb-item"><a href="{{route('operations.quotations.show',$quote_id)}}">Quote revisions</a></li>
            <li class="breadcrumb-item active">Create transportation revision</li>
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
                        Create transportation revision
                      </h3>
                    </div>
                    <div class="card-body">
                        <form action="{{route('operations.quote-revisions.save')}}" method="post" id="addNewForm">@csrf
                            <input type="hidden" name="quote_id" id="quote_id" value="{{$quote_id}}">
                            <div class="row">
                                <div class="col-lg-6 col-sm-6 col-md-12 col-xs-12">
                                    <x-forms.input class="form-control {{ $errors->has('arrival_date') ? ' is-invalid' : '' }}" title="Arrival" name="arrival_date" id="arrival_date" type="date" required="True"/>
                                </div>
                                <div class="col-lg-6 col-sm-6 col-md-12 col-xs-12">
                                    <x-forms.input class="form-control {{ $errors->has('no_days') ? ' is-invalid' : '' }}" title="No of days" name="no_days" id="no_days" type="number" required="True"/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4 col-sm-4 col-md-12 col-xs-12">
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
                                <div class="col-lg-4 col-sm-4 col-md-12 col-xs-12">
                                    <x-forms.input class="form-control {{ $errors->has('total_kms') ? ' is-invalid' : '' }}" title="Total km" name="total_kms" id="total_kms" type="number" required="True"/>
                                </div>
                                <div class="col-lg-4 col-sm-4 col-md-12 col-xs-12">
                                    <x-forms.input class="form-control {{ $errors->has('gross_vehicle_rate') ? ' is-invalid' : '' }}" title="Vehicle rate" name="gross_vehicle_rate" id="gross_vehicle_rate" type="number" required="True"/>
                                </div>
                            </div>
                            <h3>Get net rate</h3><hr>
                            <div class="alert alert-warning alert-dismissible">
                                <div class="row">
                                    <div class="col" style="float:left;">
                                        <h5><i class="icon fas fa-rupee-sign"></i> Calculate net rate</h5>
                                    </div>
                                    <div class="col" style="float:right;">
                                        <button style="float:right;" class="btn btn-primary" type="button" id="get_total_net_rate">Get total net rate</button>
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
                            <x-forms.input class="form-control {{ $errors->has('note') ? ' is-invalid' : '' }}" title="Note" name="note" id="note" type="textarea" required="False"/>
                            <button type="button" class="btn btn-info" style="float:right;" id="submitForm">Save revision</button>
                        </form>
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
        <script src="{{asset('assets/admin/plugins/toastr/toastr.min.js')}}"></script>
        <script>
            
            $(function () {
                $('#addNewForm').validate({
                    rules: {
                        agent_id: {
                            required: true
                        },
                        assigned_to: {
                            required: true
                        },
                    },
                    messages: {
                        agent_id: {
                            required: "Please select the agent"
                        },
                        assigned_to: {
                            required: "Please select the assignee"
                        },
                    },
                    errorElement: 'span',
                    errorPlacement: function (error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                    },
                    highlight: function (element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                    },
                    unhighlight: function (element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                    }
                });
            });

            $('#arrival_date').datetimepicker({
                format: 'L'
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
                $('#transportation_cost').val($('#gross_vehicle_rate').val());
                var markupType=$('#markup_type').val();
                if(markupType==1){
                    var markUp=$('#markup').val();
                    $('#markup_amount').val(markUp);
                }else if(markupType==2){
                    var markUp=$('#markup').val();
                    calculateMarkup(markUp,$('#transportation_cost').val())
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
                var transCost=$('#transportation_cost').val();
                var markup=$('#markup_amount').val();
                var totAccomodationCost=parseFloat(transCost);
                totAccomodationCost=totAccomodationCost+parseFloat(markup);
                gstAmount=(5/100)*totAccomodationCost;
                $('#gst_amount').val(gstAmount);
                $('#total_net_rate').val(totAccomodationCost+gstAmount);
            }

            $('#submitForm').click(function(){
                var destArray=[{
                    'total_kms':$('#total_kms').val(),
                    'no_days':$('#no_days').val(),
                    'vehicle_id':$('#vehicle_id').val(),
                    'gross_vehicle_rate':$('#gross_vehicle_rate').val(),
                    'arrival_date':$('#arrival_date').val(),
                    'quotation_id':'{{$quote_id}}'
                }];
                var transportationRate=JSON.stringify(destArray);
                destArray=[{
                    'transportation_cost':$('#transportation_cost').val(),
                    'markup_type':$('#markup_type').val(),
                    'markup':$('#markup').val(),
                    'markup_amount':$('#markup_amount').val(),
                    'gst_amount':$('#gst_amount').val(),
                    'total_net_rate':$('#total_net_rate').val(),
                }];
                var netRate=JSON.stringify(destArray);
                var formData = new FormData();
                formData.append('transportation_rate', transportationRate);
                formData.append('net_rate', netRate);
                $.ajax({
                    url: '{{route("operations.transportation-revision.save")}}',
                    type:"post",
                    "headers": {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
                    contentType: false, 
                    processData: false,
                    data:formData,
                    success:function(response){
                        if(response.success){
                            var url="{{route('operations.quotations.show',$quote_id)}}";
                            window.location.href=url;
                        }
                        // $('#gross_vehicle_rate').val(response);
                    },
                });
            });

        </script>
    @endsection