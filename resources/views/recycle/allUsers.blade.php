@extends("layout.master")
@section("title","Deleted Users")
@section('container')
<div class="card">
  <div class="card-header">
    <h4>Deleted Users <img src="{{asset('recycle.ico')}}" class="float-right recycle" alt=""></h4>
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
    @if ($users->count()>0)
      @php
        $count=1;
      @endphp
    <table class="table table-striped">
      <tr>
        <th>#</th>
        <th>Name</th>
        <th>Usernam</th>
        <th>Phone</th>
        <th>Role</th>
        <th>Creation Date</th>
        <th>Deletion Date</th>
        <th>Action</th>
      </tr>
      @foreach ($users as $user)
      <tr>
        <td>{{$count++}}</td>
        <td><a href="{{route('showUser',['id'=>$user->id])}}">{{$user->name}}</a></td>
        <td>{{$user->uname}}</td>
        <td>{{$user->phone}}</td>
        @if ($user->role==0)
        <td>User</td>
        @else
        <td>Admin</td>
        @endif
        <td style="white-space:nowrap">{{date('d-m-Y h:i a',strtotime($user->created_at))}}</td>
        <td style="white-space:nowrap">{{date('d-m-Y h:i a',strtotime($user->updated_at))}}</td>
        <td style="white-space:nowrap">
          <a href="{{route('recoverUser',['id'=>$user->id])}}" class="btn btn-success mr-1">recovery</a>
          <a href="{{route('deletePerUser',['id'=>$user->id])}}" class="btn btn-danger">delete</a>
        </td>
      </tr>
      @endforeach
    </table>
    @else
    <div class="alert alert-warning">There is no deleted Users</div>
    @endif
  </div>
</div>
@endsection
