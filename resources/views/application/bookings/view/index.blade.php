@php
    use Carbon\Carbon;
@endphp
@extends('layouts.app')
    @section('title')
        <title>TOMS | View booking</title>
    @endsection
    @section('css')
        <link rel="stylesheet" href="{{asset('assets/admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/admin/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/admin/plugins/toastr/toastr.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/admin/plugins/summernote/summernote-bs4.min.css')}}">
    @endsection
    @section('breadcrump')
        <div class="col-sm-6">
            <h1 class="m-0">Bookings</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{route('operations.bookings.index')}}">Bookings</a></li>
            <li class="breadcrumb-item active">View booking</li>
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
                        <i class="fas fa-hotel"></i>
                        View booking
                      </h3>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info alert-dismissible">
                            <h5><i class="icon fas fa-star"></i> {{$revision->company_name}} | {{$revision->guest_name}}</h5>
                            <div class="row">
                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                    No of pax : {{$revision->adults}} adults + {{$revision->kids}} children
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                    No of rooms : {{$revision->sgl_rooms}} sgls + {{$revision->dbl_rooms}} dbls <br>
                                    Ex beds : {{$revision->ex_bed_adults}} adults + {{$revision->ex_bed_children}} children<br>
                                    W/Out beds : {{$revision->ex_children_wout}} 
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                    Date of arrival : {{Carbon::parse($revision->arrival_date)->format('d-M-Y')}}<br>
                                    Meal plan : {{$revision->meal_plan}}<br>
                                </div>
                            </div>
                        </div>
                        <h5>Accomodation details</h5>
                        <table class="table table-bordered">
                            <tr>
                                <th>Checkin</th>
                                <th>Destination</th>
                                <th>Hotel</th>
                                <th>Room</th>
                                <th>Action</th>
                            </tr>
                            @foreach ($revision->revisionDetails as $item)
                                <tr>
                                    <td>{{Carbon::parse($item?->checkin)->format('d-M-Y')}}</td>
                                    <td>{{$item?->destination?->destination}}</td>
                                    <td>{{$item?->hotel?->hotel}}</td>
                                    <td>{{$item?->roomCategory?->room_category}}</td>
                                    <td>
                                        <button class="btn btn-primary" onclick="bookingDetails('{{getBookingDetailsId($item?->id)}}')">Booking details</button>
                                    </td>
                                </tr>
                            @endforeach
                        </table><br>
                        <h5>Vehicle details</h5>
                        <table class="table table-bordered">
                            <tr>
                                <td>Vehicle : {{$revision->vehicle->vehicle_name}}</td>
                                <td>Days : {{$revision->no_nights + 1}}</td>
                                <td>Allowed Km : {{$revision->allowed_kms}}</td>
                                <td><button class="btn btn-primary" id="vehicleBookingDetails">Booking details</button></td>
                            </tr>
                        </table><br>
                        <table class="table table-bordered">
                            <tr>
                                {{-- <td>Accomodation : &#x20b9; {{$revision->accomodation_cost}}</td>
                                <td>Transportation : &#x20b9; {{$revision->vehicle_rate}}</td>
                                <td>Grand total : &#x20b9; {{$revision->grand_total}}</td>
                                <td>GST : &#x20b9; {{$revision->gst_amount}}</td>
                                <td>Disount : &#x20b9; {{$revision->discount_amount}}</td>
                                <td>Markup : &#x20b9; {{$revision->markup_amount}}</td> --}}
                                <td style="color:green;background:yellow;font-style:italic;font-weight: bold;">Net rate : &#x20b9; {{$revision->net_rate}}</td>
                            </tr>
                        </table>
                        <hr>
                        <br><div>
                            <div class="btn-group btn-group-toggle" data-toggle="buttons" style="float:right;">
                                <label class="btn bg-info">
                                  <input type="radio" name="options" autocomplete="off" checked="" id="forwardBooking"> Forward booking
                                </label>
                                @if (checkPayments($booking->id,$booking->quote_revision_id)==False)
                                    <label class="btn bg-info">
                                        <input type="radio" name="options" autocomplete="off" checked="" id="addPayment"> Add payments
                                    </label>
                                @endif
                                @if (checkPayments($booking->id,$booking->quote_revision_id)==True)
                                    <label class="btn bg-info">
                                        <input type="radio" name="options" autocomplete="off" checked="" id="generateInvoice"> Generate invoice
                                    </label>
                                @endif
                                <label class="btn bg-info">
                                    <input type="radio" name="options" autocomplete="off" checked="" id="cancelBooking"> Cancel booking
                                </label>
                            </div>
                        </div><br>
                        <h5>Payments</h5>
                        <table class="table table-bordered">
                            <tr>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Mode</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            @if (count($payments)>0)
                                @foreach ($payments as $payment)
                                    <tr>
                                        <td>{{Carbon::parse($payment->created_at)->format('d-M-Y')}}</td>
                                        <td>₹ {{$payment->amount}}</td>
                                        <td>{{$payment->bank->bank_name}}</td>
                                        <td>{{$payment->status==0?"Pending":($payment->status==1?"Approved":"Rejected")}}</td>
                                        <td><button onClick="viewPayment('{{$payment->payment_details}}')" class="btn btn-info">View</button></td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan=3 align="center">No payments made</td>
                                </tr>
                            @endif
                        </table>
                    </div><br>
                    <!-- /.card-body -->
                  </div><br>
            </div><!--/. container-fluid -->
        </section>
        <div class="modal fade" id="bookingModal">
            <div class="modal-dialog modal-lg">
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title">Booking details</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col">
                            <x-forms.input class="form-control {{ $errors->has('single') ? ' is-invalid' : '' }}" title="Single" name="single" id="single" type="number" required="True"/>
                        </div>
                        <div class="col">
                            <x-forms.input class="form-control {{ $errors->has('double') ? ' is-invalid' : '' }}" title="Double" name="double" id="double" type="number" required="True"/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <x-forms.input class="form-control {{ $errors->has('extra_adult') ? ' is-invalid' : '' }}" title="Ex adult" name="extra_adult" id="extra_adult" type="number" required="True"/>
                        </div>
                        <div class="col">
                            <x-forms.input class="form-control {{ $errors->has('extra_child_bed') ? ' is-invalid' : '' }}" title="Ex Bed(Child)" name="extra_child_bed" id="extra_child_bed" type="number" required="True"/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <x-forms.input class="form-control {{ $errors->has('extra_child_wout_bed') ? ' is-invalid' : '' }}" title="Ex child W/out bed" name="extra_child_wout_bed" id="extra_child_wout_bed" type="number" required="True"/>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="0">Pending</option>
                                    <option value="1">Confirmed</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <x-forms.input class="form-control {{ $errors->has('booking_details') ? ' is-invalid' : '' }}" title="Booking details" name="booking_details" id="booking_details" type="textarea" required="False"/>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-info" id="bookingDetailsSave" data-id="0">Save</button>
                </div>
              </div>
              <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
        <div class="modal fade" id="vehicleBookingModal">
            <div class="modal-dialog modal-md">
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title">Vehicle booking details</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select name="vehicle_status" id="vehicle_status" class="form-control">
                                    <option value="0">Pending</option>
                                    <option value="1">Confirmed</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <x-forms.input class="form-control {{ $errors->has('vehicle_purchase_rate') ? ' is-invalid' : '' }}" title="Purchase rate" name="vehicle_purchase_rate" id="vehicle_purchase_rate" type="number" required="True"/>
                        </div>
                        <div class="col">
                            <x-forms.input class="form-control {{ $errors->has('vendor') ? ' is-invalid' : '' }}" title="Vendor" name="vendor" id="vendor" type="text" required="True"/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <x-forms.input class="form-control {{ $errors->has('vehicle_booking_details') ? ' is-invalid' : '' }}" title="Booking details" name="vehicle_booking_details" id="vehicle_booking_details" type="textarea" required="True"/>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-info" id="vehicleBookingDetailsSave">Save</button>
                </div>
              </div>
              <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
        <div class="modal fade" id="addPaymentModal">
            <div class="modal-dialog modal-md">
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title">Add payment</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="status">Bank</label>
                                <select name="bank" id="bank" class="form-control">
                                    <option value="0">Other</option>
                                    @foreach ($banks as $bank)
                                        <option value="{{$bank->id}}">{{$bank->bank_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <x-forms.input class="form-control {{ $errors->has('amount') ? ' is-invalid' : '' }}" title="Amount" name="amount" id="amount" type="number" required="True"/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <x-forms.input class="form-control {{ $errors->has('payment_details') ? ' is-invalid' : '' }}" title="Payment details" name="payment_details" id="payment_details" type="textarea" required="True"/>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-info" id="paymentDetailsSave">Save</button>
                </div>
              </div>
              <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
        <div class="modal fade" id="viewPaymentModal">
            <div class="modal-dialog modal-md">
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title">View payment details</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                    <x-forms.input class="form-control {{ $errors->has('view_payment_details') ? ' is-invalid' : '' }}" title="Payment details" name="view_payment_details" id="view_payment_details" type="textarea" required="True"/>
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
        <div class="modal fade" id="viewCancelModal">
            <div class="modal-dialog modal-md">
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title">Cancel booking</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                    <x-forms.input class="form-control {{ $errors->has('cancel_booking') ? ' is-invalid' : '' }}" title="Reason for cancellation" name="cancel_booking" id="cancel_booking" type="textarea" required="True"/>
                </div>
                <div class="modal-footer justify-content-between">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-info" id="cancelBookingSave">Cancel booking</button>
                </div>
              </div>
              <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <div class="modal fade" id="invoiceModal">
            <div class="modal-dialog modal-md">
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title">Choose invoice type</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="custom-control custom-radio">
                          <input class="custom-control-input custom-control-input-warning custom-control-input-outline" type="radio" id="customRadio4" name="invoice_type" checked="">
                          <label for="customRadio4" class="custom-control-label">GST invoice</label>
                        </div>
                        <div class="custom-control custom-radio">
                          <input class="custom-control-input custom-control-input-warning custom-control-input-outline" type="radio" id="customRadio5" name="invoice_type">
                          <label for="customRadio5" class="custom-control-label">Without GST invoice</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-success" id="prepareInvoice">Generate</button>
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
        <!-- date-range-picker -->
        <script src="{{asset('assets/admin/plugins/moment/moment.min.js')}}"></script>
        <script src="{{asset('assets/admin/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')}}"></script>
        <!-- Toastr -->
        <script src="{{asset('assets/admin/plugins/toastr/toastr.min.js')}}"></script>
        <script src="{{asset('assets/admin/plugins/summernote/summernote-bs4.min.js')}}"></script>
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
        <script>
            $('#forwardBooking').click(function(){
                swal({
                    title: 'Booking?',
                    text: "Kindly proceed the booking only after cross check the availability twice?",
                    icon: 'warning',
                    buttons: true,
                    dangerMode: true,
                }).then((result) => {
                    if (result) {
                        var booking_id="{{$booking->id}}";
                        var url="{{route('operations.booking.forward','ID')}}";
                        url=url.replace('ID',booking_id);
                        $.ajax({
                            url: url,
                            type:"post",
                            data:{
                                "_token": "{{ csrf_token() }}",
                            },
                            success:function(response){
                                if(response.success){
                                    swal("Good job!", response.success, "success");
                                }else{
                                    swal("Oops!", response.error, "error");
                                }
                            },
                        });
                    }
                })
            });

            function bookingDetails(bookingId){
                var url="{{route('operations.booking.details','ID')}}";
                url=url.replace('ID',bookingId);
                $.ajax({
                    url: url,
                    type:"get",
                    success:function(response){
                        if(response){
                            response=response.data;
                            $('#single').val(response.single);
                            $('#double').val(response.double);
                            $('#extra_adult').val(response.extra_adult);
                            $('#extra_child_bed').val(response.extra_child_bed);
                            $('#extra_child_wout_bed').val(response.extra_child_wout_bed);
                            $('#booking_details').val(response.booking_details);
                            $('#bookingDetailsSave').attr("data-id",bookingId);
                            $('#status').val(response.status);
                            $('#bookingModal').modal('show');
                        }else{
                            alert('Oops! Some error happened!');
                        }
                    },
                });
                
            }

            $('#bookingDetailsSave').click(function(){
                var id=$(this).attr('data-id');
                var single=$('#single').val();
                var double=$('#double').val();
                var exAdult=$('#extra_adult').val();
                var exChildBed=$('#extra_child_bed').val();
                var exChildWout=$('#extra_child_wout_bed').val();
                var bookingDetails=$('#booking_details').val();
                var status=$('#status').val();
                $.ajax({
                    url: "{{route('operations.booking.details.save')}}",
                    type:"post",
                    data:{
                        "_token": "{{ csrf_token() }}",
                        id:id,
                        single:single,
                        double:double,
                        extra_adult:exAdult,
                        extra_child_bed:exChildBed,
                        extra_child_wout_bed:exChildWout,
                        booking_details:bookingDetails,
                        status:status
                    },
                    success:function(response){
                        if(response.success){
                            $('#bookingModal').modal('hide');
                            swal("Good job!", response.success, "success");
                        }else{
                            swal("Oops!", response.error, "error");
                        }
                    },
                });
            });

            $('#vehicleBookingDetails').click(function(){
                var bookingId="{{$booking->id}}";
                var url="{{route('operations.vehicle.booking.details','ID')}}";
                url=url.replace('ID',bookingId);
                $.ajax({
                    url: url,
                    type:"get",
                    success:function(response){
                        if(response){
                            response=response.data;
                            $('#vehicle_purchase_rate').val(response.vehicle_purchase_rate);
                            $('#vendor').val(response.vendor);
                            $('#vehicle_booking_details').val(response.booking_details);
                            $('#vehicle_status').val(response.status);
                            $('#vehicleBookingModal').modal('show');
                        }else{
                            $('#vehicle_purchase_rate').val(0);
                            $('#vendor').val("");
                            $('#vehicle_booking_details').val("");
                            $('#vehicleBookingModal').modal('show');
                        }
                    },
                });
            });

            $('#vehicleBookingDetailsSave').click(function(){
                var bookingId="{{$booking->id}}";
                var quote_revision_details_id="{{$revision->id}}";
                var vehicle_purchase_rate=$('#vehicle_purchase_rate').val();
                var vendor=$('#vendor').val();
                var status=$('#vehicle_status').val();
                var vehicle_booking_details=$('#vehicle_booking_details').val();
                if(vehicle_purchase_rate>0 && vendor!="" && vehicle_booking_details!=""){
                    $.ajax({
                        url: "{{route('operations.vehicle.booking.details.save')}}",
                        type:"post",
                        data:{
                            "_token": "{{ csrf_token() }}",
                            booking_id:bookingId,
                            quote_revision_details_id:quote_revision_details_id,
                            vehicle_purchase_rate:vehicle_purchase_rate,
                            vendor:vendor,
                            booking_details:vehicle_booking_details,
                            status:status
                        },
                        success:function(response){
                            if(response.success){
                                $('#vehicleBookingModal').modal('hide');
                                swal("Good job!", response.success, "success");
                            }else{
                                swal("Oops!", response.error, "error");
                            }
                        },
                    });
                }else{
                    swal("Oops!", "Kindly fill the entire details!", "error");
                }
            });

            $('#addPayment').click(function(){
                $('#bank').val(0);
                $('#amount').val(0);
                $('#payment_details').val("");
                $('#addPaymentModal').modal('show');
            });

            $('#paymentDetailsSave').click(function(){
                var bank=$('#bank').val();
                var amount=$('#amount').val();
                var payment_details=$('#payment_details').val();
                var booking_id="{{$booking->id}}";
                var quotation_id="{{$booking->quotaion_id}}";
                if(amount>0 && payment_details!=null){
                    $.ajax({
                        url: "{{route('operations.payment-details.save')}}",
                        type:"post",
                        data:{
                            "_token": "{{ csrf_token() }}",
                            booking_id:booking_id,
                            quotation_id:quotation_id,
                            payment_details:payment_details,
                            amount:amount,
                            bank_id:bank,
                        },
                        success:function(response){
                            if(response.success){
                                $('#addPaymentModal').modal('hide');
                                swal("Good job!", response.success, "success");
                                window.location.reload();
                            }else{
                                swal("Oops!", response.error, "error");
                            }
                        },
                    });
                }else{
                    swal("Oops!", "Kindly fill the entire details!", "error");
                }
            });

            function viewPayment(details){
                $('#view_payment_details').val(details);
                $('#viewPaymentModal').modal('show');
            }

            $('#cancelBooking').click(function(){
                swal({
                    title: 'Cancel?',
                    text: "Are you sure, you cannot revert it?",
                    icon: 'warning',
                    buttons: true,
                    dangerMode: true,
                }).then((result) => {
                    if (result) {
                        $('#viewCancelModal').modal('show');
                    }
                })
            });

            $('#cancelBookingSave').click(function(){
                var note=$('#cancel_booking').val();
                if(note!=null && note!=""){
                    var bookingId="{{$booking->id}}";
                    $.ajax({
                        url: "{{route('operations.booking.cancel')}}",
                        type:"post",
                        data:{
                            "_token": "{{ csrf_token() }}",
                            id:bookingId,
                            note:note,
                        },
                        success:function(response){
                            if(response.success){
                                $('#viewCancelModal').modal('hide');
                                swal("Oops!", response.success, "success");
                                window.location.reload();
                            }else{
                                swal("Oops!", response.error, "error");
                            }
                        },
                    });
                }
            });

            $('#generateInvoice').click(function(){
                $('#invoiceModal').modal('show');
            });

            $('#prepareInvoice').click(function(){
                var gstType="gst";
                var booking_id="{{$booking->id}}";
                if($('#customRadio4').is(':checked')) { 
                    gstType="gst";
                }else{
                    gstType="non_gst";
                }
                var url="{{route('operations.invoice.show',['ID','CHOICE'])}}";
                url=url.replace('ID',booking_id);
                url=url.replace('CHOICE',gstType);
                window.location.href=url;
            });
        </script>
    @endsection