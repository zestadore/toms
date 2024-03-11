@extends('layouts.app')
    @section('title')
        <title>TOMS | Payments Report</title>
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
            <h1 class="m-0">Payments Report</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
            <li class="breadcrumb-item active">Reports</li>
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
                        Payments Report
                      </h3>
                    </div>
                    <div class="card-body">
                        <form id="filterfordatatable" class="form-horizontal" onsubmit="event.preventDefault();">
                            <div class="row ">
                                {{-- <div class="col">
                                    <input type="text" name="search" class="form-control" placeholder="Search with revision id">
                                </div> --}}
                                {{-- <div class="col">
                                    <select name="status_search" id="status_search" class="form-control">
                                        <option value="">Search with status</option>
                                        <option value=0>Pending</option>
                                        <option value=1>Confirmed</option>
                                        <option value=2>Payment Received</option>
                                        <option value=3>Cancelled</option>
                                    </select>
                                </div> --}}
                                <div class="col">
                                    <input type="date" name="from_date" class="form-control" placeholder="From date">
                                </div>
                                <div class="col">
                                    <input type="date" name="to_date" class="form-control" placeholder="To date">
                                </div>
                            </div>
                        </form><br>
                        <table class="table table-bordered table-striped" id="item-table">
                            <thead>
                                <tr>
                                    <th class="nosort">#</th>
                                    <th>{{ __('Guest name') }}</th>
                                    <th>{{ __('Agent') }}</th>
                                    <th>{{ __('Bank') }}</th>
                                    <th>{{ __('Amount') }}</th>
                                    {{-- <th>{{ __('Requested') }}</th> --}}
                                    <th>{{ __('Status') }}</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <!-- /.card-body -->
                  </div><br>
            </div><!--/. container-fluid -->
        </section>
    @endsection
    @section('scripts')
        <script src="{{asset('assets/admin/plugins/datatables/jquery.dataTables.min.js')}}"></script>
        <script src="{{asset('assets/admin/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
        <script src="{{asset('assets/admin/plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
        <script src="{{asset('assets/admin/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
        <script src="{{asset('assets/admin/plugins/datatables-buttons/js/dataTables.buttons.min.js')}}"></script>
        <script src="{{asset('assets/admin/plugins/datatables-buttons/js/buttons.bootstrap4.min.js')}}"></script>
        <script src="{{asset('assets/admin/plugins/jszip/jszip.min.js')}}"></script>
        <script src="{{asset('assets/admin/plugins/pdfmake/pdfmake.min.js')}}"></script>
        <script src="{{asset('assets/admin/plugins/pdfmake/vfs_fonts.js')}}"></script>
        <script src="{{asset('assets/admin/plugins/datatables-buttons/js/buttons.html5.min.js')}}"></script>
        <script>
            function drawTable()
            {
                var url='{{route("payments.report")}}'
                var table = $('#item-table').DataTable({
                    processing: true,
                    serverSide: true,
                    destroy: true,
                    // responsive: true,
                    buttons: ['copy', 'csv', 'excel', 'pdf'],
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
                            data: 'guest_name',
                            name: 'guest_name'
                        },
                        {
                            data: 'agent_name',
                            name: 'agent_name'
                        },
                        {
                            data: 'bank_name',
                            name: 'bank_name'
                        },
                        {
                            data: 'amount',
                            name: 'amount'
                        },
                        {
                            data: 'status',
                            name: 'status',
                            render: function(data) {
                                if (data==0) {
                                    return "<span class='badge badge-info'>Pending</span>";
                                }else if(data==1){
                                    return "<span class='badge badge-success'>Approved</span>";
                                }else if(data==2){
                                    return "<span class='badge badge-danger'>Rejected</span>";
                                }
                                
                            }
                        },
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
           
        </script>
    @endsection