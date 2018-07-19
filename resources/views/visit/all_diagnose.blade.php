@extends('layout.master')
@section('title','All Visits within diagnosis')
@section('container')
<div class="card">
  <div class="card-header">
    <h4>
      All Visits within <a href="{{route('showDiagnose',['id'=>$diagnose->id])}}">Diagnosis Nr. {{$diagnose->id}}</a> of <a href="{{route('profilePatient',['id'=>$diagnose->patient->id])}}">{{ucwords($diagnose->patient->pname)}}</a>
      @if ($diagnose->done==0)
      <a href="{{route('addAppointment',['id'=>$diagnose->id])}}" class="btn btn-home float-right">Add Visit</a>
      @endif
    </h4>
  </div>
  <div class="card-body">
    @if (session('error')!=null)
    <div class="alert alert-danger">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
      <h4>Error</h4>
      {!!session("error")!!}
    </div>
    @endif
    @if (session("success"))
    <div class="alert alert-success">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
      <h4>Completed Successfully</h4>
      {!!session("success")!!}
    </div>
    @endif
    @if ($visits->count()>0)
    @php
      $counter=1;
    @endphp
    <table class="table table-striped">
      <tr>
        <th>#</th>
        <th>Treatment</th>
        <th>Date</th>
        <th>Time</th>
        <th>State</th>
        <th>Action</th>
      </tr>
      @foreach ($visits as $visit)
      <tr>
        <td>{{$counter++}}</td>
        <td>{{$visit->treatment}}</td>
        <td style="white-space:nowrap;">{{date("d-m-Y",strtotime($visit->date))}}</td>
        <td style="white-space:nowrap;">{{date("h:i a",strtotime($visit->time))}}</td>
        @if ($visit->approved==3)
        <td style="white-space:nowrap;"><div class="btn btn-home">in waiting room <span class="glyphicon glyphicon-time"></span></div></td>
        @elseif ($visit->approved==1)
        <td style="white-space:nowrap;"><div class="btn btn-success">finished visit <span class="glyphicon glyphicon-ok-circle"></span></div></td>
        @elseif ($visit->approved==2)
        <td style="white-space:nowrap;"><div class="btn btn-secondary">not approved <span class="glyphicon glyphicon-remove-sign"></span></div></td>
        @elseif ($visit->approved==0)
        <td style="white-space:nowrap;"><div class="btn btn-danger">cancelled <span class="glyphicon glyphicon-remove-sign"></span></div></td>
        @endif
        <td style="white-space:nowrap;">
          @if ($visit->approved==2)
          <a href="{{route('cancelAppointment',['id'=>$visit->id])}}" class="btn btn-danger">cancel</a>
          @elseif($visit->approved==3)
          <a href="{{route('endAppointment',['id'=>$visit->id])}}" class="btn btn-success">end visit</a>
          @elseif($visit->approved==0)
          <a href="" class="btn btn-danger action" data-action="#delete_visit" data-url="/patient/diagnosis/visit/delete/{{$visit->id}}">delete</a>
          @endif
          <a href="{{route('updateAppointment',['id'=>$visit->id])}}" class="btn btn-secondary">edit</a>
        </td>
      </tr>
      @endforeach
    </table>
    @else
    <div class="alert alert-warning">
      <span class="glyphicon glyphicon-exclamation-sign"></span> There is no visits reserved within this diagnosis
      <a href="{{route('addAppointment',['id'=>$diagnose->id])}}">Add Visit</a>
    </div>
    @endif
  </div>
</div>
<div class="float_form_container">
  <div id="delete_visit" class="float_form bg-home">
    <span class="close" style="color:whitesmoke;">&times;</span>
      <h4 class="center mb-3">Are you sure that you want to delete this visit?</h4>
      <div class="center">
        <a style="width: 150px; display: inline-block;" class="btn btn-danger">YES</a>
        <button style="width: 150px; display: inline-block;" type="button" class="close_button btn btn-secondary">NO</button>
      </div>
  </div>
</div>
@endsection
