<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield("title")</title>
    <link rel="icon" href="{{asset('presc.png')}}">
    <link href="https://fonts.googleapis.com/css?family=Slabo+27px" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('js/jquery-ui/jquery-ui.min.css')}}">
    <link rel="stylesheet" href="{{asset('js/jquery-ui/jquery-ui.theme.min.css')}}">
    <link rel="stylesheet" href="{{asset('bootstrap/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    <script type="text/javascript" src="{{asset('bootstrap/js.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/jquery-ui/jquery-ui.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/js_barcode.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/validate.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/script.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/patients.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/diagnoses.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/xrays.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/visits.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/users.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/drugs.js')}}"></script>
  </head>
  <body class="site">
    <nav class="navbar navbar-dark bg-home">
      <a href="@auth{{route('home')}}@endauth" class="navbar-brand">
        <img src="{{asset('presc.png')}}" id="logo" alt="">
        Gad Dental Clinics
      </a>
      @auth
      <div id="show-menu-div">
        <a href="#" id="show-menu-link">
          <span class="glyphicon glyphicon-th"></span>
          {{-- <img src="{{asset('menu.svg')}}" width="50px" height="50px" alt=""> --}}
        </a>
      </div>
      <div id="show-menu-sm-div">
        <a href="#" id="show-menu-sm-link">
          <span class="glyphicon glyphicon-th"></span>
        </a>
      </div>
      <div id="menu-div">
        <div id="control-menu">
          <div class="control-menu-items row">
            @if (auth()->user()->role==1)
            <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-3">
            @else
            <div class="col-12 col-sm-6 col-md-4 col-lg-4 mb-3">
            @endif
              <h4>Patients</h4>
              <ul class="nav flex-column">
                <li class="nav-item">
                  <a href="{{route('allPatient')}}" class="control-menu-item">All Patients</a>
                </li>
                <li class="nav-item">
                  <a href="{{route('allPayments')}}" class="control-menu-item">All Payments</a>
                </li>
                <li class="nav-item">
                  <a href="{{route('addPatient')}}" class="control-menu-item">Create Patient</a>
                </li>
              </ul>
              <h4 class="mt-3">Medicine</h4>
              <ul class="nav flex-column">
                <li class="nav-item">
                  <a href="{{route('showAllSystemDrugs')}}" class="control-menu-item">Medicines on system</a><br>
                </li>
                <li class="nav-item">
                  <a href="{{route('addSystemDrug')}}" class="control-menu-item">Create medicine</a>
                </li>
              </ul>
            </div>
            @if (auth()->user()->role==1)
            <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-3">
            @else
            <div class="col-12 col-sm-6 col-md-4 col-lg-4 mb-3">
            @endif
              <h4>Visits</h4>
              <ul class="nav flex-column">
                <li class="nav-item">
                  <a href="{{route('allAppointment',['date'=>date('Y-m-d')])}}" class="control-menu-item">All Todays Visits</a><br>
                </li>
                <li class="nav-item">
                  <a href="{{route('allAppointment',['date'=>date('Y-m-d',strtotime('+1 day'))])}}" class="control-menu-item">All Tomorrow Visits</a>
                </li>
                <li class="nav-item">
                  <a href="{{route('allAppointment',['date'=>date('Y-m-d',strtotime('-1 day'))])}}" class="control-menu-item">All Yesterday Visits</a>
                </li>
              </ul>
            </div>
            @if (auth()->user()->role==1)
            <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-3">
            @else
            <div class="col-12 col-sm-6 col-md-4 col-lg-4 mb-3">
            @endif
              <h4>Working Times</h4>
              <ul class="nav flex-column">
                <li class="nav-item">
                  <a href="{{route('addWorkingTime')}}" class="control-menu-item">Add Working Time</a>
                </li>
                <li class="nav-item">
                  <a href="{{route('allWorkingTime')}}" class="control-menu-item">Working Times</a>
                </li>
              </ul>
              @if (auth()->user()->role==1)
              <h4 class="mt-3"><img src="{{asset('recycle.ico')}}" width='23px' height="23px" alt="">Recycle Bin</h4>
              <ul class="nav flex-column">
                <li class="nav-item">
                  <a href="{{route('allDeletedPatients')}}" class="control-menu-item">Deleted Patients</a>
                </li>
                <li class="nav-item">
                  <a href="{{route('allDeletedDiagnoses')}}" class="control-menu-item">Deleted Diagnosis</a>
                </li>
                <li class="nav-item">
                  <a href="{{route('allDeletedAppointments')}}" class="control-menu-item">Deleted Appointments</a>
                </li>
                <li class="nav-item">
                  <a href="{{route('allDeletedUsers')}}" class="control-menu-item">Deleted Users</a>
                </li>
                <li class="nav-item">
                  <a href="{{route('allDeletedTeeth')}}" class="control-menu-item">Deleted Teeth</a>
                </li>
                <li class="nav-item">
                  <a href="{{route('allDeletedDrugs')}}" class="control-menu-item">Deleted Drugs</a>
                </li>
                <li class="nav-item">
                  <a href="{{route('allDeletedWorkingTimes')}}" class="control-menu-item">Deleted Working Times</a>
                </li>
              </ul>
              @endif
            </div>
            @if (auth()->user()->role==1)
            <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-3">
              <h4>Admin Controls</h4>
              <ul class="nav flex-column">
                <li class="nav-item">
                  <a href="{{route('allUser')}}" class="control-menu-item">All Users</a><br>
                </li>
                <li class="nav-item">
                  <a href="{{route('createUser')}}" class="control-menu-item">Create New User</a><br>
                </li>
                <li class="nav-item">
                  <a href="{{route('allTableLogs',['table'=>'users'])}}" class="control-menu-item">All Logs on Users</a><br>
                </li>
                <li class="nav-item">
                  <a href="{{route('allTableLogs',['table'=>'patients'])}}" class="control-menu-item">All Logs on Patients</a><br>
                </li>
                <li class="nav-item">
                  <a href="{{route('allTableLogs',['table'=>'diagnoses'])}}" class="control-menu-item">All Logs on Diagnosis</a><br>
                </li>
                <li class="nav-item">
                  <a href="{{route('allTableLogs',['table'=>'appointments'])}}" class="control-menu-item">All Logs on Visits</a><br>
                </li>
                <li class="nav-item">
                  <a href="{{route('allTableLogs',['table'=>'oral_radiologies'])}}" class="control-menu-item">All Logs on X-rays</a><br>
                </li>
                <li class="nav-item">
                  <a href="{{route('allTableLogs',['table'=>'drugs'])}}" class="control-menu-item">All Logs on Medications</a><br>
                </li>
                <li class="nav-item">
                  <a href="{{route('allLogs')}}" class="control-menu-item">All Logs</a><br>
                </li>
              </ul>
            </div>
            @endif
          </div>
        </div>
      </div>
      <div id="search-form-div">
        <form id="search-form" action="{{route('searchPatient')}}" method="post" class="form-inline my-lg-0">
          <input id="search-input" class="form-control" name="patient" type="search" placeholder="Search patient..." aria-label="Search" style="padding-right:30px">
          <button id="search" type="submit">
            <span class="glyphicon glyphicon-search"></span>
          </button>
          @csrf
        </form>
        <span id="show-search-form" class="glyphicon glyphicon-search"></span>
        @if(Storage::disk('local')->exists(auth()->user()->photo))
          <img src="{{Storage::url(auth()->user()->photo)}}" alt="" id="admin_profile_img">
        @else
          <img src="{{asset('dentist.png')}}" alt="" id="admin_profile_img">
        @endif
      </div>
      <div class="arrow-up"></div>
      <div class="user_list">
        <a href="{{route("showUser",["id"=>auth()->user()->id])}}" class="user_list_item">
          account <img src="{{asset('account.png')}}" style="border-radius:8px" width="25px" height="25px" alt="">
        </a>
        <a href="{{route('changePassword')}}" class="user_list_item">
          change password <span class="glyphicon glyphicon-lock"></span>
        </a>
        <a href="{{route('logout')}}" class="user_list_item" id="logout">
          logout <span class="glyphicon glyphicon-log-out"></span>
        </a>
      </div>
      <div id="logout-modal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Logout</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <p>Are you sure that you want to logout?</p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">no</button>
              <a href="{{route('logout')}}" class="btn btn-primary">yes</a>
            </div>
          </div>
        </div>
      </div>
      @endauth
    </nav>
    <div class="container-fluid site-content">
      @yield('container')
    </div>
    <footer class="footer"><address>Copyright © 2018 Ahmed Abdelgawad</address></footer>
  </body>
</html>
