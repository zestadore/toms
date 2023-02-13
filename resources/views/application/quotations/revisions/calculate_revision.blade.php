@php
    use Carbon\Carbon;
@endphp
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
            <li class="breadcrumb-item active">Calculate revision</li>
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
                        Calculate revision
                      </h3>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info alert-dismissible">
                            <h5><i class="icon fas fa-star"></i> {{$revision->company_name}} | {{$revision->guest_name}}</h5>
                            <div class="row">
                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                    No of pax : {{$revision->adults}} adults + {{$revision->kids}} children
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                    No of rooms : {{$revision->sgl_rooms}} sgls + {{$revision->dbl_rooms}} dbls <br>
                                    Ex beds : {{$revision->ex_bed_adults}} adults + {{$revision->ex_bed_children}} children<br>
                                    W/Out beds : {{$revision->ex_children_wout}} 
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                    Date of arrival : {{Carbon::parse($revision->arrival_date)->format('d-M-Y')}}<br>
                                    Meal plan : {{$revision->meal_plan}}<br>
                                </div>
                            </div>
                        </div>
                        <h3>Assign destinations & hotels/resorts</h3><hr>
                        @for ($i = 0; $i < $revision->no_nights; $i++)
                            <x-layout.destination :destinations="[$destinations]" date="{{Carbon::parse($revision->arrival_date)->addDays($i)}}" key="{{$i}}"/>
                        @endfor
                        <div class="alert alert-warning alert-dismissible">
                            <div class="row">
                                <div class="col" style="float:left;">
                                    <h5><i class="icon fas fa-umbrella-beach"></i> Accomodation rate</h5>
                                </div>
                                <div class="col" style="float:right;">
                                    <button style="float:right;" class="btn btn-primary" id="get_total_rate">Get total rate</button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <x-forms.input class="form-control {{ $errors->has('gross_sgl') ? ' is-invalid' : '' }}" title="Sgl" name="gross_sgl" id="gross_sgl" type="number" required="True"/>
                                </div>
                                <div class="col">
                                    <x-forms.input class="form-control {{ $errors->has('gross_dbl') ? ' is-invalid' : '' }}" title="Dbl" name="gross_dbl" id="gross_dbl" type="number" required="True"/>
                                </div>
                                <div class="col">
                                    <x-forms.input class="form-control {{ $errors->has('gross_ex_bed') ? ' is-invalid' : '' }}" title="Ex Bed" name="gross_ex_bed" id="gross_ex_bed" type="number" required="True"/>
                                </div>
                                <div class="col">
                                    <x-forms.input class="form-control {{ $errors->has('gross_ex_chd_bed') ? ' is-invalid' : '' }}" title="Ex bed(Child)" name="gross_ex_chd_bed" id="gross_ex_chd_bed" type="number" required="True"/>
                                </div>
                                <div class="col">
                                    <x-forms.input class="form-control {{ $errors->has('gross_wout') ? ' is-invalid' : '' }}" title="Ex child w/out" name="gross_wout" id="gross_wout" type="number" required="True"/>
                                </div>
                            </div>
                        </div>
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
                                    <x-forms.input class="form-control {{ $errors->has('accomodation_cost') ? ' is-invalid' : '' }}" title="Accomodation cost" name="accomodation_cost" id="accomodation_cost" type="number" required="True"/>
                                </div>
                                <div class="col">
                                    <x-forms.input class="form-control {{ $errors->has('transportation_cost') ? ' is-invalid' : '' }}" title="Transportation cost" name="transportation_cost" id="transportation_cost" type="number" required="True"/>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="discount_type">Discount type</label>
                                        <select name="discount_type" id="discount_type" class="form-control">
                                            <option value="0">No discount</option>
                                            <option value="1">Value</option>
                                            <option value="2">Percent</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col">
                                    <x-forms.input class="form-control {{ $errors->has('discount') ? ' is-invalid' : '' }}" title="Discount" name="discount" id="discount" type="number" required="True"/>
                                </div>
                                <div class="col">
                                    <x-forms.input class="form-control {{ $errors->has('discount_amount') ? ' is-invalid' : '' }}" title="Discount amount" name="discount_amount" id="discount_amount" type="number" required="True"/>
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
        <script src="{{asset('assets/admin/plugins/toastr/toastr.min.js')}}"></script>
        <script>
            var no_sgl=parseInt("{{$revision->sgl_rooms}}");
            var no_dbl=parseInt("{{$revision->dbl_rooms}}");
            var  no_ex_bed_adts=parseInt("{{$revision->ex_bed_adults}}");
            var  no_ex_bed_child=parseInt("{{$revision->ex_bed_children}}");
            var  no_ex_wout=parseInt("{{$revision->ex_children_wout}}");
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
                            let optionList = dest[key].options;
                            optionList.length = 0;
                            let options=response;
                            optionList.add(
                                new Option("Select your hotel", "", "")
                            )
                            options.forEach(option =>
                                optionList.add(
                                    new Option(option.hotel, option.id, option.selected)
                                )
                            );
                        }
                    },
                });
            });

            $('.select-hotel').change(function(){
                var hotelId=$(this).val();
                var key=$(this).data('key');
                var date=$(this).data('date');
                var url="{{route('operations.packages.list',['ID','DATE'])}}";
                url=url.replace('ID',hotelId);
                url=url.replace('DATE',date);
                $.ajax({
                    url: url,
                    type:"get",
                    success:function(response){
                        if(response){
                            var pack=document.getElementsByName('packages[]');
                            let optionList = pack[key].options;
                            optionList.length = 0;
                            let options=response;
                            optionList.add(
                                new Option("Select your package", "", "")
                            )
                            options.forEach(option =>
                                optionList.add(
                                    new Option(option.package, option.id, option.selected)
                                )
                            );
                        }else{
                            let optionList = $('#packages')[0].options;
                            optionList.length = 0;
                            optionList.add(
                                new Option("Select your package", "", "")
                            )
                        }
                    },
                });
            });

            $('.select-package').change(function(){
                var hotels=document.getElementsByName('hotels[]')
                var key=$(this).data('key');
                var hotelId=hotels[key].value;
                var url="{{route('operations.rooms.list','ID')}}";
                url=url.replace('ID',hotelId);
                $.ajax({
                    url: url,
                    type:"get",
                    success:function(response){
                        if(response){
                            var rooms=document.getElementsByName('rooms[]');
                            let optionList = rooms[key].options;
                            optionList.length = 0;
                            let options=response;
                            optionList.add(
                                new Option("Select your room", "", "")
                            )
                            options.forEach(option =>
                                optionList.add(
                                    new Option(option.room_category, option.id, option.selected)
                                )
                            );
                        }else{
                            let optionList = $('#rooms')[0].options;
                            optionList.length = 0;
                            optionList.add(
                                new Option("Select your room", "", "")
                            )
                        }
                    },
                });
            });

            $('.select-room').change(function(){
                var roomId=$(this).val();
                var key=$(this).data('key');
                var pack=document.getElementsByName('packages[]');
                var packageId=pack[key].value;
                var date=$(this).data('date');
                getRateswithRoom(roomId,packageId,date,key);
            });
            $('.meal_plan').change(function(){
                var key=$(this).data('key');
                var room=document.getElementsByName('rooms[]');
                var roomId=room[key].value;
                var pack=document.getElementsByName('packages[]');
                var packageId=pack[key].value;
                var date=$(this).data('date');
                if(packageId && roomId){
                    getRateswithRoom(roomId,packageId,date,key);
                }
            });

            function getRateswithRoom(roomId,packageId,date,key){
                var url="{{route('operations.rates.list',['ROOMID','PACKAGEID','DATE'])}}";
                url=url.replace('ROOMID',roomId);
                url=url.replace('PACKAGEID',packageId);
                url=url.replace('DATE',date);
                $.ajax({
                    url: url,
                    type:"get",
                    success:function(response){
                        preFillForm(response,key);
                    },
                });
            }

            function preFillForm(response,key){
                var sgl_rate=parseFloat(response.single);
                var dbl_rate=parseFloat(response.double);
                var ex_bed_adt_rate=parseFloat(response.extra_adult);
                var ex_bed_chd_rate=parseFloat(response.extra_child_bed);
                var ex_wout_bed=parseFloat(response.extra_child_wout_bed);
                var brk=parseFloat(response.breakfast);
                var chd_brk=parseFloat(response.child_breakfast);
                var lun=parseFloat(response.lunch);
                var chd_lun=parseFloat(response.child_lunch);
                var din=parseFloat(response.dinner);
                var chd_din=parseFloat(response.child_dinner);
                calculateRoomRates(sgl_rate,dbl_rate,ex_bed_adt_rate,ex_bed_chd_rate,ex_wout_bed,brk,chd_brk,lun,chd_lun,din,chd_din,key);
                var string="(";
                string=string+"Sgl : "+response.single+"/ Dbl : "+response.double+"/ ex bed(adult) : "+response.extra_adult+"/ ex bed(child) : ";
                string=string+response.extra_child_bed+"/ Child W/out :"+response.extra_child_wout_bed;
                string=string+"(Brk : "+response.breakfast+"|"+response.child_breakfast+"/ Lun : "+response.lunch+"|"+response.child_lunch+"/ Din : ";
                string=string+response.dinner+"|"+response.child_dinner+"))";
                $('#directRates'+key).html(string);
            }

            function calculateRoomRates(sgl,dbl,ex_bed,ex_bed_chd,ex_wout,brk,chd_brk,lun,chd_lun,din,chd_din,key){
                var sgl_rate=sgl*no_sgl;
                var dbl_rate=dbl*no_dbl;
                var ex_adt_rate= no_ex_bed_adts*ex_bed;
                var ex_bed_chd_rate= no_ex_bed_child*ex_bed_chd;
                var ex_wout_rate= no_ex_wout*ex_wout;
                sgl_rate=sgl_rate+(brk*no_sgl);
                dbl_rate=dbl_rate+(brk*(no_dbl*2));
                ex_adt_rate=ex_adt_rate+(brk*no_ex_bed_adts);
                ex_bed_chd_rate=ex_bed_chd_rate+(chd_brk*no_ex_bed_child);
                ex_wout_rate=ex_wout_rate+(chd_brk*no_ex_wout);
                var meals=document.getElementsByName('meal_plan[]');
                var meal_plan=meals[key].value;
                if(meal_plan=="MAP"){
                    sgl_rate=sgl_rate+(din*no_sgl);
                    dbl_rate=dbl_rate+(din*(no_dbl*2));
                    ex_adt_rate=ex_adt_rate+(din*no_ex_bed_adts);
                    ex_bed_chd_rate=ex_bed_chd_rate+(chd_din*no_ex_bed_child);
                    ex_wout_rate=ex_wout_rate+(chd_din*no_ex_wout);
                }else if(meal_plan=="AP"){
                    sgl_rate=sgl_rate+(din*no_sgl)+(lun*no_sgl);
                    dbl_rate=dbl_rate+(din*(no_dbl*2))+(lun*(no_dbl*2));
                    ex_adt_rate=ex_adt_rate+(din*no_ex_bed_adts)+(lun*no_ex_bed_adts);
                    ex_bed_chd_rate=ex_bed_chd_rate+(chd_din*no_ex_bed_child)+(chd_lun*no_ex_bed_child);
                    ex_wout_rate=ex_wout_rate+(chd_din*no_ex_wout)+(chd_lun*no_ex_wout);
                }
                var sgl=document.getElementsByName('sgl_room[]');
                var dbl=document.getElementsByName('dbl_room[]');
                var ex_bed_adult=document.getElementsByName('ex_bed_adult[]');
                var ex_bed_child=document.getElementsByName('ex_bed_child[]');
                var ex_child_wout=document.getElementsByName('ex_child_wout[]');
                sgl[key].value=sgl_rate;
                dbl[key].value=dbl_rate;
                ex_bed_adult[key].value=ex_adt_rate;
                ex_bed_child[key].value=ex_bed_chd_rate;
                ex_child_wout[key].value=ex_wout_rate;
            }

            $('#get_total_rate').click(function(){
                var sgl=document.getElementsByName('sgl_room[]');
                var dbl=document.getElementsByName('dbl_room[]');
                var ex_bed=document.getElementsByName('ex_bed_adult[]');
                var ex_bed_chd=document.getElementsByName('ex_bed_child[]');
                var ex_wout=document.getElementsByName('ex_child_wout[]');
                var sgl_rate=0;
                var dbl_rate=0;
                var ex_adt_rate=0;
                var ex_bed_chd_rate= 0;
                var ex_wout_rate= 0;
                for(i=0;i<sgl.length;i++){
                    sgl_rate=parseFloat(sgl_rate)+parseFloat(sgl[i].value);
                    dbl_rate=parseFloat(dbl_rate)+parseFloat(dbl[i].value);
                    ex_adt_rate=parseFloat(ex_adt_rate)+parseFloat(ex_bed[i].value);
                    ex_bed_chd_rate=parseFloat(ex_bed_chd_rate)+parseFloat(ex_bed_chd[i].value);
                    ex_wout_rate=parseFloat(ex_wout_rate)+parseFloat(ex_wout[i].value);
                }
                $('#gross_sgl').val(sgl_rate);
                $('#gross_dbl').val(dbl_rate);
                $('#gross_ex_bed').val(ex_adt_rate);
                $('#gross_ex_chd_bed').val(ex_bed_chd_rate);
                $('#gross_wout').val(ex_wout_rate);
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
                var totAccomodationCost=0;
                totAccomodationCost=totAccomodationCost+parseFloat($('#gross_sgl').val());
                totAccomodationCost=totAccomodationCost+parseFloat($('#gross_dbl').val());
                totAccomodationCost=totAccomodationCost+parseFloat($('#gross_ex_bed').val());
                totAccomodationCost=totAccomodationCost+parseFloat($('#gross_ex_chd_bed').val());
                totAccomodationCost=totAccomodationCost+parseFloat($('#gross_wout').val());
                $('#accomodation_cost').val(totAccomodationCost);
                $('#transportation_cost').val($('#gross_vehicle_rate').val());
                var discountType=$('#discount_type').val();
                if(discountType==0){
                    $('#discount').val(0);
                    $('#discount_amount').val(0);
                }else if(discountType==2){
                    var discount=$('#discount').val();
                    calculateDiscount(discount,totAccomodationCost)
                }else if(discountType==1){
                    var discount=$('#discount').val();
                    $('#discount_amount').val(discount);
                }
                var markupType=$('#markup_type').val();
                if(markupType==1){
                    var markUp=$('#markup').val();
                    $('#markup_amount').val(markUp);
                }else if(markupType==2){
                    var markUp=$('#markup').val();
                    calculateMarkup(markUp,totAccomodationCost)
                }
                calculateGST();
            });

            function calculateDiscount(discount,totAccomodationCost){
                var discountAmount=0;
                discountAmount=(discount/100)*totAccomodationCost;
                $('#discount_amount').val(discountAmount);
            }

            function calculateMarkup(markUp,totAccomodationCost){
                var markupAmount=0;
                markupAmount=(markUp/100)*totAccomodationCost;
                $('#markup_amount').val(markupAmount);
            }


            function calculateGST(){
                var gstAmount=0;
                var accCost=$('#accomodation_cost').val();
                var transCost=$('#transportation_cost').val();
                var discount=$('#discount_amount').val();
                var markup=$('#markup_amount').val();
                var totAccomodationCost=parseFloat(accCost)+parseFloat(transCost);
                totAccomodationCost=totAccomodationCost+parseFloat(markup);
                totAccomodationCost=totAccomodationCost-parseFloat(discount);
                gstAmount=(5/100)*totAccomodationCost;
                $('#gst_amount').val(gstAmount);
                $('#total_net_rate').val(totAccomodationCost+gstAmount);
            }

            $('#save-revision').click(function(){
                var destinations = document.getElementsByName('destinations[]');
                var hotels=document.getElementsByName('hotels[]');
                var rooms=document.getElementsByName('rooms[]');
                var meals=document.getElementsByName('meal_plan[]');
                var sgl_rooms=document.getElementsByName('sgl_room[]');
                var dbl_rooms=document.getElementsByName('dbl_room[]');
                var ex_beds=document.getElementsByName('ex_bed_adult[]');
                var ex_bed_children=document.getElementsByName('ex_bed_child[]');
                var ex_wouts=document.getElementsByName('ex_child_wout[]');
                var destArray=[];
                for(i=0;i<destinations.length;i++){
                    destArray[i]=({
                        'destination':destinations[i].value,
                        'hotel':hotels[i].value,
                        'room':rooms[i].value,
                        'meals':meals[i].value,
                        'sgl_rooms':sgl_rooms[i].value,
                        'dbl_rooms':dbl_rooms[i].value,
                        'ex_beds':ex_beds[i].value,
                        'ex_bed_children':ex_bed_children[i].value,
                        'ex_wouts':ex_wouts[i].value,
                        'checkin':destinations[i].getAttribute("data-date")
                    });
                }
                details=JSON.stringify(destArray);
                destArray=[];
                destArray=[{
                    'gross_sgl':$('#gross_sgl').val(),
                    'gross_dbl':$('#gross_dbl').val(),
                    'gross_ex_bed':$('#gross_ex_bed').val(),
                    'gross_ex_chd_bed':$('#gross_ex_chd_bed').val(),
                    'gross_wout':$('#gross_wout').val()
                }];
                var accomodationRate=JSON.stringify(destArray);
                destArray=[];
                destArray=[{
                    'total_kms':$('#total_kms').val(),
                    'no_days':$('#no_days').val(),
                    'vehicle_id':$('#vehicle_id').val(),
                    'gross_vehicle_rate':$('#gross_vehicle_rate').val(),
                }];
                var transportationRate=JSON.stringify(destArray);
                destArray=[];
                destArray=[{
                    'accomodation_cost':$('#accomodation_cost').val(),
                    'transportation_cost':$('#transportation_cost').val(),
                    'discount_type':$('#discount_type').val(),
                    'discount':$('#discount').val(),
                    'discount_amount':$('#discount_amount').val(),
                    'markup_type':$('#markup_type').val(),
                    'markup':$('#markup').val(),
                    'markup_amount':$('#markup_amount').val(),
                    'gst_amount':$('#gst_amount').val(),
                    'total_net_rate':$('#total_net_rate').val(),
                    'revision_id':'{{$revisionId}}'
                }];
                var netRate=JSON.stringify(destArray);
                var formData = new FormData();
                formData.append('details', details);
                formData.append('acomodation_rate', accomodationRate);
                formData.append('transportation_rate', transportationRate);
                formData.append('net_rate', netRate);
                $.ajax({
                    url: '{{route("operations.quote-revisions-details.save")}}',
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
            
            $('.meal_plan').val('{{$revision->meal_plan}}').trigger('change');
            $('#no_days').val(parseInt('{{$revision->no_nights}}')+1);
            $("#no_days").prop("readonly", true);
            $('#total_kms').val(0);
            $('#discount_amount').val(0);
            $('#markup_amount').val(0);
            $('#markup').val(0);
            $('#discount').val(0);
            
        </script>
    @endsection