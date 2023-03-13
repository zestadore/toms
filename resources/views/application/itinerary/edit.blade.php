@extends('layouts.app')
    @section('title')
        <title>TOMS | Itinerary</title>
    @endsection
    @section('css')
        <link rel="stylesheet" href="{{asset('assets/admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/admin/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/admin/plugins/toastr/toastr.min.css')}}">
    @endsection
    @section('breadcrump')
        <div class="col-sm-6">
            <h1 class="m-0">Itinerary</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{route('admin.itinerary.index')}}">Itinerary</a></li>
            <li class="breadcrumb-item active">Edit itinerary</li>
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
                        <i class="fas fa-map-signs"></i>
                        Edit itinerary
                      </h3>
                    </div>
                    <div class="card-body">
                        <form action="{{route('admin.itinerary.update',$data->id)}}" method="post" id="addNewForm">@csrf
                            @method('put')
                            <div class="row">
                                <div class="col-lg-6 col-sm-6 col-md-12 col-xs-12">
                                    <x-forms.input class="form-control {{ $errors->has('title') ? ' is-invalid' : '' }}" title="Title" name="title" id="title" type="text" required="True"/>
                                </div>
                                <div class="col-lg-6 col-sm-6 col-md-12 col-xs-12">
                                    <div class="form-group">
                                        <label>Destination <span style="color:red;">*</span></label>
                                        <select class="form-control select2bs4" name="destination_id" id="destination_id" style="width: 100%;" required>
                                          <option value="">Select a destination</option>
                                          @foreach ($destinations as $item)
                                          @if ($data->destination_id==Crypt::decrypt($item->id))
                                            <option value="{{$item->id}}" selected>{{$item->destination}}</option>
                                          @else
                                            <option value="{{$item->id}}">{{$item->destination}}</option>
                                          @endif
                                              
                                          @endforeach
                                        </select>
                                        @error('destination_id')
                                            <span class="error mt-2 text-danger" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <x-forms.input class="form-control {{ $errors->has('itinerary') ? ' is-invalid' : '' }}" title="Itinerary" name="itinerary" id="itinerary" type="textarea" required="True"/>
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
                        title: {
                            required: true
                        },
                        destination_id: {
                            required: true
                        },
                        itinerary: {
                            required: true
                        },
                    },
                    messages: {
                        title: {
                            required: "Please enter the title"
                        },
                        destination_id: {
                            required: "Please select destination"
                        },
                        itinerary: {
                            required: "Please enter itinerary"
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
                $('#title').val('{{$data->title}}');
                // $('#destination_id').val("{{Crypt::encrypt($data->destination_id)}}");
                $('#itinerary').val('{{$data->itinerary}}');
                var status='{{$data->status}}';
                if(status==0){
                    $("#customSwitch1").prop('checked', false);
                }else{
                    $("#customSwitch1").prop('checked', true);
                }
            }
            prefillForm();
        </script>
    @endsection