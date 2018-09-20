@extends('layout.master')
@section('title','login')
@section('container')
  <div class="row justify-content-center">
    <form action="{{route('postLogin')}}" method="post" id="form-login" class="col-11 col-sm-10 col-lg-6 col-md-8">
      <h3>Login to your account</h3>
      @if (session("invalid")!=null)
        <div class="alert alert-danger alert-dismissible fade show">
          <h4 class="alert-heading">Invalid Entry</h4>
          {{session("invalid")}}
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      @endif
      <div class="form-group row">
        <label for="uname" class="col-form-label col-sm-3 col-md-2 col-lg-2">Username</label>
        <div class="col-sm-9 col-md-10 col-lg-10">
          <input type="text" id="uname" name="uname" autocomplete="off" value="{{old('uname')}}" placeholder="Enter your username" class="form-control" autofocus>
        </div>
      </div>
      <div class="form-group row">
        <label for="uname" class="col-form-label col-sm-3 col-md-2 col-lg-2">Password</label>
        <div class="col-sm-9 col-md-10 col-lg-10">
          <input type="password" id="password" name="password" placeholder="Enter your password" class="form-control">
        </div>
      </div>
      <input type="submit" value="login" class="btn btn-login">
      {{ csrf_field() }}
    </form>
  </div>
@endsection
