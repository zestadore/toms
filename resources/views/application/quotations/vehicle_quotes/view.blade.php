@php
    use Carbon\Carbon;
@endphp
@extends('layouts.app')
    @section('title')
        <title>TOMS | Vehicle Quotation</title>
    @endsection
    @section('css')
        <link rel="stylesheet" href="{{asset('assets/admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/admin/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/admin/plugins/toastr/toastr.min.css')}}">
        <meta name="csrf_token" content="{{ csrf_token() }}" />
    @endsection
    @section('breadcrump')
        <div class="col-sm-6">
            <h1 class="m-0">Quotations</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{route('operations.quotations.index')}}">Vehicle Quotation</a></li>
            <li class="breadcrumb-item active">View</li>
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
                        <i class="fas fa-wallet"></i>
                        View package
                      </h3>
                      <div class="btn-group" style="float:right;">
                            <button type="button" class="btn btn-default">Mailable formats</button>
                            <button type="button" class="btn btn-default dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false">
                            <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <div class="dropdown-menu" role="menu" style="">
                                <a class="dropdown-item" id="mailableFormat" href="#">Whatsapp</a>
                            </div>
                      </div>
                      {{-- <button class="btn btn-info" id="mailableFormat" style="float:right;">Mailable format</button> --}}
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info alert-dismissible">
                            <h5><i class="icon fas fa-star"></i> Agent :  | Guest : </h5>
                        </div>
                        <br>
                        @if (count($quote->details)>0)
                            <h5>Itinerary</h5>
                            <table class="table table-bordered">
                                @foreach ($quote->details as $item)
                                    <tr>
                                        <span>Day {{$loop->iteration}} | Date : {{Carbon::parse($item?->checkin)->format('d-M-Y')}}</span>
                                        <p><span>Destination : {{$item->destination?->destination}} </span></p>
                                        <div>
                                            {{getItinerary($item?->itinerary_id)}}
                                        </div>
                                        <table width="50%">
                                            <tr>
                                                <td>
                                                    <img src="{{$item?->destination?->image_path}}" alt="" class="image-responsive" width="100px">
                                                </td>
                                            </tr>
                                        </table>
                                        <br>
                                    </tr>
                                @endforeach
                            </table>
                        @endif
                        <h5>Vehicle details</h5>
                        <table class="table table-bordered">
                            <tr>
                                <td>Vehicle : {{$quote->vehicle->vehicle_name}}</td>
                                <td>Days : {{$quote->nights + 1}}</td>
                                <td>Allowed Km : {{$quote->kms_blocked}}</td>
                            </tr>
                        </table>
                        <h5>Pricing</h5>
                        <table class="table table-bordered">
                            <tr>
                                <td>Transportation : &#x20b9; {{$quote->net_rate}}</td>
                            </tr>
                        </table>
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
                    </div>
                    <!-- /.card-body -->
                  </div><br>
            </div><!--/. container-fluid -->
        </section>
        <div class="modal fade" id="modal-whatsapp">
            <div class="modal-dialog modal-sm">
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title">Whatsapp format</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                    {{$quote->vehicle->vehicle_name}}<br>
                    {{$quote->nights}} Nights | {{$quote->nights + 1}} Days<br>
                    Allowed Km : <b>{{$quote->kms_blocked}}</b><br>
                    Net Rate : <b>&#x20b9; {{$quote->net_rate}}/-</b>
                </div>
                <div class="modal-footer justify-content-between">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
              </div>
              <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
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
        <script>
            $('#mailableFormat').click(function(){
                $('#modal-whatsapp').modal('show');
            });
            $("body").removeClass("dark-mode");
        </script>
    @endsection