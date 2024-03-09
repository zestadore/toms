<div class="container-fluid">
    <!-- Info boxes -->
    <div class="row">
        <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-chart-pie"></i></span>

            <div class="info-box-content">
            <span class="info-box-text">Pending quotations</span>
            <span class="info-box-number">
                {{$pendingQuotationCount}}
            </span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box mb-3">
            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-building"></i></span>

            <div class="info-box-content">
            <span class="info-box-text">Pending Availabilities</span>
            <span class="info-box-number">{{$pendingAvailabilityCount}}</span> 
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
        </div>
        <!-- /.col -->

        <!-- fix for small devices only -->
        <div class="clearfix hidden-md-up"></div>

        <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box mb-3">
            <span class="info-box-icon bg-success elevation-1"><i class="fas fa-thumbs-up"></i></span>

            <div class="info-box-content">
            <span class="info-box-text">Pending Confirmations</span>
            <span class="info-box-number">{{$pendingBookingCount}}</span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box mb-3">
            <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-rupee-sign"></i></span>

            <div class="info-box-content">
            <span class="info-box-text">Pending(Approval) Payments</span>
            <span class="info-box-number">{{$pendingPaymentCount}}</span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->

    <div class="row">
        <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Monthly Payment Report</h5>
            </div>
            <!-- ./card-body -->
            <div class="card-footer">
            <div class="row">
                <div class="col-sm-3 col-6">
                <div class="description-block border-right">
                    {{-- <span class="description-percentage text-success"><i class="fas fa-caret-up"></i> 17%</span> --}}
                    <h5 class="description-header">{{$paymentsThisMonth}}</h5>
                    <span class="description-text">TOTAL PAYMENTS ADDED</span>
                </div>
                <!-- /.description-block -->
                </div>
                <!-- /.col -->
                <div class="col-sm-3 col-6">
                <div class="description-block border-right">
                    {{-- <span class="description-percentage text-warning"><i class="fas fa-caret-left"></i> 0%</span> --}}
                    <h5 class="description-header">{{$approvedPaymentsThisMonth}}</h5>
                    <span class="description-text">TOTAL PAYMENTS APPROVED</span>
                </div>
                <!-- /.description-block -->
                </div>
                <!-- /.col -->
                <div class="col-sm-3 col-6">
                <div class="description-block border-right">
                    {{-- <span class="description-percentage text-success"><i class="fas fa-caret-up"></i> 20%</span> --}}
                    <h5 class="description-header">{{$pendingPaymentsThisMonth}}</h5>
                    <span class="description-text">TOTAL PAYMENTS PENDING</span>
                </div>
                <!-- /.description-block -->
                </div>
                <!-- /.col -->
                <div class="col-sm-3 col-6">
                <div class="description-block">
                    {{-- <span class="description-percentage text-danger"><i class="fas fa-caret-down"></i> 18%</span> --}}
                    <h5 class="description-header">{{$rejectedPaymentsThisMonth}}</h5>
                    <span class="description-text">TOTAL PAYMENTS REJECTED</span>
                </div>
                <!-- /.description-block -->
                </div>
            </div>
            <!-- /.row -->
            </div>
            <!-- /.card-footer -->
        </div>
        <!-- /.card -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="info-box shadow" id="addMember">
                <span class="info-box-icon bg-warning"><i class="far fa-user"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">Add</span>
                  <span class="info-box-number">Members</span>
                </div>
                <!-- /.info-box-content -->
              </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="info-box shadow" id="addAgent">
                <span class="info-box-icon bg-warning"><i class="far fa-user"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">Add</span>
                  <span class="info-box-number">Agents</span>
                </div>
                <!-- /.info-box-content -->
              </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="info-box shadow" id="addDestination">
                <span class="info-box-icon bg-warning"><i class="fas fa-map-marker-alt"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">Add</span>
                  <span class="info-box-number">Destinations</span>
                </div>
                <!-- /.info-box-content -->
              </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="info-box shadow" id="addHotel">
                <span class="info-box-icon bg-warning"><i class="fas fa-building"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">Add</span>
                  <span class="info-box-number">Hotels / Resorts</span>
                </div>
                <!-- /.info-box-content -->
              </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="info-box shadow" id="addVehicle">
                <span class="info-box-icon bg-warning"><i class="fas fa-taxi"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">Add</span>
                  <span class="info-box-number">Vehicles</span>
                </div>
                <!-- /.info-box-content -->
              </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="info-box shadow" id="addItinerary">
                <span class="info-box-icon bg-warning"><i class="fas fa-map-signs"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">Add</span>
                  <span class="info-box-number">Itinerary</span>
                </div>
                <!-- /.info-box-content -->
              </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="info-box shadow" id="addQuotation">
                <span class="info-box-icon bg-warning"><i class="fas fa-wallet"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">Add</span>
                  <span class="info-box-number">Leads / Quotations</span>
                </div>
                <!-- /.info-box-content -->
              </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="info-box shadow" id="viewPayments">
                <span class="info-box-icon bg-warning"><i class="fas fa-rupee-sign"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">View</span>
                  <span class="info-box-number">Payments</span>
                </div>
                <!-- /.info-box-content -->
              </div>
        </div>
    </div>
</div><!--/. container-fluid -->
@section('scripts')
    <script>
        $('#addVehicle').click(function(){
            window.location.href = "{{ route('admin.vehicles.create') }}";
        });
        $('#addItinerary').click(function(){
            window.location.href = "{{ route('admin.itinerary.create') }}";
        });
        $('#addQuotation').click(function(){
            window.location.href = "{{ route('operations.quotations.create') }}";
        });
        $('#viewPayments').click(function(){
            window.location.href = "{{ route('admin.pending.payments') }}";
        });
        $('#addAgent').click(function(){
            window.location.href = "{{ route('admin.agents.create') }}";
        });
        $('#addDestination').click(function(){
            window.location.href = "{{ route('admin.destinations.create') }}";
        });
        $('#addHotel').click(function(){
            window.location.href = "{{ route('admin.hotels.create') }}";
        });
        $('#addMember').click(function(){
            window.location.href = "{{ route('admin.members.create') }}";
        });
    </script>
@endsection