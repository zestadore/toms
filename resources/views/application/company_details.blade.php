@extends('layouts.app')
    @section('title')
        <title>TOMS | Company details</title>
    @endsection
    @section('css')
        <link rel="stylesheet" href="{{asset('assets/admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/admin/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/admin/plugins/toastr/toastr.min.css')}}">
    @endsection
    @section('breadcrump')
        <div class="col-sm-6">
            <h1 class="m-0">Company details</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
            <li class="breadcrumb-item active">Company details</li>
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
                        <i class="fas fa-registered"></i>
                        Company details
                      </h3>
                    </div>
                    <div class="card-body">
                        <form action="{{route('admin.company.details.save')}}" method="post" id="addNewForm" enctype='multipart/form-data'>@csrf
                            <x-forms.input class="form-control {{ $errors->has('company_name') ? ' is-invalid' : '' }}" title="Company name" name="company_name" id="company_name" type="text" required="True"/>
                            <div class="row">
                                <div class="col-lg-6 col-sm-6 col-md-12 col-xs-12">
                                    <x-forms.input class="form-control {{ $errors->has('contact_1') ? ' is-invalid' : '' }}" title="Contact 1" name="contact_1" id="contact_1" type="number" required="True"/>
                                </div>
                                <div class="col-lg-6 col-sm-6 col-md-12 col-xs-12">
                                    <x-forms.input class="form-control {{ $errors->has('contact_2') ? ' is-invalid' : '' }}" title="Contact 2" name="contact_2" id="contact_2" type="number" required="False"/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 col-sm-6 col-md-12 col-xs-12">
                                    <x-forms.input class="form-control {{ $errors->has('email_id') ? ' is-invalid' : '' }}" title="Email id" name="email_id" id="email_id" type="text" required="True"/>
                                </div>
                                <div class="col-lg-6 col-sm-6 col-md-12 col-xs-12">
                                    <x-forms.input class="form-control {{ $errors->has('url') ? ' is-invalid' : '' }}" title="Website" name="url" id="url" type="text" required="False"/>
                                </div>
                            </div>
                            <x-forms.input class="form-control {{ $errors->has('address') ? ' is-invalid' : '' }}" title="Address" name="address" id="address" type="textarea" required="False"/>
                            <div class="row">
                                <div class="col-lg-4 col-sm-4 col-md-12 col-xs-12">
                                    <x-forms.input class="form-control {{ $errors->has('gst_number') ? ' is-invalid' : '' }}" title="GST IN" name="gst_number" id="gst_number" type="text" required="True"/>
                                </div>
                                <div class="col-lg-4 col-sm-4 col-md-12 col-xs-12">
                                    <x-forms.input class="form-control {{ $errors->has('gst') ? ' is-invalid' : '' }}" title="GST(%)" name="gst" id="gst" type="number" required="True"/>
                                </div>
                                <div class="col-lg-4 col-sm-4 col-md-12 col-xs-12">
                                    <x-forms.input class="custom-file-input {{ $errors->has('logo') ? ' is-invalid' : '' }}" title="Logo" name="logo" id="logo" type="file" required="False"/>
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
                        company_name: {
                            required: true
                        },
                        contact_1: {
                            required: true
                        },
                        email_id: {
                            required: true
                        },
                        gst_number: {
                            required: true
                        },
                        gst: {
                            required: true
                        },
                    },
                    messages: {
                        company_name: {
                            required: "Please enter company name"
                        },
                        contact_1: {
                            required: "Please enter the contact number"
                        },
                        email_id: {
                            required: "Please enter the email id"
                        },
                        gst_number: {
                            required: "Please enter the GST number"
                        },
                        gst: {
                            required: "Please enter the GST(%)"
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

            function preFillForm(){
                $('#company_name').val('{{$data?->company_name}}');
                $('#contact_1').val('{{$data?->contact_1}}');
                $('#contact_2').val('{{$data?->contact_2}}');
                $('#email_id').val('{{$data?->email_id}}');
                $('#url').val('{{$data?->url}}');
                $('#address').val('{{$data?->address}}');
                $('#gst_number').val('{{$data?->gst_number}}');
                $('#gst').val('{{$data?->gst}}');
            }

            preFillForm();
        </script>
    @endsection