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
                        @for ($i = 0; $i < $revision->no_nights; $i++)
                            <x-layout.destination :destinations="[$destinations]" date="{{Carbon::parse($revision->arrival_date)->addDays($i)}}" key="{{$i}}"/>
                        @endfor
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
                var sgl=document.getElementsByName('sgl_room[]');
                var dbl=document.getElementsByName('dbl_room[]');
                var ex_bed_adult=document.getElementsByName('ex_bed_adult[]');
                var ex_bed_child=document.getElementsByName('ex_bed_child[]');
                var ex_child_wout=document.getElementsByName('ex_child_wout[]');
                sgl[key].value=response.single;
                dbl[key].value=response.double;
                ex_bed_adult[key].value=response.extra_adult;
                ex_bed_child[key].value=response.extra_child_bed;
                ex_child_wout[key].value=response.extra_child_wout_bed;
            }
            
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

           

           

            
        </script>
    @endsection