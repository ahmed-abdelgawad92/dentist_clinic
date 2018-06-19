@extends('layout.master')
@section('title','home')
@section('container')
  {{-- @guest
  <div class="row justify-content-center">
    <div class="col-sm-8 col-md-6 col-lg4">
      <div class="card">
        <div class="card-header bg-home text-white">
          login
        </div>
        <div class="card-body">
          <form action="" method="post">
            <div class="form-group">
              <label for="uname">Username</label>
              <input type="text" name="uname" id="uname" class="form-control" value="" placeholder="Enter Username">
            </div>
            <div class="form-group">
              <label for="pass">Password</label>
              <input type="password" name="pass" id="pass"  class="form-control"value="" placeholder="Enter Password">
            </div>
            <button type="submit" class="btn btn-home">login</button>
          </form>
        </div>
      </div>
    </div>
  </div>
  @endguest
  @auth

  @endauth --}}
  @if (session("success")!=null)
  <div class="alert alert-success alert-dismissible fade show">
    <h4 class="alert-heading">Completed Successfully</h4>
    {{session("success")}}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
  @endif
  <div class="row">
    <div class="col-md-4 col-sm-6 col-lg-4">
      <div class="card">
        <a href="{{route('addPatient')}}"><img src="{{asset('patient.jpg')}}" alt="" class="card-img-top"></a>
        <div class="card-body">
          <h5 class="card-title">Create new patient</h5>
          <p class="card-text">Here you can create a new patient and all the details you need to know about the patient.</p>
          <a href="{{route('addPatient')}}" class="btn btn-primary">Create Now</a>
        </div>
      </div>
    </div>
    <div class="col-md-4 col-sm-6 col-lg-4">
      <div class="card">
        <img src="{{asset('calendar.png')}}" alt="" class="card-img-top">
        <div class="card-body">
          <h5 class="card-title">Todays Visits</h5>
          <p class="card-text">Here you can check all of your todays appointments and edit,approve or cancel them.</p>
          <a href="" class="btn btn-primary">Check Now</a>
        </div>
      </div>
    </div>
    <div class="col-md-4 col-sm-6 col-lg-4">
      <div class="card">
        <a href="{{route('allPatient')}}"><img src="{{asset('list.jpg')}}" alt="" class="card-img-top"></a>
        <div class="card-body">
          <h5 class="card-title">Show All Patients</h5>
          <p class="card-text">Here you can get a list of all patients , you can select the one you want and add diagnosis</p>
          <a href="{{route('allPatient')}}" class="btn btn-primary">Show Now</a>
        </div>
      </div>
    </div>
  </div>
@endsection
