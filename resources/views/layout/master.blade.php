<!DOCTYPE html>
<html>
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
    <script type="text/javascript" src="{{asset('js/script.js')}}"></script>
  </head>
  <body class="site">
    <nav class="navbar navbar-dark bg-home">
      <a href="@auth{{route('home')}}@endauth" class="navbar-brand">
        <img src="{{asset('logotooth_75.png')}}" id="logo" alt="">
        Dr. FOX
      </a>
      @auth
      <div id="search-form-div">
        <form id="search-form" action="{{route('searchPatient')}}" method="post" class="form-inline my-2 my-lg-0">
          <input id="search-input" class="form-control mr-sm-2" name="patient" type="search" placeholder="Search" aria-label="Search" style="padding-right:30px">
          <button id="search" type="submit">
            <span class="glyphicon glyphicon-search"></span>
          </button>
          @csrf
        </form>
        <span id="show-search-form" class="glyphicon glyphicon-search"></span>
        <a href="{{route('logout')}}" title="logout" class="btn-home" id="logout">
          <span class="glyphicon glyphicon-log-out"></span>
        </a>
      </div>
      @endauth
    </nav>
    <div class="container site-content">
      @yield('container')
    </div>
    <footer><address>Copyright © 2018 Ahmed Abdelgawad</address></footer>
  </body>
</html>
