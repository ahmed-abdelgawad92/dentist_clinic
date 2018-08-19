@extends('layout.master')
@section('title','All Users')
@section('container')
<div class="card">
  <div class="card-header">
    <h4>All Users</h4>
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
    <div class="col-10 offset-1">
      @if($users->count()>0)
      <form action="{{route('searchUser')}}" id="search_user_form" method="post" style="position: relative" class="mb-3">
        <input type="text" autocomplete="off" name="search_user" id="search_user" placeholder="search for a user" class="form-control" value="">
        <button class="search" type="submit">
          <span class="glyphicon glyphicon-search"></span>
        </button>
        @csrf
      </form>
      <div id="loading" class="text-center" style="display:none">
        <img src="{{asset('loading.gif')}}" width="270px" height="200px" alt="">
      </div>
      <table class="table table-striped">
        <thead>
        <tr>
          <th>#</th>
          <th>Name</th>
          <th>Username</th>
          <th>Role</th>
          <th>Phone</th>
          <th>Log</th>
          <th>Edit</th>
          <th>delete</th>
        </tr>
        </thead>
      @php
        $count =1;
      @endphp
      <tbody id="user-table">
      @foreach ($users as $user)
        @if ($user->role==2)
          @continue
        @endif
        <tr>
          <th>{{$count++}}</th>
          <td><a href="{{route('showUser',['id'=>$user->id])}}">{{ucwords($user->name)}}</a></td>
          <td>{{$user->uname}}</td>
          @if ($user->role==1)
          <td>Admin</td>
          @else
          <td>User</td>
          @endif
          <td>{{$user->phone}}</td>
          <td><a href="{{route('allUserLogs',['id'=>$user->id])}}" class="btn btn-home">User's Logs</a></td>
          <td><a href="{{route('updateUser',['id'=>$user->id])}}" class="btn btn-secondary">edit <span class="glyphicon glyphicon-edit"></span></a></td>
          <td><a href="{{route('deleteUser',['id'=>$user->id])}}" class="btn delete_user btn-danger">delete <span class="glyphicon glyphicon-trash"></span></a></td>
        </tr>
      @endforeach
      </tbody>
      </table>
      @else
      <div class="alert alert-warning">There is no users but you :)</div>
      @endif
    </div>
    </div>
  </div>
</div>
<div class="float_form_container">
  <div id="delete_user" class="float_form bg-home">
    <span class="close bg-home">&times;</span>
      <h4 class="center mb-3">Are you sure that you want to delete this User?</h4>
      <div class="center">
        <a style="width: 150px; display: inline-block;" class="btn btn-danger">YES</a>
        <button style="width: 150px; display: inline-block;" type="button" class="close_button btn btn-secondary">NO</button>
      </div>
  </div>
</div>
@endsection
