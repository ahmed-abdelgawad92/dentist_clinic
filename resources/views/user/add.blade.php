@extends('layout.master')
@section('title','Register New User')
@section('container')
<div class="card">
  <div class="card-header">
    Registration of a new User
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
    <form id="create_user_form" action="{{route("createUser")}}" method="post" enctype="multipart/form-data">
      <div class="form-group row">
        <label for="admin_name" class="col-sm-2">Full Name</label>
        <div class="col-sm-10">
          <input type="text" name="name" autofocus id="admin_name" placeholder="Enter Full Name" value="{{old('name')}}" class="@if ($errors->has('name'))
            is-invalid
          @endif form-control">
          @if ($errors->has("name"))
            @foreach ($errors->get("name") as $msg)
              <div style='display:block' class='invalid-feedback'>{{$msg}}</div>
            @endforeach
          @endif
        </div>
      </div>
      <div class="form-group row">
        <label for="check_uname" class="col-sm-2">Username</label>
        <div class="col-sm-10">
          <input type="text" name="uname" autocomplete="off" id="check_uname" data-action="{{route('check_uname')}}" placeholder="Enter Username" value="{{old('uname')}}" class=" @if ($errors->has('uname'))
            is-invalid
          @endif form-control">
          @if ($errors->has("uname"))
            @foreach ($errors->get("uname") as $msg)
              <div style='display:block' class='invalid-feedback'>{{$msg}}</div>
            @endforeach
          @endif
          <div id="loading" class="text-center loadpos" style="display:none">
            <img src="{{asset('load.gif')}}" alt="" width="30px" height="30px">
          </div>
        </div>
      </div>
      <div class="form-group row">
        <label for="admin_password" class="col-sm-2">Password</label>
        <div class="col-sm-10">
          <input type="password" name="password" id="admin_password" placeholder="Enter Password" value="{{old('password')}}" class="@if ($errors->has('password'))
            is-invalid
          @endif form-control">
          @if ($errors->has("password"))
            @foreach ($errors->get("password") as $msg)
              <div style='display:block' class='invalid-feedback'>{{$msg}}</div>
            @endforeach
          @endif
        </div>
      </div>
      <div class="form-group row">
        <label for="admin_confirm_password" class="col-sm-2">Confirm Password</label>
        <div class="col-sm-10">
          <input type="password" name="confirm_password" id="admin_confirm_password" placeholder="Re-Enter Password" value="{{old('confirm_password')}}" class="@if ($errors->has('confirm_password'))
            is-invalid
          @endif form-control">
          @if ($errors->has("confirm_password"))
            @foreach ($errors->get("confirm_password") as $msg)
              <div style='display:block' class='invalid-feedback'>{{$msg}}</div>
            @endforeach
          @endif
        </div>
      </div>
      <div class="form-group row">
        <label for="admin_phone" class="col-sm-2">Phone No.</label>
        <div class="col-sm-10">
          <input type="text" name="phone" id="admin_phone" placeholder="Enter Phone No." value="{{old('phone')}}" class="@if ($errors->has('phone'))
            is-invalid
          @endif form-control">
          @if ($errors->has("phone"))
            @foreach ($errors->get("phone") as $msg)
              <div style='display:block' class='invalid-feedback'>{{$msg}}</div>
            @endforeach
          @endif
        </div>
      </div>
      <div class="form-group row">
        <label for="admin_role" class="col-sm-2">Role</label>
        <div class="col-sm-10">
          <select name="role" id="admin_role" placeholder="Enter Patient Name" class="@if ($errors->has('role'))
            is-invalid
          @endif form-control">
          <option value="0" @if(old("role")==0) selected @endif >User</option>
          <option value="1" @if(old("role")==1) selected @endif >Admin</option>
        </select>
          @if ($errors->has("role"))
            @foreach ($errors->get("role") as $msg)
              <div style='display:block' class='invalid-feedback'>{{$msg}}</div>
            @endforeach
          @endif
        </div>
      </div>
      <div class="form-group row">
        <label for="admin_photo" class="col-sm-2">Upload Profile Photo</label>
        <div class="col-sm-10">
          <div class="custom-file">
            <input type="file" class="custom-file-input @if ($errors->has('photo'))
              is-invalid
            @endif" id="admin_photo" name="photo">
            <label class="custom-file-label" for="admin_photo">Choose file</label>
          </div>
          @if ($errors->has("photo"))
            @foreach ($errors->get("photo") as $msg)
              <div style='display:block' class='invalid-feedback'>{{$msg}}</div>
            @endforeach
          @endif
        </div>
      </div>
      <button class="btn btn-home btn-lg submit-btn"><img src="{{asset('load.gif')}}" alt="" id="loading_button">Create User</button>
      @csrf
    </form>
  </div>
</div>
@endsection
