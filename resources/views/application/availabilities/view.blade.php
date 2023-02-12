@php
    use Carbon\Carbon;
@endphp
@extends('layouts.app')
    @section('title')
        <title>TOMS | View availaility</title>
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
            <h1 class="m-0">Quotations</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{route('operations.availabilities.index')}}">Availability</a></li>
            <li class="breadcrumb-item active">View availability</li>
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
                        <i class="fas fa-plus-circle"></i>
                        View availability
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
                            </tr>
                            @foreach ($revision->revisionDetails as $item)
                                <tr>
                                    <td>{{Carbon::parse($item->checkin)->format('d-M-Y')}}</td>
                                    <td>{{$item->destination->destination}}</td>
                                    <td>{{$item->hotel->hotel}}</td>
                                    <td>{{$item->roomCategory->room_category}}</td>
                                </tr>
                            @endforeach
                        </table>
                        <h5>Vehicle details</h5>
                        <table class="table table-bordered">
                            <tr>
                                <td>Vehicle : {{$revision->vehicle->vehicle_name}}</td>
                                <td>Days : {{$revision->no_nights + 1}}</td>
                                <td>Allowed Km : {{$revision->allowed_kms}}</td>
                            </tr>
                        </table>
                        {{-- <h5>Pricing</h5>
                        <table class="table table-bordered">
                            <tr>
                                <td>Accomodation : &#x20b9; {{$revision->accomodation_cost}}</td>
                                <td>Transportation : &#x20b9; {{$revision->vehicle_rate}}</td>
                                <td>Grand total : &#x20b9; {{$revision->grand_total}}</td>
                                <td>GST : &#x20b9; {{$revision->gst_amount}}</td>
                                <td>Disount : &#x20b9; {{$revision->discount_amount}}</td>
                                <td>Markup : &#x20b9; {{$revision->markup_amount}}</td>
                                <td style="color:green;background:yellow;font-style:italic;">Net rate : &#x20b9; {{$revision->net_rate}}</td>
                            </tr>
                        </table> --}}
                        <hr>
                        @foreach ($notes as $item)
                            <table class="table table-bordered">
                                <tr>
                                    <td>{{$item->title}}</td>
                                </tr>
                                <tr>
                                    <td>
                                        {!!$item->description!!}
                                    </td>
                                </tr>
                            </table>
                        @endforeach
                        <br><div>
                            <div class="btn-group btn-group-toggle" data-toggle="buttons" style="float:right;">
                                <label class="btn bg-info">
                                  <input type="radio" name="options" autocomplete="off" checked="" id="viewMessage"> View message
                                </label>
                                <label class="btn bg-info">
                                    <input type="radio" name="options" autocomplete="off" checked="" id="submitAvailability"> Submit availability
                                  </label>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                  </div><br>
            </div><!--/. container-fluid -->
        </section>
        <div class="modal fade" id="modal-availability-message">
            <div class="modal-dialog modal-lg">
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title">Availability</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                    <div id="availabilityMessage"></div>
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
          <div class="modal fade" id="modal-availability">
            <div class="modal-dialog modal-lg">
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title">Availability</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                    <x-forms.input class="form-control {{ $errors->has('availability_note') ? ' is-invalid' : '' }}" title="Note" name="availability_note" id="availability_note" type="textarea" required="False"/>
                </div>
                <div class="modal-footer justify-content-between">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-primary" id="reportAvailability" data-id=0>Submit availability</button>
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
            $('#viewMessage').click(function(){
                $('#availabilityMessage').html('{!!$availability->primary_note!!}');
                $('#modal-availability-message').modal('show');
            });
            $('#submitAvailability').click(function(){
                $('#modal-availability').modal('show');
            });
            $('#availability_note').summernote({
                toolbar: [
                    // [groupName, [list of button]]
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['font', ['strikethrough', 'superscript', 'subscript']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['height', ['height']],
                    [ 'insert', [ 'link'] ],
                    [ 'table', [ 'table' ] ],
                ]
            });

            $('#reportAvailability').click(function(){
                var revision_id='{{$revision->id}}';
                var availability_note=$('#availability_note').val();
                $.ajax({
                    url: "{{route('operations.availability.report')}}",
                    type:"post",
                    data:{
                        "_token": "{{ csrf_token() }}",
                        revision_id:revision_id,
                        availability_note:availability_note
                    },
                    success:function(response){
                        if(response.success){
                            swal("Good job!", "Successfully submitted availability!", "success");
                            drawTable();
                        }else{
                            swal("Oops!", response.error, "error");
                        }
                    },
                });
                $('#modal-availability').modal('hide');
            });
        </script>
    @endsection