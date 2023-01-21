@extends('layouts.app')
    @section('title')
        <title>TOMS | Quote revision</title>
    @endsection
    @section('css')
        <link rel="stylesheet" href="{{asset('assets/admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/admin/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/admin/plugins/toastr/toastr.min.css')}}">
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
            <li class="breadcrumb-item active">Create revision</li>
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
                        Create revision
                      </h3>
                    </div>
                    <div class="card-body">
                        <form action="{{route('operations.quote-revisions.save')}}" method="post" id="addNewForm">@csrf
                            <input type="hidden" name="quote_id" id="quote_id" value="{{$quote_id}}">
                            <div class="row">
                                <div class="col-lg-6 col-sm-6 col-md-12 col-xs-12">
                                    <x-forms.input class="form-control {{ $errors->has('arrival_date') ? ' is-invalid' : '' }}" title="Arrival" name="arrival_date" id="arrival_date" type="date" required="True"/>
                                </div>
                                <div class="col-lg-6 col-sm-6 col-md-12 col-xs-12">
                                    <x-forms.input class="form-control {{ $errors->has('no_nights') ? ' is-invalid' : '' }}" title="No of nights" name="no_nights" id="no_nights" type="number" required="True"/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4 col-sm-4 col-md-12 col-xs-12">
                                    <x-forms.input class="form-control {{ $errors->has('adults') ? ' is-invalid' : '' }}" title="Adults" name="adults" id="adults" type="number" required="True"/>
                                </div>
                                <div class="col-lg-4 col-sm-4 col-md-12 col-xs-12">
                                    <x-forms.input class="form-control {{ $errors->has('kids') ? ' is-invalid' : '' }}" title="Children" name="kids" id="kids" type="number" required="True"/>
                                </div>
                                <div class="col-lg-4 col-sm-4 col-md-12 col-xs-12">
                                    <div class="form-group">
                                        <label for="mea_plan">Meal <span style="color:red;">*</span></label>
                                        <select name="meal_plan" id="meal_plan" class="form-control" required>
                                            <option value="CP">CP</option>
                                            <option value="EP">EP</option>
                                            <option value="MAP">MAP</option>
                                            <option value="AP">AP</option>
                                        </select>
                                        @error('meal_plan')
                                            <span class="error mt-2 text-danger" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4 col-sm-4 col-md-12 col-xs-12">
                                    <x-forms.input class="form-control {{ $errors->has('sgl_rooms') ? ' is-invalid' : '' }}" title="Single rooms" name="sgl_rooms" id="sgl_rooms" type="number" required="True"/>
                                </div>
                                <div class="col-lg-4 col-sm-4 col-md-12 col-xs-12">
                                    <x-forms.input class="form-control {{ $errors->has('dbl_rooms') ? ' is-invalid' : '' }}" title="Double rooms" name="dbl_rooms" id="dbl_rooms" type="number" required="True"/>
                                </div>
                                <div class="col-lg-4 col-sm-4 col-md-12 col-xs-12">
                                    <x-forms.input class="form-control {{ $errors->has('ex_bed_adults') ? ' is-invalid' : '' }}" title="Ex bed(adults)" name="ex_bed_adults" id="ex_bed_adults" type="number" required="True"/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 col-sm-6 col-md-12 col-xs-12">
                                    <x-forms.input class="form-control {{ $errors->has('ex_bed_children') ? ' is-invalid' : '' }}" title="Ex bed(Children)" name="ex_bed_children" id="ex_bed_children" type="number" required="True"/>
                                </div>
                                <div class="col-lg-6 col-sm-6 col-md-12 col-xs-12">
                                    <x-forms.input class="form-control {{ $errors->has('ex_children_wout') ? ' is-invalid' : '' }}" title="Ex children(W/Out bed)" name="ex_children_wout" id="ex_children_wout" type="number" required="True"/>
                                </div>
                            </div>
                            <x-forms.input class="form-control {{ $errors->has('note') ? ' is-invalid' : '' }}" title="Note" name="note" id="note" type="textarea" required="False"/>
                            <button type="button" class="btn btn-info" style="float:right;" id="submitForm">Next</button>
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
        <!-- date-range-picker -->
        <script src="{{asset('assets/admin/plugins/moment/moment.min.js')}}"></script>
        <script src="{{asset('assets/admin/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')}}"></script>
        <!-- Toastr -->
        <script src="{{asset('assets/admin/plugins/toastr/toastr.min.js')}}"></script>
        <script>
            
            $(function () {
                $('#addNewForm').validate({
                    rules: {
                        agent_id: {
                            required: true
                        },
                        assigned_to: {
                            required: true
                        },
                    },
                    messages: {
                        agent_id: {
                            required: "Please select the agent"
                        },
                        assigned_to: {
                            required: "Please select the assignee"
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

            $('#arrival_date').datetimepicker({
                format: 'L'
            });

            function resetForm(){
                $('#adults').val(0);
                $('#kids').val(0);
                $('#sgl_rooms').val(0);
                $('#dbl_rooms').val(0);
                $('#ex_bed_adults').val(0);
                $('#ex_adults_wout').val(0);
                $('#ex_bed_children').val(0);
                $('#ex_children_wout').val(0);
            }

            resetForm();

            $('#submitForm').click(function(){
                var sgl=parseInt($('#sgl_rooms').val());
                var dbl=parseInt($('#dbl_rooms').val());
                var tot=sgl+dbl;
                if(tot>0){
                    $('#addNewForm').submit();
                }else{
                    toastr.error('Kindly check the number of rooms.')
                }
                // $('#addNewForm').submit();
            });

        </script>
    @endsection