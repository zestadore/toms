@php
    use Carbon\Carbon;
@endphp
@extends('layouts.app')
    @section('title')
        <title>TOMS | Overdues</title>
    @endsection
    @section('css')
        <link rel="stylesheet" href="{{asset('assets/admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/admin/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/admin/plugins/toastr/toastr.min.css')}}">
    @endsection
    @section('breadcrump')
        <div class="col-sm-6">
            <h1 class="m-0">Overdues</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
            <li class="breadcrumb-item active">Overdues</li>
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
                        <i class="fas fa-donate"></i>
                        Overdues
                      </h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <tr>
                                <th>Sl.no</th>
                                <th>Agent</th>
                                <th>Payment details</th>
                                <th>Bank</th>
                                <th>Total amount</th>
                                <th>Balance amount</th>
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
                                    <td>₹ {{$payment->balance_amount}}</td>
                                    <td>{{Carbon::parse($payment->created_at)->format('d-M-Y')}}</td>
                                    <td>
                                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                            <label class="btn bg-olive">
                                                <input type="radio" name="options" id="option_b1" autocomplete="off" checked="" onclick="getBookingsList('{{$payment->id}}')"> Use
                                            </label>
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
        <!-- /.modal -->
        <div class="modal fade" id="bookings">
            <div class="modal-dialog modal-lg">
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title">Bookings</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                    <div id="bookingLists"></div>
                </div>
                <div class="modal-footer justify-content-between">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
              </div>
              <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
    @endsection
    @section('scripts')
        <!-- jquery-validation -->
        <script src="{{asset('assets/admin/plugins/jquery-validation/jquery.validate.min.js')}}"></script>
        <script src="{{asset('assets/admin/plugins/jquery-validation/additional-methods.min.js')}}"></script>
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
        <script>
            function getBookingsList(id){
                swal({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    buttons: true,
                    dangerMode: true,
                }).then((result) => {
                    if (result) {
                        var url="{{route('operations.bookings.list','ID')}}";
                        url=url.replace('ID',id);
                        $.ajax({
                            url: url,
                            type:"get",
                            success:function(response){
                                if(response){
                                    var html="<table class='table table-bordered'>";
                                    html+="<tr><th>Rev</th><th>Guest name</th><th>Total amount</th><th>Amount pending</th><th>Action</th></tr>";
                                    $.each(response, function( index, value ) {
                                        document.cookie = "booking_id ="+value.booking_id;
                                        document.cookie = "quote_revision_id ="+value.quote_revision_id;
                                        html+="<tr><td>"+value.quote_id+"</td><td>"+value.guest_name+"</td><td>₹ "+value.total_amount+"</td><td>₹ "+value.balance+"</td>";
                                        html+="<td><button class='btn btn-info applyPayment' data-id="+id+" data-booking="+value.booking_id+">Apply payment</button></td></tr>";
                                    });
                                    html+="</table>";
                                    $('#bookingLists').html(html);
                                    $('#bookings').modal('show');
                                }else{
                                    swal("Oops!", response.error, "danger");
                                }
                            },
                        });
                    }
                })
            }

            $(document).on('click','.applyPayment', function() {
                var id=$(this).attr('data-id');
                var bookingId=$(this).attr('data-booking');
                $.ajax({
                    url: "{{route('operations.payment.apply')}}",
                    type:"post",
                    data:{
                        "_token": "{{ csrf_token() }}",
                        booking_id:bookingId,
                        overdue_id:id
                    },
                    success:function(response){
                        if(response.success){
                            swal("Yes!", response.success, "success");
                        }else{
                            swal("Oops!", response.error, "error");
                        }
                    },
                }); 
            });
        </script>
        
    @endsection