@extends('layout.master')
@section('title','Edit User'.$user->uname)
@section('container')
<div class="card">
  <div class="card-header">
    Edit {{$user->name}}
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
    <form id="edit_user_form" action="{{route("updateUser",['id'=>$user->id])}}" method="post">
      <div class="form-group row">
        <label for="name" class="col-sm-2">Full Name</label>
        <div class="col-sm-10">
          <input type="text" name="name" autofocus id="name" placeholder="Enter Full Name" value="{{$user->name}}" class="@if ($errors->has('name'))
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
        <label for="phone" class="col-sm-2">Phone No.</label>
        <div class="col-sm-10">
          <input type="text" name="phone" id="phone" placeholder="Enter Phone No." value="{{$user->phone}}" class="@if ($errors->has('phone'))
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
        <label for="role" class="col-sm-2">Role</label>
        <div class="col-sm-10">
          <select name="role" id="role" placeholder="Enter Patient Name" class="@if ($errors->has('role'))
            is-invalid
          @endif form-control">
          <option value="0" @if($user->role==0) selected @endif >User</option>
          <option value="1" @if($user->role==1) selected @endif >Admin</option>
        </select>
          @if ($errors->has("role"))
            @foreach ($errors->get("role") as $msg)
              <div style='display:block' class='invalid-feedback'>{{$msg}}</div>
            @endforeach
          @endif
        </div>
      </div>
      <button class="btn btn-home btn-lg submit-btn">Edit User</button>
      @csrf
      @method("PUT")
    </form>
  </div>
</div>
@endsection
