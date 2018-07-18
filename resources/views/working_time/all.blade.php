@extends('layout.master')
@section('title','All Working Times')
@section('container')
<div class="card">
  <div class="card-header"><h4>All Working Time <a href="{{route('addWorkingTime')}}" class="btn btn-home float-right">Add Working time</a></h4></div>
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
    @if ($working_times->count()>0)
    <table class="table table-striped">
    <tr>
      @php
        $counter=1;
      @endphp
      <th>#</th>
      <th>Day</th>
      <th>From</th>
      <th>To</th>
      <th>Action</th>
    </tr>
    @foreach ($working_times as $time)
    <tr>
      <td>{{$counter++}}</td>
      <td>{{$time->getDayName()}}</td>
      <td>{{date("h:i a",strtotime($time->time_from))}}</td>
      <td>{{date("h:i a",strtotime($time->time_to))}}</td>
      <td>
        <a href="{{route('updateWorkingTime',['id'=>$time->id])}}" class="btn btn-secondary">edit</a>
        <a href="{{route('deleteWorkingTime',['id'=>$time->id])}}" class="btn btn-danger">delete</a>
      </td>
    </tr>
    @endforeach
    </table>
    @else
      <div class="alert alert-warning">There is no working times on this system <a href="{{route('addWorkingTime')}}" class="btn btn-home">add working time</a></div>
    @endif
  </div>
</div>
@endsection
