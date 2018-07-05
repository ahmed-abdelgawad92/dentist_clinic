@extends('layout.master')
@section('title')
{{$user->uname}}'s profile
@endsection
@section('container')
<div class="card">
  <div class="card-header">
    <h4>{{ucwords($user->uname)}}'s Profile</h4>
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
    <div class="row">
      <div class="col-md-3 col-lg-3 col-sm-6 col-6 offset-3 offset-md-0 offset-lg-0 offset-sm-3">
        <div id="profile-div">
        @if(Storage::disk('local')->exists($user->photo))
        <a href=""><img src="{{url('storage/'.$user->photo)}}" alt="{{$user->name}}" class="profile rounded-circle"></a>
        @else
        <a href=""><img src="{{asset('unknown.png')}}"  alt="{{$user->name}}" class="profile rounded-circle"></a>
        @endif
        </div>
        <h4 class="center">{{ucwords($user->name)}}</h4>
        <h4 class="center" title="Phone No.">{{$user->phone}}</h4>
      </div>
      <div class="col-md-9 col-lg-9 col-sm-12 col-12">
        <div class="controls">
          <h4>Account Details</h4>
          <div class="btn-group">
            @if($user->id==auth()->user()->id)
            <a href="{{route("changePassword")}}" class="btn btn-home">change password</a>
            @endif
            @if(auth()->user()->role==1)
            <a href="{{route("updateUser",["id"=>$user->id])}}" class="btn btn-secondary">edit</a>
            @endif
          </div>
        </div>
        <table class="table table-striped info">
          <tr>
            <th>Full Name</th>
            <td>{{$user->name}}</td>
          </tr>
          <tr>
            <th>Username</th>
            <td>{{$user->uname}}</td>
          </tr>
          <tr>
            <th>Phone</th>
            <td>{{$user->phone}}</td>
          </tr>
          <tr>
            <th>Role</th>
            <td>@if ($user->role==0) User @else Admin @endif </td>
          </tr>
        </table>
      </div>
    </div>
    <div class="row">

    </div>
  </div>
</div>
@endsection
