@extends('layout.master')
@section('title','All Logs In '.ucwords($table))
@section('container')
<div class="card">
  <div class="card-header">
    <h4>{{ucwords($user->uname)}}'s All Processes on {{ucwords($table)}}</h4>
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
        <a href="{{route("showUser",['id'=>$user->id])}}"><img src="{{url('storage/'.$user->photo)}}" alt="{{$user->name}}" class="profile rounded-circle"></a>
        @else
        <a href="{{route("showUser",['id'=>$user->id])}}"><img src="{{asset('unknown.png')}}"  alt="{{$user->name}}" class="profile rounded-circle"></a>
        @endif
        </div>
        <h4 class="center"><a href="{{route("showUser",['id'=>$user->id])}}">{{ucwords($user->name)}}</a></h4>
        <h4 class="center" title="Phone No.">{{$user->phone}}</h4>
      </div>
      <div class="col-md-9 col-lg-9 col-sm-12 col-12">
        <div class="controls">
          <h4>User Details</h4>
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
      <div class="col-10 offset-1 mt-3">
      <h4 class="center">{{ucwords($user->name)}} made #{{$logs->total()}} processes in {{ucwords($table)}}</h4>
      @if($logs->count()>0)
      @php
        $count=1;
      @endphp
      <table class="table table-striped">
        <tr>
          <th>#</th>
          <th style="white-space:nowrap;">Affected {{ucwords($table)}}</th>
          <th>Process</th>
          <th>Description</th>
          <th>Date</th>
          <th>Time</th>
        </tr>
        @foreach ($logs as $log)
        <tr>
          <th>{{$count++}}</th>
          @if ($log->affected_table=="users")
          <th><a href="{{route('showUser',['id'=>$log->affected_row])}}">{{$log->userName()}}</a></th>
          @elseif ($log->affected_table=="patients")
          <th><a href="{{route('profilePatient',['id'=>$log->affected_row])}}">{{$log->patient()}}</a></th>
          @elseif ($log->affected_table=="appointments")
          <th ><a href="">Visit Nr. {{$log->appointment()}}</a></th>
          @elseif ($log->affected_table=="diagnoses")
          <th><a href="{{route('showDiagnose',['id'=>$log->affected_row])}}">Diagnosis Nr. {{$log->diagnose()}}</a></th>
          @elseif ($log->affected_table=="drugs")
          <th>{{$log->drug()}}</th>
          @elseif ($log->affected_table=="oral_radiologies")
          <th><a href="">{{$log->xray()}}</a></th>
          @elseif ($log->affected_table=="working_times")
          <th>{{$log->working_time()}}</th>
          @endif
          <th>{{$log->process_type}}</th>
          <th>{{$log->description}}</th>
          <th style="white-space:nowrap;">{{date("d-m-Y",strtotime($log->created_at))}}</th>
          <th style="white-space:nowrap;">{{date("h:i a",strtotime($log->created_at))}}</th>
        </tr>
        @endforeach
      </table>
      {!!$logs->links()!!}
      @else
      <div class="alert alert-warning">
        He didn't make any process on Users
      </div>
      @endif
      </div>
    </div>
  </div>
</div>
@endsection
