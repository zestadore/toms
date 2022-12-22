@extends('layouts.app')
    @section('title')
        <title>TOMS | Hotels</title>
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
            <h1 class="m-0">Hotels</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{route('admin.hotels.index')}}">Hotels</a></li>
            <li class="breadcrumb-item active">Create hotel</li>
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
                        <i class="fas fa-bed"></i>
                        Create hotel
                      </h3>
                    </div>
                    <div class="card-body">
                        <form action="{{route('admin.hotels.store')}}" method="post" id="addNewForm" enctype='multipart/form-data'>@csrf
                            <div class="row">
                                <div class="col-lg-6 col-sm-6 col-md-12 col-xs-12">
                                    <x-forms.input class="form-control {{ $errors->has('hotel') ? ' is-invalid' : '' }}" title="Hotel" name="hotel" id="hotel" type="text" required="True"/>
                                </div>
                                <div class="col-lg-6 col-sm-6 col-md-12 col-xs-12">
                                    <div class="form-group">
                                        <label>Destination <span style="color:red;">*</span></label>
                                        <select class="form-control select2bs4" name="destination_id" id="destination_id" style="width: 100%;" required>
                                          <option value="">Select a destination</option>
                                          @foreach ($destinations as $item)
                                              <option value="{{$item->id}}">{{$item->destination}}</option>
                                          @endforeach
                                        </select>
                                        @error('destination_id')
                                            <span class="error mt-2 text-danger" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4 col-sm-4 col-md-12 col-xs-12">
                                    <x-forms.input class="form-control {{ $errors->has('location') ? ' is-invalid' : '' }}" title="Location" name="location" id="location" type="text" required="False"/>
                                </div>
                                <div class="col-lg-4 col-sm-4 col-md-12 col-xs-12">
                                    <x-forms.input class="form-control {{ $errors->has('inventory') ? ' is-invalid' : '' }}" title="Inventory" name="inventory" id="inventory" type="number" required="False"/>
                                </div>
                                <div class="col-lg-4 col-sm-4 col-md-12 col-xs-12">
                                    <div class="form-group">
                                        <label>Category </label>
                                        <select class="form-control select2bs4" name="category_id" id="category_id" style="width: 100%;">
                                          <option value="">Select a category</option>
                                          @foreach ($categories as $item)
                                              <option value="{{$item->id}}">{{$item->category}}</option>
                                          @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 col-sm-6 col-md-12 col-xs-12">
                                    <x-forms.input class="form-control {{ $errors->has('contact') ? ' is-invalid' : '' }}" title="Front office contact" name="contact" id="contact" type="number" required="False"/>
                                </div>
                                <div class="col-lg-6 col-sm-6 col-md-12 col-xs-12">
                                    <x-forms.input class="form-control {{ $errors->has('reservation_contact') ? ' is-invalid' : '' }}" title="Reservations contact" name="reservation_contact" id="reservation_contact" type="number" required="True"/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4 col-sm-4 col-md-12 col-xs-12">
                                    <x-forms.input class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" title="Email id" name="email" id="email" type="email" required="True"/>
                                </div>
                                <div class="col-lg-4 col-sm-4 col-md-12 col-xs-12">
                                    <x-forms.input class="form-control {{ $errors->has('website') ? ' is-invalid' : '' }}" title="Website" name="website" id="website" type="text" required="False"/>
                                </div>
                                <div class="col-lg-4 col-sm-4 col-md-12 col-xs-12">
                                    <x-forms.input class="custom-file-input {{ $errors->has('image') ? ' is-invalid' : '' }}" title="Image" name="image" id="image" type="file" required="False"/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 col-sm-6 col-md-12 col-xs-12">
                                    <x-forms.input class="form-control {{ $errors->has('address') ? ' is-invalid' : '' }}" title="Hotel/Resort address" name="address" id="address" type="textarea" required="False"/>
                                </div>
                                <div class="col-lg-6 col-sm-6 col-md-12 col-xs-12">
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
        <script>
            $(function () {
                $('#addNewForm').validate({
                    rules: {
                        hotel: {
                            required: true
                        },
                        destination_id: {
                            required: true
                        },
                        email: {
                            required: true
                        },
                        reservation_contact: {
                            required: true
                        },
                    },
                    messages: {
                        hotel: {
                            required: "Please enter the hotel name"
                        },
                        destination_id: {
                            required: "Please select the destination"
                        },
                        email: {
                            required: "Please enter the email id"
                        },
                        reservation_contact: {
                            required: "Please enter the reservation contact number"
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
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            })
        </script>
    @endsection