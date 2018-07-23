@extends('layout.master')
@section('title','Edit Visit')
@section('container')
<div class="card">
  <div class="card-header"><h4>Edit Visit of <a href="{{route('showDiagnose',['id'=>$visit->diagnose->id])}}">Diagnosis Nr. {{$visit->diagnose->id}}</a><a href="{{route('profilePatient',['id'=>$visit->patient()->id])}}" class="float-right">{{$visit->patient()->pname}}</a></h4></div>
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
    @if (session("warning"))
    <div class="alert alert-warning">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
      <h4>Warning</h4>
      {!!session("warning")!!}
    </div>
    @endif
    <form action="{{route('updateAppointment',['id'=>$visit->id])}}" id="edit_visit_form" method="post">
      <h4 class="center mb-3">Here you can edit visit to a specific Diagnosis</h4>
      <div class="form-group row">
        <label for="visit_treatment" class="col-sm-2">Treatment</label>
        <div class="col-sm-10">
          <textarea name="visit_treatment" id="visit_treatment" placeholder="Write down the Treatment" class="form-control">{{$visit->treatment}}</textarea>
        </div>
      </div>
      <div class="form-group row">
        <label for="visit_date" class="col-sm-2">Date</label>
        <div class="col-sm-10">
          <input type="date" name="visit_date" id="visit_date" value="{{$visit->date}}" placeholder="Select Date" class="form-control">
        </div>
      </div>
      <div id="loading" class="text-center" style="display:none">
        <img width="50px" height="50px" src="{{asset('load.gif')}}" alt="">
      </div>
      <div class="form-group row">
        <label class="col-sm-2">Time</label>
        <div class="col-sm-10">
          <select name="visit_time" id="visit_time" class="custom-select">
            <option value="{{date("h:i a",strtotime($visit->time))}}">{{date("h:i a",strtotime($visit->time))}}</option>
          </select>
        </div>
      </div>
      <input style="width: 150px; display: block; margin:0 auto;" type="submit" class="btn btn-secondary" value="Edit Visit">
      @csrf
      @method('PUT')
    </form>
  </div>
</div>
@endsection
