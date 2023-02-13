@extends('layouts.app')
    @section('title')
        <title>TOMS | Quotations</title>
    @endsection
    @section('css')
        <link rel="stylesheet" href="{{asset('assets/admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/admin/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/admin/plugins/toastr/toastr.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/admin/plugins/summernote/summernote-bs4.min.css')}}">
        <style>
            #statusAvailability {
                margin: 4px, 4px;
                padding: 4px;
                width: 750px;
                overflow-x: auto;
                overflow-y: hidden;
                white-space: nowrap;
            }
        </style>
    @endsection
    @section('breadcrump')
        <div class="col-sm-6">
            <h1 class="m-0">Quote revisions</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{route('operations.quotations.index')}}">Quotations</a></li>
            <li class="breadcrumb-item active">Quote revisions</li>
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
                        Quote revisions
                      </h3>
                        <button type="button" class="btn btn-outline-info mr-1 mb-3 btn-sm" id="add-new" style="float:right;">
                            <i class="fa fa-fw fa-plus mr-1"></i> Add New
                        </button>
                    </div>
                    <div class="card-body">
                        {{-- <form id="filterfordatatable" class="form-horizontal" onsubmit="event.preventDefault();">
                            <div class="row ">
                                <div class="col">
                                    <input type="text" name="search" class="form-control" placeholder="Search with agent">
                                </div>
                            </div>
                        </form><br> --}}
                        <table class="table table-bordered table-striped" id="item-table">
                            <thead>
                                <tr>
                                    <th class="nosort">#</th>
                                    <th>{{ __('Rev id') }}</th>
                                    <th>{{ __('Date') }}</th>
                                    <th>{{ __('Duration') }}</th>
                                    <th>{{ __('Meal plan') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th class="nosort">Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <!-- /.card-body -->
                  </div><br>
            </div><!--/. container-fluid -->
        </section>
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
                    <x-forms.input class="form-control {{ $errors->has('primary_note') ? ' is-invalid' : '' }}" title="Note" name="primary_note" id="primary_note" type="textarea" required="False"/>
                </div>
                <div class="modal-footer justify-content-between">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-primary" id="askAvailability" data-id=0>Request availability</button>
                </div>
              </div>
              <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
          <!-- /.modal -->
          <div class="modal fade" id="availability-status">
            <div class="modal-dialog modal-lg">
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title">Availability</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                    <div id="statusAvailability"></div>
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
        <script src="{{asset('assets/admin/plugins/datatables/jquery.dataTables.min.js')}}"></script>
        <script src="{{asset('assets/admin/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
        <script src="{{asset('assets/admin/plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
        <script src="{{asset('assets/admin/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
        <script src="{{asset('assets/admin/plugins/datatables-buttons/js/dataTables.buttons.min.js')}}"></script>
        <script src="{{asset('assets/admin/plugins/datatables-buttons/js/buttons.bootstrap4.min.js')}}"></script>
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
        <script src="{{asset('assets/admin/plugins/summernote/summernote-bs4.min.js')}}"></script>
        <script>
            function drawTable()
            {
                var url='{{route("operations.quotations.show","ID")}}'
                url=url.replace('ID','{{$quote_id}}');
                var table = $('#item-table').DataTable({
                    processing: true,
                    serverSide: true,
                    destroy: true,
                    // responsive: true,
                    buttons: [],
                    "pagingType": "full_numbers",
                    "dom": "<'row'<'col-sm-12 col-md-12 right'B>><'row'<'col-sm-12'tr>><'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                    ajax: {
                        "url": url,
                        "data": function(d) {
                            var searchprams = $('#filterfordatatable').serializeArray();
                            var indexed_array = {};

                            $.map(searchprams, function(n, i) {
                                indexed_array[n['name']] = n['value'];
                            });
                            return $.extend({}, d, indexed_array);
                        },
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'name'
                        },
                        {
                            data: 'rev_id',
                            name: 'rev_id'
                        },
                        {
                            data: 'arrival_date',
                            name: 'arrival_date'
                        },
                        {
                            data: 'no_nights',
                            name: 'no_nights'
                        },
                        {
                            data: 'meal_plan',
                            name: 'meal_plan'
                        },
                        {
                            data: 'status',
                            name: 'status',
                            render: function(data) {
                                if (data ==1) {
                                    return "<span class='badge badge-primary'>Quotation</span>";
                                }else if(data ==0){
                                    return "<span class='badge badge-warning'>Pending</span>";
                                }else if(data ==2){
                                    return "<span class='badge badge-success'>Confirmed</span>";
                                }else if(data ==4){
                                    return "<span class='badge badge-warning'>Availability</span>";
                                }else{
                                    return "<span class='badge badge-danger'>Cancelled</span>";
                                }
                                
                            }
                        },
                        {
                            data: 'action',
                            name: 'action'
                        }
                    ],

                    'aoColumnDefs': [{
                        'bSortable': false,
                        'aTargets': ['nosort']
                    }]

                });

                $.fn.DataTable.ext.pager.numbers_length = 5;
                $('#filterfordatatable').change(function() {
                    table.draw();
                });
            }
            drawTable();

            function editData(id){
                var url="{{route('operations.quotations.edit','ID')}}";
                url=url.replace('ID',id);
                window.location.href=url;
            }

            function deleteData(id)
            {
                swal({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                buttons: true,
                dangerMode: true,
                }).then((result) => {
                    if (result) {
                        var url="{{route('operations.quotations.destroy','ID')}}";
                        url=url.replace('ID',id);
                        $.ajax({
                            url: url,
                            type:"delete",
                            data:{
                                "_token": "{{ csrf_token() }}",
                            },
                            success:function(response){
                                console.log(response);
                                if(response.success){
                                    swal("Good job!", "You deleted the data!", "success");
                                    drawTable();
                                }else{
                                    swal("Oops!", "Failed to deleted the data!", "danger");
                                }
                            },
                        });
                    }
                })
            }

            $('#add-new').click(function(){
                var url="{{route('operations.quote-revisions.create','ID')}}";
                url=url.replace('ID','{{$quote_id}}');
                window.location.href=url;
            });

            function gotoCalculations(id){
                var url="{{route('operations.revision.calculation','ID')}}";
                url=url.replace('ID',id);
                window.location.href=url;
            }

            function gotoViewRevision(id){
                var url="{{route('operations.revision.calculation.view','ID')}}";
                url=url.replace('ID',id);
                window.location.href=url;
            }

            function askAvailability(id){
                $('#askAvailability').attr('data-id',id);
                $('#modal-availability').modal('show');
            }

            $('#askAvailability').click(function(){
                var revision_id=$('#askAvailability').data('id');
                var primary_note=$('#primary_note').val();
                $.ajax({
                    url: "{{route('operations.availability.ask')}}",
                    type:"post",
                    data:{
                        "_token": "{{ csrf_token() }}",
                        revision_id:revision_id,
                        primary_note:primary_note
                    },
                    success:function(response){
                        if(response.success){
                            swal("Good job!", "Successfully requested for availability!", "success");
                            drawTable();
                        }else{
                            swal("Oops!", response.error, "error");
                        }
                    },
                });
                $('#modal-availability').modal('hide');
            });

            function copyRevision(id){
                swal({
                    title: 'Copying?',
                    text: "Do you want a copy of this revision?",
                    icon: 'warning',
                    buttons: true,
                    dangerMode: true,
                }).then((result) => {
                    if (result) {
                        var url="{{route('operations.revision.copy','ID')}}";
                        url=url.replace('ID',id);
                        $.ajax({
                            url: url,
                            type:"post",
                            data:{
                                "_token": "{{ csrf_token() }}",
                            },
                            success:function(response){
                                console.log(response);
                                if(response.success){
                                    swal("Good job!", "You have created a copy of the revision!", "success");
                                    drawTable();
                                }else{
                                    swal("Oops!", "Failed to copy the revision!", "danger");
                                }
                            },
                        });
                    }
                })
            }

            function availabilityStatus(result){
                $('#statusAvailability').html(result);
                $('#availability-status').modal('show');
            }

            $('#primary_note').summernote({
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

        </script>
    @endsection