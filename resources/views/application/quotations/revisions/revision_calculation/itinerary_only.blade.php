@php
    use Carbon\Carbon;
@endphp
@extends('layouts.app')
    @section('title')
        <title>TOMS | Quote revision</title>
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
            <li class="breadcrumb-item"><a href="{{route('operations.quotations.index')}}">Quotations</a></li>
            <li class="breadcrumb-item"><a href="{{route('operations.quotations.show',$quote_id)}}">Quote revisions</a></li>
            <li class="breadcrumb-item active">View package</li>
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
                                    {{-- Meal plan : {{$revision->meal_plan}}<br> --}}
                                </div>
                            </div>
                        </div>
                        <br>
                        @if (count($revision->revisionDetails)>0)
                            <h5>Itinerary</h5>
                            <table class="table table-bordered">
                                @foreach ($revision->revisionDetails as $item)
                                    <tr>
                                        <span>Day {{$loop->iteration}} | Date : {{Carbon::parse($item?->checkin)->format('d-M-Y')}}</span>
                                        <p><span>Destination : {{$item->destination->destination}} </span></p>
                                        <div>
                                            {{getItinerary($item?->itinerary_id)}}
                                        </div>
                                        <table width="50%">
                                            <tr>
                                                <td>
                                                    <img src="{{$item?->destination?->image_path}}" alt="" class="image-responsive" width="100px">
                                                </td>
                                                <td>
                                                    <img src="{{$item?->hotel?->image_path}}" alt="" class="image-responsive" width="100px">
                                                </td>
                                                <td>
                                                    <img src="{{$item?->roomCategory?->image_path}}" alt="" class="image-responsive" width="100px">
                                                </td>
                                            </tr>
                                        </table>
                                        <br>
                                    </tr>
                                @endforeach
                            </table>
                        @endif
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
            $("body").removeClass("dark-mode");
        </script>
    @endsection