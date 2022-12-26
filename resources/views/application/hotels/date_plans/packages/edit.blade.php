@extends('layouts.app')
    @section('title')
        <title>TOMS | Packages</title>
    @endsection
    @section('css')
        <link rel="stylesheet" href="{{asset('assets/admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/admin/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/admin/plugins/toastr/toastr.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/admin/plugins/select2/css/select2.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
    @endsection
    @section('breadcrump')
        <div class="col-sm-6">
            <h1 class="m-0">Packages</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{route('admin.hotels.index')}}">Hotels</a></li>
            <li class="breadcrumb-item"><a href="{{route('admin.hotels.date-plans.index',$hotel_id)}}">Date plans</a></li>
            <li class="breadcrumb-item"><a href="{{route('admin.hotels.date-plans.packages.index',[$hotel_id,$date_plan_id])}}">Packages</a></li>
            <li class="breadcrumb-item active">Edit package</li>
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
                        <i class="fas fa-suitcase"></i>
                        Edit package
                      </h3>
                    </div>
                    <div class="card-body">
                        <form action="{{route('admin.hotels.date-plans.packages.update',[$hotel_id,$date_plan_id,$data->id])}}" method="post" id="addNewForm">@csrf
                            @method('put')
                            <div class="row">
                                <div class="col-lg-4 col-sm-4 col-md-12 col-xs-12">
                                    <x-forms.input class="form-control {{ $errors->has('package') ? ' is-invalid' : '' }}" title="Package name" name="package" id="package" type="text" required="True"/>
                                </div>
                                <div class="col-lg-4 col-sm-4 col-md-12 col-xs-12">
                                    <x-forms.input class="form-control {{ $errors->has('no_nights') ? ' is-invalid' : '' }}" title="Duration(No of nights)" name="no_nights" id="no_nights" type="number" required="True"/>
                                </div>
                                <div class="col-lg-4 col-sm-4 col-md-12 col-xs-12">
                                    <div class="form-group">
                                        <label for="meal_plan">Meal plan <span style="color:red;">*</span></label>
                                        <select name="meal_plan" id="meal_plan" class="form-control" required>
                                            <option value="CP">CP</option>
                                            <option value="EP">EP</option>
                                            <option value="MAP">MAP</option>
                                            <option value="AP">AP</option>
                                        </select>
                                        @error('meal_plan')
                                            <span class="error mt-2 text-danger" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                                    <x-forms.input class="form-control {{ $errors->has('description') ? ' is-invalid' : '' }}" title="Description" name="description" id="description" type="textarea" required="False"/>
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
        <script src="{{asset('assets/admin/plugins/select2/js/select2.full.min.js')}}"></script>
        <!-- date-range-picker -->
        <script src="{{asset('assets/admin/plugins/moment/moment.min.js')}}"></script>
        <script src="{{asset('assets/admin/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')}}"></script>
        <script>
            $(function () {
                $('#addNewForm').validate({
                    rules: {
                        package: {
                            required: true
                        },
                        no_nights: {
                            required: true
                        },
                        meal_plan: {
                            required: true
                        },
                    },
                    messages: {
                        package: {
                            required: "Please enter the package name"
                        },
                        no_nights: {
                            required: "Please enter no of nights"
                        },
                        meal_plan: {
                            required: "Please select meal plan"
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

            function prefillForm(){
                $('#package').val('{{$data->package}}');
                $('#no_nights').val('{{$data->no_nights}}');
                $('#description').val('{{$data->description}}');
                $('#meal_plan').val('{{$data->meal_plan}}');
                var status='{{$data->status}}';
                if(status==0){
                    $("#customSwitch1").prop('checked', false);
                }else{
                    $("#customSwitch1").prop('checked', true);
                }
            }
            prefillForm();
        </script>
    @endsection