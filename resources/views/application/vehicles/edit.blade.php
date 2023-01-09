@extends('layouts.app')
    @section('title')
        <title>TOMS | Vehicles</title>
    @endsection
    @section('css')
        <link rel="stylesheet" href="{{asset('assets/admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/admin/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/admin/plugins/toastr/toastr.min.css')}}">
    @endsection
    @section('breadcrump')
        <div class="col-sm-6">
            <h1 class="m-0">Vehicles</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{route('admin.vehicles.index')}}">Vehicles</a></li>
            <li class="breadcrumb-item active">Edit vehicle</li>
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
                        <i class="fas fa-taxi"></i>
                        Edit vehicle
                      </h3>
                    </div>
                    <div class="card-body">
                        <form action="{{route('admin.vehicles.update',$data->id)}}" method="post" id="addNewForm">@csrf
                            @method('put')
                            <div class="row">
                                <div class="col-lg-6 col-sm-6 col-md-12 col-xs-12">
                                    <x-forms.input class="form-control {{ $errors->has('vehicle') ? ' is-invalid' : '' }}" title="Vehicle" name="vehicle" id="vehicle" type="text" required="True"/>
                                </div>
                                <div class="col-lg-6 col-sm-6 col-md-12 col-xs-12">
                                    <div class="form-group">
                                        <label for="condition">Condition(AC/NAC) <span style="color:red;"> *</span></label>
                                        <select name="condition" id="condition" class="form-control" required>
                                            <option value="AC">AC</option>
                                            <option value="NAC">NAC</option>
                                        </select>
                                        @error('condition')
                                            <span class="error mt-2 text-danger" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 col-sm-6 col-md-12 col-xs-12">
                                    <x-forms.input class="form-control {{ $errors->has('seating_capacity') ? ' is-invalid' : '' }}" title="Seating capacity" name="seating_capacity" id="seating_capacity" type="number" required="False"/>
                                </div>
                                <div class="col-lg-6 col-sm-6 col-md-12 col-xs-12">
                                    <x-forms.input class="form-control {{ $errors->has('category') ? ' is-invalid' : '' }}" title="Category" name="category" id="category" type="text" required="False"/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 col-sm-6 col-md-12 col-xs-12">
                                    <x-forms.input class="form-control {{ $errors->has('kms_allowed') ? ' is-invalid' : '' }}" title="KM allowed" name="kms_allowed" id="kms_allowed" type="number" required="True"/>
                                </div>
                                <div class="col-lg-6 col-sm-6 col-md-12 col-xs-12">
                                    <x-forms.input class="form-control {{ $errors->has('rate') ? ' is-invalid' : '' }}" title="Rate" name="rate" id="rate" type="number" required="True"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="custom-control custom-switch">
                                  <input type="checkbox" class="custom-control-input" id="customSwitch1" name="status" checked>
                                  <label class="custom-control-label" for="customSwitch1">Status</label>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-info" style="float:right;">Save</button>
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
        <script>
            $(function () {
                $('#addNewForm').validate({
                    rules: {
                        vehicle: {
                            required: true
                        },
                        condition: {
                            required: true
                        },
                        kms_allowed: {
                            required: true
                        },
                        rate: {
                            required: true
                        },
                    },
                    messages: {
                        vehicle: {
                            required: "Please enter the vehicle"
                        },
                        condition: {
                            required: "Please select the condition"
                        },
                        kms_allowed: {
                            required: "Please enter kms allowed per day"
                        },
                        rate: {
                            required: "Please enter per day rate"
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

            function preFill(){
                $('#vehicle').val('{{$data->vehicle}}');
                $('#condition').val('{{$data->condition}}');
                $('#seating_capacity').val('{{$data->seating_capacity}}');
                $('#category').val('{{$data->category}}');
                $('#kms_allowed').val('{{$data->kms_allowed}}');
                $('#rate').val('{{$data->rate}}');
                var status='{{$data->status}}';
                if(status==0){
                    $("#customSwitch1").prop('checked', false);
                }else{
                    $("#customSwitch1").prop('checked', true);
                }
            }

            preFill();
        </script>
    @endsection