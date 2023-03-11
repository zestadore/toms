@extends('layouts.app')
    @section('title')
        <title>TOMS | Members</title>
    @endsection
    @section('css')
        <link rel="stylesheet" href="{{asset('assets/admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/admin/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/admin/plugins/toastr/toastr.min.css')}}">
    @endsection
    @section('breadcrump')
        <div class="col-sm-6">
            <h1 class="m-0">Members</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{route('admin.members.index')}}">Members</a></li>
            <li class="breadcrumb-item active">Edit member</li>
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
                        <i class="fas fa-user"></i>
                        Create members
                      </h3>
                    </div>
                    <div class="card-body">
                        <form action="{{route('admin.members.update',[Crypt::encrypt($data->id)])}}" method="post" id="addNewForm" enctype='multipart/form-data'>@csrf
                            @method('put')
                            <div class="row">
                                <div class="col-lg-6 col-sm-6 col-md-12 col-xs-12">
                                    <x-forms.input class="form-control {{ $errors->has('first_name') ? ' is-invalid' : '' }}" title="First name" name="first_name" id="first_name" type="text" required="True"/>
                                </div>
                                <div class="col-lg-6 col-sm-6 col-md-12 col-xs-12">
                                    <x-forms.input class="form-control {{ $errors->has('last_name') ? ' is-invalid' : '' }}" title="Last name" name="last_name" id="last_name" type="text" required="False"/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 col-sm-6 col-md-12 col-xs-12">
                                    <x-forms.input class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" title="Email" name="email" id="email" type="text" required="True"/>
                                </div>
                                <div class="col-lg-6 col-sm-6 col-md-12 col-xs-12">
                                    <x-forms.input class="form-control {{ $errors->has('mobile') ? ' is-invalid' : '' }}" title="Mobile" name="mobile" id="mobile" type="number" required="True"/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 col-sm-6 col-md-12 col-xs-12">
                                    <x-forms.input class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}" title="Password" name="password" id="password" type="password" required="True"/>
                                </div>
                                <div class="col-lg-6 col-sm-6 col-md-12 col-xs-12">
                                    <x-forms.input class="form-control {{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}" title="Retype password" name="password_confirmation" id="password_confirmation" type="password" required="True"/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 col-sm-6 col-md-12 col-xs-12">
                                    <x-forms.input class="custom-file-input {{ $errors->has('image') ? ' is-invalid' : '' }}" title="Image" name="image" id="image" type="file" required="False"/>
                                </div>
                                <div class="col-lg-6 col-sm-6 col-md-12 col-xs-12">
                                    <div class="form-group">
                                        <label for="role">Role</label>
                                        <select name="role" id="role" class="form-control">
                                            <option value="user">Operations</option>
                                            <option value="reservations">Reservations</option>
                                            <option value="accounts">Accounts</option>
                                            <option value="admin">Admin</option>
                                        </select>
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
                    first_name: {
                        required: true
                    },
                    email: {
                        required: true
                    },
                    mobile: {
                        required: true
                    },
                    password: {
                        required: true
                    },
                    password_confirmation: {
                        required: true
                    },
                    },
                    messages: {
                        first_name: {
                            required: "Please enter the first_name"
                        },
                        email: {
                            required: "Please enter the email id"
                        },
                        mobile: {
                            required: "Please enter the mobile number"
                        },
                        password: {
                            required: "Please enter the password"
                        },
                        password_confirmation: {
                            required: "Please retype the password"
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
                $('#first_name').val('{{$data->first_name}}');
                $('#last_name').val('{{$data->last_name}}');
                $('#email').val('{{$data->email}}');
                $('#mobile').val('{{$data->mobile}}');
            }
            prefillForm();
        </script>
    @endsection