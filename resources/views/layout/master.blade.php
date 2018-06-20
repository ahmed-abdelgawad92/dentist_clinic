<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield("title")</title>
    <link rel="icon" href="{{asset('logotooth.png')}}">
    <link href="https://fonts.googleapis.com/css?family=Slabo+27px" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('js/jquery-ui/jquery-ui.min.css')}}">
    <link rel="stylesheet" href="{{asset('js/jquery-ui/jquery-ui.theme.min.css')}}">
    <link rel="stylesheet" href="{{asset('bootstrap/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    <script type="text/javascript" src="{{asset('bootstrap/js.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/jquery-ui/jquery-ui.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/validate.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/script.js')}}"></script>
  </head>
  <body class="site">
    <nav class="navbar navbar-dark bg-home">
      <a href="@auth{{route('home')}}@endauth" class="navbar-brand">
        <img src="{{asset('logotooth_75.png')}}" id="logo" alt="">
        Dr. FOX
      </a>
      @auth
      <div id="show-menu-div">
        <a href="#" id="show-menu-link">
          <span class="glyphicon glyphicon-th"></span>
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
            <div class="col-12 col-sm-6 col-md-4 col-lg-4 mb-3">
              <h4>Patients</h4>
              <ul class="nav flex-column">
                <li class="nav-item">
                  <a href="{{route('allPatient')}}" class="control-menu-item">All Patients</a><br>
                </li>
                <li class="nav-item">
                  <a href="{{route('addPatient')}}" class="control-menu-item">Create Patient</a>
                </li>
              </ul>
            </div>
            <div class="col-12 col-sm-6 col-md-4 col-lg-4 mb-3">
              <h4>Visits</h4>
              <a href="#" class="control-menu-item">All Patients</a>
            </div>
            <div class="col-12 col-sm-6 col-md-4 col-lg-4 mb-3">
              <h4>Medicine</h4>
              <a href="#" class="control-menu-item">All Patients</a>
            </div>
          </div>
        </div>
      </div>
      <div id="search-form-div">
        <form id="search-form" action="{{route('searchPatient')}}" method="post" class="form-inline my-2 my-lg-0">
          <input id="search-input" class="form-control mr-sm-2" name="patient" type="search" placeholder="Search patient..." aria-label="Search" style="padding-right:30px">
          <button id="search" type="submit">
            <span class="glyphicon glyphicon-search"></span>
          </button>
          @csrf
        </form>
        <span id="show-search-form" class="glyphicon glyphicon-search"></span>
        <a href="{{route('logout')}}" data-toggle="tooltip" data-placement="left" title="logout" class="btn-home" id="logout">
          <span class="glyphicon glyphicon-log-out"></span>
        </a>
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
      </div>
      @endauth
    </nav>
    <div class="container site-content">
      @yield('container')
    </div>
    <footer><address>Copyright © 2018 Ahmed Abdelgawad</address></footer>
  </body>
</html>
