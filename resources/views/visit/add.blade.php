@extends('layout.master')
@section('title','Add Visit')
@section('container')
<div class="card">
  <div class="card-header"><h4>Add Visit to <a href="{{route('showDiagnose',['id'=>$diagnose->id])}}">Diagnosis Nr. {{$diagnose->id}}</a><a href="{{route('profilePatient',['id'=>$diagnose->patient->id])}}" class="float-right">{{$diagnose->patient->pname}}</a></h4></div>
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
    <form id="add_visit_form" action="{{route('addAppointment',['id'=>$diagnose->id])}}" method="post">
      <h4 class="center mb-3">Here you can add visit to a specific Diagnosis</h4>
      <div class="form-group row">
        <label for="visit_treatment" class="col-sm-2">Treatment</label>
        <div class="col-sm-10">
          <textarea name="visit_treatment" id="visit_treatment" placeholder="Write down the Treatment" class="form-control">{{old('visit_treatment')}}</textarea>
        </div>
      </div>
      <div class="form-group row">
        <label for="visit_date" class="col-sm-2">Date</label>
        <div class="col-sm-10">
          <input type="date" name="visit_date" id="visit_date" value="{{old('visit_date')}}" placeholder="Select Date" class="form-control">
        </div>
      </div>
      <div id="loading" class="text-center" style="display:none">
        <img width="50px" height="50px" src="{{asset('load.gif')}}" alt="">
      </div>
      <div class="form-group row">
        <label class="col-sm-2">Time</label>
        <div class="col-sm-10">
          <select name="visit_time" id="visit_time" class="custom-select">
            <option value="">select the desired date first</option>
          </select>
        </div>
      </div>
      <input style="width: 150px; display: block; margin:0 auto;" type="submit" class="btn btn-secondary" value="Add Visit">
      @csrf
    </form>
  </div>
</div>
@endsection
