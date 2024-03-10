<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  @yield('title')
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="{{asset('assets/admin/plugins/fontawesome-free/css/all.min.css')}}">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="{{asset('assets/admin/plugins/overlayScrollbars/css/OverlayScrollbars.min.css')}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{asset('assets/admin/dist/css/adminlte.min.css')}}">
  <link rel="stylesheet" href="{{asset('assets/admin/dist/css/loader.css')}}">
  @yield('css')
</head>
<body class="hold-transition dark-mode sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
  <div id="loader"></div>
<div class="wrapper">

  <!-- Preloader -->
  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__wobble" src="{{asset('assets/admin/images/bag-logo.png')}}" alt="BagLogo" height="100" width="100">
  </div>

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-dark">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <a href="Javascript::void()" class="h1"><img src="{{asset('assets/toms.png')}}" width=50% style="float:right;"></a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-user"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-item dropdown-header">Hi, {{Auth::user()->first_name}} {{Auth::user()->last_name}}<p>{{strtoupper(Auth::user()->role)}} </p></span>
          <div class="dropdown-divider"></div>
          <a href="{{route('profile')}}" class="dropdown-item">
            <i class="fas fa-user mr-2"></i> Profile
          </a>
          <div class="dropdown-divider"></div>
          <a href="{{route('change.password')}}" class="dropdown-item">
            <i class="fas fa-pencil-alt mr-2"></i> Change Password
          </a>
          <div class="dropdown-divider"></div>
          <a href="javascript::void(0)" onclick="event.preventDefault();document.getElementById('logout-form').submit();" class="dropdown-item">
            <i class="fas fa-user-lock mr-2"></i> Logout
          </a>
          <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
            @csrf
          </form>
        </div>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link" style="text-align: center;">
      <img src="{{asset('assets/admin/images/logo-white2.png')}}" width=60%>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          @if (Auth::user()->image==null)
            <img src="{{asset('assets/admin/dist/img/user2-160x160.jpg')}}" class="img-circle elevation-2" alt="User Image">
          @else
            <img src="{{Auth::user()->image_path}}" class="img-circle elevation-2" alt="User Image" width=50 height=50>
          @endif
        </div>
        <div class="info">
          <a href="#" class="d-block">{{Auth::user()->first_name}} {{Auth::user()->last_name}}</a>
        </div>
      </div>
      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
               <li class="nav-item">
                <a href="{{route('dashboard')}}" class="nav-link {{ (request()->is('admin/dashboard*') || request()->is('user/dashboard*'))? 'active' : '' }}">
                  <i class="nav-icon fas fa-tachometer-alt"></i>
                  <p>
                    Dashboard
                  </p>
                </a>
              </li>
              <li class="nav-header">MASTERS</li>
              @canany(['isAdmin'])
                <li class="nav-item {{ (request()->is('admin/members*'))? 'menu-open' : '' }}">
                  <a href="#" class="nav-link {{ (request()->is('admin/members*'))? 'active' : '' }}">
                    <i class="nav-icon fas fa-user"></i>
                    <p>
                      Members
                      <i class="right fas fa-angle-left"></i>
                    </p>
                  </a>
                  <ul class="nav nav-treeview">
                    <li class="nav-item">
                      <a href="{{route('admin.members.index')}}" class="nav-link {{ (request()->is('admin/members'))? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>List</p>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a href="{{route('admin.members.create')}}" class="nav-link {{ (request()->is('admin/members/create'))? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Create</p>
                      </a>
                    </li>
                  </ul>
                </li>
              @endcanany
              <li class="nav-item {{ (request()->is('admin/agents*'))? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ (request()->is('admin/agents*'))? 'active' : '' }}">
                  <i class="nav-icon fas fa-users"></i>
                  <p>
                    Agents
                    <i class="right fas fa-angle-left"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="{{route('admin.agents.index')}}" class="nav-link {{ (request()->is('admin/agents'))? 'active' : '' }}">
                      <i class="far fa-circle nav-icon"></i>
                      <p>List</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{route('admin.agents.create')}}" class="nav-link {{ (request()->is('admin/agents/create'))? 'active' : '' }}">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Create</p>
                    </a>
                  </li>
                </ul>
              </li>
              <li class="nav-item {{ (request()->is('admin/destinations*'))? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ (request()->is('admin/destinations*'))? 'active' : '' }}">
                  <i class="nav-icon fas fa-map-marker-alt"></i>
                  <p>
                    Destinations
                    <i class="right fas fa-angle-left"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="{{route('admin.destinations.index')}}" class="nav-link {{ (request()->is('admin/destinations'))? 'active' : '' }}">
                      <i class="far fa-circle nav-icon"></i>
                      <p>List</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{route('admin.destinations.create')}}" class="nav-link {{ (request()->is('admin/destinations/create'))? 'active' : '' }}">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Create</p>
                    </a>
                  </li>
                </ul>
              </li>
              @canany(['isAdmin'])
                <li class="nav-item {{ (request()->is('admin/categories*'))? 'menu-open' : '' }}">
                  <a href="#" class="nav-link {{ (request()->is('admin/categories*'))? 'active' : '' }}">
                    <i class="nav-icon fas fa-project-diagram"></i>
                    <p>
                      Categories
                      <i class="right fas fa-angle-left"></i>
                    </p>
                  </a>
                  <ul class="nav nav-treeview">
                    <li class="nav-item">
                      <a href="{{route('admin.categories.index')}}" class="nav-link {{ (request()->is('admin/categories'))? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>List</p>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a href="{{route('admin.categories.create')}}" class="nav-link {{ (request()->is('admin/categories/create'))? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Create</p>
                      </a>
                    </li>
                  </ul>
                </li>
                <li class="nav-item {{ (request()->is('admin/hotels*'))? 'menu-open' : '' }}">
                  <a href="#" class="nav-link {{ (request()->is('admin/hotels*'))? 'active' : '' }}">
                    <i class="nav-icon fas fa-bed"></i>
                    <p>
                      Hotels/Resorts
                      <i class="right fas fa-angle-left"></i>
                    </p>
                  </a>
                  <ul class="nav nav-treeview">
                    <li class="nav-item">
                      <a href="{{route('admin.hotels.index')}}" class="nav-link {{ (request()->is('admin/hotels'))? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>List</p>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a href="{{route('admin.hotels.create')}}" class="nav-link {{ (request()->is('admin/hotels/create'))? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Create</p>
                      </a>
                    </li>
                  </ul>
                </li>
                <li class="nav-item {{ (request()->is('admin/vehicles*'))? 'menu-open' : '' }}">
                  <a href="#" class="nav-link {{ (request()->is('admin/vehicles*'))? 'active' : '' }}">
                    <i class="nav-icon fas fa-taxi"></i>
                    <p>
                      Vehicles
                      <i class="right fas fa-angle-left"></i>
                    </p>
                  </a>
                  <ul class="nav nav-treeview">
                    <li class="nav-item">
                      <a href="{{route('admin.vehicles.index')}}" class="nav-link {{ (request()->is('admin/vehicles'))? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>List</p>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a href="{{route('admin.vehicles.create')}}" class="nav-link {{ (request()->is('admin/vehicles/create'))? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Create</p>
                      </a>
                    </li>
                  </ul>
                </li>
              @endcanany
              <li class="nav-item {{ (request()->is('admin/itinerary*'))? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ (request()->is('admin/itinerary*'))? 'active' : '' }}">
                  <i class="nav-icon fas fa-map-signs"></i>
                  <p>
                    Itinerary
                    <i class="right fas fa-angle-left"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="{{route('admin.itinerary.index')}}" class="nav-link {{ (request()->is('admin/itinerary'))? 'active' : '' }}">
                      <i class="far fa-circle nav-icon"></i>
                      <p>List</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{route('admin.itinerary.create')}}" class="nav-link {{ (request()->is('admin/itinerary/create'))? 'active' : '' }}">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Create</p>
                    </a>
                  </li>
                </ul>
              </li>
              <li class="nav-item {{ (request()->is('admin/quotation-notes*'))? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ (request()->is('admin/quotation-notes*'))? 'active' : '' }}">
                  <i class="nav-icon fas fa-edit"></i>
                  <p>
                    Notes
                    <i class="right fas fa-angle-left"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="{{route('admin.quotation-notes.index')}}" class="nav-link {{ (request()->is('admin/quotation-notes'))? 'active' : '' }}">
                      <i class="far fa-circle nav-icon"></i>
                      <p>List</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{route('admin.quotation-notes.create')}}" class="nav-link {{ (request()->is('admin/quotation-notes/create'))? 'active' : '' }}">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Create</p>
                    </a>
                  </li>
                </ul>
              </li>
              <li class="nav-header">OPERATIONS</li>
              <li class="nav-item {{ (request()->is('operations/quotations*'))? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ (request()->is('operations/quotations*'))? 'active' : '' }}">
                  <i class="nav-icon fas fa-wallet"></i>
                  <p>
                    Leads / Quotations
                    <i class="right fas fa-angle-left"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="{{route('operations.quotations.index')}}" class="nav-link {{ (request()->is('operations/quotations'))? 'active' : '' }}">
                      <i class="far fa-circle nav-icon"></i>
                      <p>List</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{route('operations.quotations.create')}}" class="nav-link {{ (request()->is('operations/quotations/create'))? 'active' : '' }}">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Create</p>
                    </a>
                  </li>
                </ul>
              </li>
              <li class="nav-item">
                <a href="{{route('operations.availabilities.index')}}" class="nav-link {{ (request()->is('operations/availabilities*'))? 'active' : '' }}">
                  <i class="nav-icon fas fa-plus-circle"></i>
                  <p>
                    Availability
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('operations.bookings.index')}}" class="nav-link {{ (request()->is('operations/bookings*'))? 'active' : '' }}">
                  <i class="nav-icon fas fa-hotel"></i>
                  <p>
                    Bookings
                  </p>
                </a>
              </li>
              @canany(['isAdmin', 'isAccounts'])
                <li class="nav-item">
                  <a href="{{route('admin.pending.payments')}}" class="nav-link {{ (request()->is('admin/pending-payments*') || request()->is('admin/pending-payments*'))? 'active' : '' }}">
                    <i class="nav-icon fas fa-rupee-sign"></i>
                    <p>
                      Payments
                    </p>
                  </a>
                </li>
              @endcanany
              <li class="nav-item">
                <a href="{{route('operations.overdues.index')}}" class="nav-link {{ (request()->is('operations/overdues*') || request()->is('operations/overdues*'))? 'active' : '' }}">
                  <i class="nav-icon fas fa-donate"></i>
                  <p>
                    Overdues
                  </p>
                </a>
              </li>
              @canany(['isAdmin'])
                <li class="nav-header">REPORTS</li>
                <li class="nav-item">
                  <a href="{{route('leads.report')}}" class="nav-link {{ (request()->is('leads-report*'))? 'active' : '' }}">
                    <i class="nav-icon fas fa-align-right"></i>
                    <p>
                      Leads Report
                    </p>
                  </a>
                </li>
                <li class="nav-header">SETTINGS</li>
                <li class="nav-item">
                  <a href="{{route('admin.company.details')}}" class="nav-link {{ (request()->is('operations/company-details*'))? 'active' : '' }}">
                    <i class="nav-icon fas fa-registered"></i>
                    <p>
                      Company Details
                    </p>
                  </a>
                </li>
                <li class="nav-item {{ (request()->is('admin/banks*'))? 'menu-open' : '' }}">
                  <a href="#" class="nav-link {{ (request()->is('admin/banks*'))? 'active' : '' }}">
                    <i class="nav-icon fas fa-landmark"></i>
                    <p>
                      Banks
                      <i class="right fas fa-angle-left"></i>
                    </p>
                  </a>
                  <ul class="nav nav-treeview">
                    <li class="nav-item">
                      <a href="{{route('admin.banks.index')}}" class="nav-link {{ (request()->is('admin/banks'))? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>List</p>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a href="{{route('admin.banks.create')}}" class="nav-link {{ (request()->is('admin/banks/create'))? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Create</p>
                      </a>
                    </li>
                  </ul>
                </li>
              @endcanany
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
            @yield('breadcrump')
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
        @yield('content')
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->

  <!-- Main Footer -->
  <footer class="main-footer">
    <strong>Copyright &copy; {{date('Y')}} <a href="Javascript::void(0)">Travel Measure</a>.</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Crafted with <i class="fa fa-heart text-pulse" style="color:red;"></i> by <a href="https://zestadore.in" target="_blank">Zestadore IT Solutions</a></b>
    </div>
  </footer>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->
<!-- jQuery -->
<script src="{{asset('assets/admin/plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap -->
<script src="{{asset('assets/admin/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- overlayScrollbars -->
<script src="{{asset('assets/admin/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{asset('assets/admin/dist/js/adminlte.js')}}"></script>

<!-- PAGE PLUGINS -->
<!-- jQuery Mapael -->
<script src="{{asset('assets/admin/plugins/jquery-mousewheel/jquery.mousewheel.js')}}"></script>
<script src="{{asset('assets/admin/plugins/raphael/raphael.min.js')}}"></script>
<script src="{{asset('assets/admin/plugins/jquery-mapael/jquery.mapael.min.js')}}"></script>
<script src="{{asset('assets/admin/plugins/jquery-mapael/maps/usa_states.min.js')}}"></script>
<!-- ChartJS -->
<script src="{{asset('assets/admin/plugins/chart.js/Chart.min.js')}}"></script>

<!-- AdminLTE for demo purposes -->
<script src="{{asset('assets/admin/dist/js/demo.js')}}"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="{{asset('assets/admin/dist/js/pages/dashboard2.js')}}"></script>
<script>
  $('#loader').hide();
  $(document)
  .ajaxStart(function () {
      $('#loader').show();
  })
  .ajaxStop(function () {
      $('#loader').hide();
  });
</script>
@yield('scripts')
</body>
</html>
