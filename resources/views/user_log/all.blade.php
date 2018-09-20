@extends('layout.master')
@section('title')
All logs
@if (isset($table))
of {{$table}}
@endif
@endsection
@section('container')
<div class="card">
  <div class="card-header">
    <h4>All Logs @if (isset($table)) on {{ucwords($table)}} @endif</h4>
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
    <div class="col-12">
      @if($logs->count()>0)
      <table class="table table-striped table-hover">
        <thead>
        <tr>
          <th>#</th>
          <th>Affected @if(isset($table)){{ucwords($table)}} @else Table @endif</th>
          <th>Affected row</th>
          <th>Process</th>
          <th>Description</th>
          <th>User</th>
          <th>Date</th>
          <th>Time</th>
        </tr>
        </thead>
      @php
        $count =1;
      @endphp
      <tbody>
      @foreach ($logs as $log)
        @if ($log->user->role==2)
          @continue;
        @endif
        <tr>
          <td>{{$count++}}</td>
          @if ($log->affected_table=="users")
          <td>Users</td>
          <td><a href="{{route('showUser',['id'=>$log->affected_row])}}">{{$log->userName()}}</a></td>
          @elseif ($log->affected_table=="patients")
          <td>Patients</td>
          <td><a href="{{route('profilePatient',['id'=>$log->affected_row])}}">{{$log->patient()}}</a></td>
          @elseif ($log->affected_table=="appointments")
          <td>Visits</td>
          <td><a href="">{{$log->appointment()}}</a></td>
          @elseif ($log->affected_table=="diagnoses")
          <td>Diagnosis</td>
          <td><a href="{{route('showDiagnose',['id'=>$log->affected_row])}}">Diagnosis Nr. {{$log->affected_row}}</a></td>
          @elseif ($log->affected_table=="drugs")
          <td>Medications</td>
          <td><a href="">{{$log->drug()}}</a></td>
          @elseif ($log->affected_table=="oral_radiologies")
          <td>X-rays</td>
          <td><a href="{{url('storage/'.$log->xray())}}">{{$log->xray()}}</a></td>
        @elseif ($log->affected_table=="working_times")
          <td>Working Times</td>
          <td>{{$log->working_time()}}</td>
          @endif
          <td>{{$log->process_type}}</td>
          <td>{{$log->description}}</td>
          <td><a href="{{route("showUser",['id'=>$log->user_id])}}">{{$log->user->uname}}</a></td>
          <td style="white-space: nowrap;">{{date("d-m-Y",strtotime($log->created_at))}}</td>
          <td style="white-space: nowrap;">{{date("h:i a",strtotime($log->created_at))}}</td>
        </tr>
      @endforeach
      </tbody>
      </table>
      {!!$logs->links()!!}
      @else
      <div class="alert alert-warning">There is no Logs @if (isset($table)) on {{ucwords($table)}} @endif</div>
      @endif
    </div>
    </div>
  </div>
</div>
@endsection
