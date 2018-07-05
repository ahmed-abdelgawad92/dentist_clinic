@extends('layout.master')
@section('title','Change Password')
@section('container')
<div class="card">
  <div class="card-header">
    Change Your Password
  </div>
  <div class="card-body">
    @if (session("success")!=null)
    <div class="alert alert-success alert-dismissible fade show">
      <h4 class="alert-heading">Completed Successfully</h4>
      {!!session("success")!!}
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    @endif
    @if (session("error")!=null)
    <div class="alert alert-danger alert-dismissible fade show">
      <h4 class="alert-heading">Error</h4>
      {!!session("error")!!}
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    @endif
    <form id="change_password_form" action="{{route("changePassword")}}" method="post">
      <div class="form-group row">
        <label for="old_password" class="col-sm-2">Current Password</label>
        <div class="col-sm-10">
          <input type="password" name="old_password" autofocus id="old_password" placeholder="Enter your old passsword" value="{{old('old_password')}}" class="@if ($errors->has('old_password'))
            is-invalid
          @endif form-control">
          @if ($errors->has("old_password"))
            @foreach ($errors->get("old_password") as $msg)
              <div style='display:block' class='invalid-feedback'>{{$msg}}</div>
            @endforeach
          @endif
        </div>
      </div>
      <div class="form-group row">
        <label for="new_password" class="col-sm-2">New Password</label>
        <div class="col-sm-10">
          <input type="password" name="new_password" id="new_password" placeholder="Enter your new passsword" value="{{old('new_password')}}" class="@if ($errors->has('new_password'))
            is-invalid
          @endif form-control">
          @if ($errors->has("new_password"))
            @foreach ($errors->get("new_password") as $msg)
              <div style='display:block' class='invalid-feedback'>{{$msg}}</div>
            @endforeach
          @endif
        </div>
      </div>
      <div class="form-group row">
        <label for="confirm_new_password" class="col-sm-2">Confirm New Password</label>
        <div class="col-sm-10">
          <input type="password" name="confirm_new_password" id="confirm_new_password" placeholder="Re-Enter your passsword" value="{{old('confirm_new_password')}}" class="@if ($errors->has('confirm_new_password'))
            is-invalid
          @endif form-control">
          @if ($errors->has("confirm_new_password"))
            @foreach ($errors->get("confirm_new_password") as $msg)
              <div style='display:block' class='invalid-feedback'>{{$msg}}</div>
            @endforeach
          @endif
        </div>
      </div>
      <button class="btn btn-home btn-lg submit-btn">Change Password</button>
      @csrf
      @method("PUT")
    </form>
  </div>
</div>
@endsection
