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
            <h1 class="m-0">Bank</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{route('admin.banks.index')}}">Banks</a></li>
            <li class="breadcrumb-item active">Edit bank</li>
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
                        <i class="fas fa-project-diagram"></i>
                        Edit bank
                      </h3>
                    </div>
                    <div class="card-body">
                        <form action="{{route('admin.banks.update',$data->id)}}" method="post" id="addNewForm">@csrf
                            @method('put')
                            <div class="row">
                                <div class="col-lg-6 col-sm-6 col-md-12 col-xs-12">
                                    <x-forms.input class="form-control {{ $errors->has('bank_name') ? ' is-invalid' : '' }}" title="Bank name" name="bank_name" id="bank_name" type="text" required="True"/>
                                </div>
                                <div class="col-lg-6 col-sm-6 col-md-12 col-xs-12">
                                    <x-forms.input class="form-control {{ $errors->has('branch') ? ' is-invalid' : '' }}" title="Branch" name="branch" id="branch" type="text" required="True"/>
                                </div>
                            </div>
                            <x-forms.input class="form-control {{ $errors->has('address') ? ' is-invalid' : '' }}" title="Address" name="address" id="address" type="textarea" required="False"/>
                            <div class="row">
                                <div class="col-lg-4 col-sm-4 col-md-12 col-xs-12">
                                    <x-forms.input class="form-control {{ $errors->has('account_name') ? ' is-invalid' : '' }}" title="Account name" name="account_name" id="account_name" type="text" required="True"/>
                                </div>
                                <div class="col-lg-4 col-sm-4 col-md-12 col-xs-12">
                                    <x-forms.input class="form-control {{ $errors->has('account_number') ? ' is-invalid' : '' }}" title="Account number" name="account_number" id="account_number" type="text" required="True"/>
                                </div>
                                <div class="col-lg-4 col-sm-4 col-md-12 col-xs-12">
                                    <x-forms.input class="form-control {{ $errors->has('ifsc') ? ' is-invalid' : '' }}" title="IFSC" name="ifsc" id="ifsc" type="text" required="True"/>
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
                        bank_name: {
                            required: true
                        },
                        branch: {
                            required: true
                        },
                        account_name: {
                            required: true
                        },
                        account_number: {
                            required: true
                        },
                        ifsc: {
                            required: true
                        },
                    },
                    messages: {
                        bank_name: {
                            required: "Please enter the bank name"
                        },
                        branch: {
                            required: "Please enter the branch"
                        },
                        account_name: {
                            required: "Please enter the account name"
                        },
                        account_number: {
                            required: "Please enter the account number"
                        },
                        ifsc: {
                            required: "Please enter the is code"
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
                $('#bank_name').val('{{$data->bank_name}}');
                $('#branch').val('{{$data->branch}}');
                $('#address').val('{{$data->address}}');
                $('#account_name').val('{{$data->account_name}}');
                $('#account_number').val('{{$data->account_number}}');
                $('#ifsc').val('{{$data->ifsc}}');
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