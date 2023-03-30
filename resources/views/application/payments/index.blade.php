@php
    use Carbon\Carbon;
@endphp
@extends('layouts.app')
    @section('title')
        <title>TOMS | Pending payments</title>
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
            <li class="breadcrumb-item active">Pending payments</li>
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
                        Pending payments
                      </h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <tr>
                                <th>Sl.no</th>
                                <th>Agent</th>
                                <th>Payment details</th>
                                <th>Bank</th>
                                <th>Amount</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                            @foreach ($data as $payment)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$payment->quotation->agent->company_name}}</td>
                                    <td>{{$payment->payment_details}}</td>
                                    <td>{{$payment->bank->bank_name}}</td>
                                    <td>₹ {{$payment->amount}}</td>
                                    <td>{{Carbon::parse($payment->created_at)->format('d-M-Y')}}</td>
                                    <td>
                                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                            @if ($payment->status==0)
                                                <label class="btn bg-olive">
                                                    <input type="radio" name="options" id="option_b1" autocomplete="off" checked="" onclick="approvePayment('{{$payment->id}}')"> Approve
                                                </label>
                                                <label class="btn bg-warning">
                                                    <input type="radio" name="options" id="option_b2" autocomplete="off" onclick="rejectPayment('{{$payment->id}}')"> Reject
                                                </label>
                                            @endif
                                            
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
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
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
        <script>
            function approvePayment(id){
                swal({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    buttons: true,
                    dangerMode: true,
                }).then((result) => {
                    if (result) {
                        var url="{{route('admin.members.destroy','ID')}}";
                        url=url.replace('ID',id);
                        $.ajax({
                            url: '{{route("admin.payment.approve")}}',
                            type:"post",
                            data:{
                                "_token": "{{ csrf_token() }}",
                                id:id
                            },
                            success:function(response){
                                console.log(response);
                                if(response.success){
                                    swal("Good job!", response.success, "success");
                                    location.reload();
                                }else{
                                    swal("Oops!", response.error, "danger");
                                }
                            },
                        });
                    }
                })
            }

            function rejectPayment(id){
                swal({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    buttons: true,
                    dangerMode: true,
                }).then((result) => {
                    if (result) {
                        var url="{{route('admin.members.destroy','ID')}}";
                        url=url.replace('ID',id);
                        $.ajax({
                            url: '{{route("admin.payment.reject")}}',
                            type:"post",
                            data:{
                                "_token": "{{ csrf_token() }}",
                                id:id
                            },
                            success:function(response){
                                console.log(response);
                                if(response.success){
                                    swal("Good job!", response.success, "success");
                                    location.reload();
                                }else{
                                    swal("Oops!", response.error, "danger");
                                }
                            },
                        });
                    }
                })
            }
        </script>
    @endsection