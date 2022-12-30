@extends('layouts.app')
    @section('title')
        <title>TOMS | Package rates</title>
    @endsection
    @section('css')
        <link rel="stylesheet" href="{{asset('assets/admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/admin/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/admin/plugins/toastr/toastr.min.css')}}">
        <style>
            input.largerCheckbox {
                width: 20px;
                height: 20px;
            }
        </style>
    @endsection
    @section('breadcrump')
        <div class="col-sm-6">
            <h1 class="m-0">Package rates</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{route('admin.hotels.index')}}">Hotels</a></li>
            <li class="breadcrumb-item"><a href="{{route('admin.hotels.date-plans.index',$hotel_id)}}">Date Plans</a></li>
            <li class="breadcrumb-item"><a href="{{route('admin.hotels.date-plans.packages.index',[$hotel_id,$date_plan_id])}}">Packages</a></li>
            <li class="breadcrumb-item"><a href="{{route('admin.hotels.date-plans.packages.rates.index',[$hotel_id,$date_plan_id,$package_id])}}">Rates</a></li>
            <li class="breadcrumb-item active">Create rate</li>
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
                        <i class="fas fa-rupee-sign"></i>
                        Create package rate
                      </h3>
                    </div>
                    <div class="card-body">
                        <form action="{{route('admin.hotels.date-plans.packages.rates.store',[$hotel_id,$date_plan_id,$package_id])}}" method="post" id="addNewForm">@csrf
                            <div class="row">
                                <div class="col-lg-4 col-sm-4 col-md-12 col-xs-12">
                                    <div class="form-group">
                                        <label for="room_category">Room category <span style="color:red;">*</span></label>
                                        <select name="room_category" id="room_category" class="form-control" required>
                                            <option value="">Select room</option>
                                            @foreach ($roomCategories as $category)
                                                <option value="{{$category->id}}">{{$category->room_category}}</option>
                                            @endforeach
                                        </select>
                                        @error('room_category')
                                            <span class="error mt-2 text-danger" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-8 col-sm-8 col-md-12 col-xs-12">
                                    <label>Valid days</label><br>
                                    <input type="checkbox" name="days[]" id="days" value="1" class="largerCheckbox" checked>
                                    <label for="days"> Sun</label>&nbsp;
                                    <input type="checkbox" name="days[]" id="days" value="2" class="largerCheckbox" checked>
                                    <label for="days"> Mon</label>&nbsp;
                                    <input type="checkbox" name="days[]" id="days" value="3" class="largerCheckbox" checked>
                                    <label for="days"> Tue</label>&nbsp;
                                    <input type="checkbox" name="days[]" id="days" value="4" class="largerCheckbox" checked>
                                    <label for="days"> Wed</label>&nbsp;
                                    <input type="checkbox" name="days[]" id="days" value="5" class="largerCheckbox" checked>
                                    <label for="days"> Thu</label>&nbsp;
                                    <input type="checkbox" name="days[]" id="days" value="6" class="largerCheckbox" checked>
                                    <label for="days"> Fri</label>&nbsp;
                                    <input type="checkbox" name="days[]" id="days" value="7" class="largerCheckbox" checked>
                                    <label type="checkbox"> Sat</label>&nbsp;
                                </div>
                            </div>
                            <h5>Room rates(special rates)</h5>
                            <hr>
                            <div class="row">
                                <div class="col-lg-3 col-sm-3 col-md-12 col-xs-12">
                                    <x-forms.input class="form-control {{ $errors->has('single') ? ' is-invalid' : '' }}" title="Single" name="single" id="single" type="number" required="True"/>
                                </div>
                                <div class="col-lg-3 col-sm-3 col-md-12 col-xs-12">
                                    <x-forms.input class="form-control {{ $errors->has('double') ? ' is-invalid' : '' }}" title="Double" name="double" id="double" type="number" required="True"/>
                                </div>
                                <div class="col-lg-2 col-sm-2 col-md-12 col-xs-12">
                                    <x-forms.input class="form-control {{ $errors->has('extra_adult') ? ' is-invalid' : '' }}" title="Ex bed" name="extra_adult" id="extra_adult" type="number" required="True"/>
                                </div>
                                <div class="col-lg-4 col-sm-4 col-md-12 col-xs-12">
                                    <div class="row">
                                        <div class="col-lg-6 col-sm-6 col-md-12 col-xs-12">
                                            <x-forms.input class="form-control {{ $errors->has('extra_child_bed') ? ' is-invalid' : '' }}" title="Ex bed(child)" name="extra_child_bed" id="extra_child_bed" type="number" required="True"/>
                                        </div>
                                        <div class="col-lg-6 col-sm-6 col-md-12 col-xs-12">
                                            <x-forms.input class="form-control {{ $errors->has('extra_child_wout_bed') ? ' is-invalid' : '' }}" title="Ex child w/out bed" name="extra_child_wout_bed" id="extra_child_wout_bed" type="number" required="True"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <h5>Meal suppliments(special rates)</h5>
                            <hr>
                            <div class="row">
                                <div class="col-lg-4 col-sm-4 col-md-12 col-xs-12">
                                    <label>Breakfast</label>
                                    <div class="row">
                                        <div class="col-lg-6 col-sm-6 col-md-12 col-xs-12">
                                            <x-forms.input class="form-control {{ $errors->has('breakfast') ? ' is-invalid' : '' }}" title="Adult" name="breakfast" id="breakfast" type="number" required="True"/>
                                        </div>
                                        <div class="col-lg-6 col-sm-6 col-md-12 col-xs-12">
                                            <x-forms.input class="form-control {{ $errors->has('child_breakfast') ? ' is-invalid' : '' }}" title="Child" name="child_breakfast" id="child_breakfast" type="number" required="True"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-4 col-md-12 col-xs-12">
                                    <label>Lunch</label>
                                    <div class="row">
                                        <div class="col-lg-6 col-sm-6 col-md-12 col-xs-12">
                                            <x-forms.input class="form-control {{ $errors->has('lunch') ? ' is-invalid' : '' }}" title="Adult" name="lunch" id="lunch" type="number" required="True"/>
                                        </div>
                                        <div class="col-lg-6 col-sm-6 col-md-12 col-xs-12">
                                            <x-forms.input class="form-control {{ $errors->has('child_lunch') ? ' is-invalid' : '' }}" title="Child" name="child_lunch" id="child_lunch" type="number" required="True"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-4 col-md-12 col-xs-12">
                                    <label>Dinner</label>
                                    <div class="row">
                                        <div class="col-lg-6 col-sm-6 col-md-12 col-xs-12">
                                            <x-forms.input class="form-control {{ $errors->has('dinner') ? ' is-invalid' : '' }}" title="Adult" name="dinner" id="dinner" type="number" required="True"/>
                                        </div>
                                        <div class="col-lg-6 col-sm-6 col-md-12 col-xs-12">
                                            <x-forms.input class="form-control {{ $errors->has('child_dinner') ? ' is-invalid' : '' }}" title="Child" name="child_dinner" id="child_dinner" type="number" required="True"/>
                                        </div>
                                    </div>
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
                        room_category: {
                            required: true
                        },
                        single: {
                            required: true
                        },
                        double: {
                            required: true
                        },
                        extra_adult: {
                            required: true
                        },
                        extra_child_bed: {
                            required: true
                        },
                        extra_child_wout_bed: {
                            required: true
                        },
                        breakfast: {
                            required: true
                        },
                        lunch: {
                            required: true
                        },
                        dinner: {
                            required: true
                        },
                        child_breakfast: {
                            required: true
                        },
                        child_lunch: {
                            required: true
                        },
                        child_dinner: {
                            required: true
                        },
                    },
                    messages: {
                        room_category: {
                            required: "Please select room category"
                        },
                        single: {
                            required: "Please enter rate for single room"
                        },
                        double: {
                            required: "Please enter rate for double room"
                        },
                        extra_adult: {
                            required: "Please enter rate for extra bed"
                        },
                        extra_child_bed: {
                            required: "Please enter rate for extra bed(child)"
                        },
                        extra_child_wout_bed: {
                            required: "Please enter rate for extra child w/out bed"
                        },
                        breakfast: {
                            required: "Please enter rate for breakfast"
                        },
                        lunch: {
                            required: "Please enter rate for lunch"
                        },
                        dinner: {
                            required: "Please enter rate for dinner"
                        },
                        child_breakfast: {
                            required: "Please enter rate for breakfast(child)"
                        },
                        child_lunch: {
                            required: "Please enter rate for lunch(child)"
                        },
                        child_dinner: {
                            required: "Please enter rate for dinner(child)"
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