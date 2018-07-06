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
      <form action="{{route('searchUser')}}" id="search_user_form" method="post" style="position: relative" class="mb-3">
        <input type="text" name="search_user" id="search_user" placeholder="search for a user" class="form-control" value="">
        <button class="search" type="submit">
          <span class="glyphicon glyphicon-search"></span>
        </button>
        @csrf
      </form>
      @if($users->count()>0)
      <table class="table table-striped">
        <tr>
          <th>#</th>
          <th>Name</th>
          <th>Username</th>
          <th>Phone</th>
          <th>Log</th>
          <th>Edit</th>
          <th>delete</th>
        </tr>
      @php
        $count =1;
      @endphp
      @foreach ($users as $user)
        <tr>
          <th>{{$count++}}</th>
          <td><a href="{{route('showUser',['id'=>$user->id])}}">{{ucwords($user->name)}}</a></td>
          <td>{{$user->uname}}</td>
          <td>{{$user->phone}}</td>
          <td><a href="{{route('allUserLogs',['id'=>$user->id])}}" class="btn btn-home">User's Logs</a></td>
          <td><a href="{{route('updateUser',['id'=>$user->id])}}" class="btn btn-secondary">edit <span class="glyphicon glyphicon-edit"></span></a></td>
          <td><a href="{{route('deleteUser',['id'=>$user->id])}}" class="btn btn-danger">delete <span class="glyphicon glyphicon-trash"></span></a></td>
        </tr>
      @endforeach
      </table>
      @else
      <div class="alert alert-warning">There is no users but you :)</div>
      @endif
    </div>
    </div>
  </div>
</div>
@endsection
