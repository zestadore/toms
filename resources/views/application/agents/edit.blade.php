@extends('layouts.app')
    @section('title')
        <title>TOMS | Agents</title>
    @endsection
    @section('css')
        <link rel="stylesheet" href="{{asset('assets/admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/admin/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/admin/plugins/toastr/toastr.min.css')}}">
    @endsection
    @section('breadcrump')
        <div class="col-sm-6">
            <h1 class="m-0">Agents</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{route('admin.agents.index')}}">Agents</a></li>
            <li class="breadcrumb-item active">Edit agent</li>
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
                        <i class="fas fa-users"></i>
                        Edit agent
                      </h3>
                    </div>
                    <div class="card-body">
                        <form action="{{route('admin.agents.update',$data->id)}}" method="post" id="addNewForm">@csrf
                            @method('put')
                            <div class="row">
                                <div class="col-lg-6 col-sm-6 col-md-12 col-xs-12">
                                    <x-forms.input class="form-control {{ $errors->has('company_name') ? ' is-invalid' : '' }}" title="Agent name" name="company_name" id="company_name" type="text" required="True"/>
                                </div>
                                <div class="col-lg-6 col-sm-6 col-md-12 col-xs-12">
                                    <x-forms.input class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" title="Email" name="email" id="email" type="email" required="True"/>
                                </div>
                            </div>
                            <x-forms.input class="form-control {{ $errors->has('address') ? ' is-invalid' : '' }}" title="Address" name="address" id="address" type="textarea" required="False"/>
                            <div class="row">
                                <div class="col-lg-4 col-sm-4 col-md-12 col-xs-12">
                                    <x-forms.input class="form-control {{ $errors->has('state') ? ' is-invalid' : '' }}" title="State" name="state" id="state" type="text" required="True"/>
                                </div>
                                <div class="col-lg-4 col-sm-4 col-md-12 col-xs-12">
                                    <x-forms.input class="form-control {{ $errors->has('contact') ? ' is-invalid' : '' }}" title="Contact" name="contact" id="contact" type="text" required="True"/>
                                </div>
                                <div class="col-lg-4 col-sm-4 col-md-12 col-xs-12">
                                    <x-forms.input class="form-control {{ $errors->has('website') ? ' is-invalid' : '' }}" title="Website" name="website" id="website" type="text" required="False"/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4 col-sm-4 col-md-12 col-xs-12">
                                    <x-forms.input class="form-control {{ $errors->has('contact_person') ? ' is-invalid' : '' }}" title="Contact person" name="contact_person" id="contact_person" type="text" required="False"/>
                                </div>
                                <div class="col-lg-4 col-sm-4 col-md-12 col-xs-12">
                                    <x-forms.input class="form-control {{ $errors->has('person_contact') ? ' is-invalid' : '' }}" title="Contact person no" name="person_contact" id="person_contact" type="text" required="False"/>
                                </div>
                                <div class="col-lg-4 col-sm-4 col-md-12 col-xs-12">
                                    <x-forms.input class="form-control {{ $errors->has('person_email') ? ' is-invalid' : '' }}" title="Contact person email" name="person_email" id="person_email" type="text" required="False"/>
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
                        company_name: {
                            required: true
                        },
                        email: {
                            required: true
                        },
                        state: {
                            required: true
                        },
                        contact: {
                            required: true
                        },
                    },
                    messages: {
                        company_name: {
                            required: "Please enter the company name"
                        },
                        email: {
                            required: "Please enter the email id"
                        },
                        company_name: {
                            required: "Please enter the company name"
                        },
                        state: {
                            required: "Please enter the state"
                        },
                        contact: {
                            required: "Please enter the contact number"
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
                $('#company_name').val('{{$data->company_name}}');
                $('#email').val('{{$data->email}}');
                $('#address').val('{{$data->address}}');
                $('#state').val('{{$data->state}}');
                $('#contact').val('{{$data->contact}}');
                $('#website').val('{{$data->website}}');
                $('#contact_person').val('{{$data->contact_person}}');
                $('#person_contact').val('{{$data->person_contact}}');
                $('#person_email').val('{{$data->person_email}}');
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