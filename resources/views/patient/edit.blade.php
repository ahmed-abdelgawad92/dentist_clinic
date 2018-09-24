@extends('layout.master')
@section('title','Edit Patient')
@section('container')
<div class="card">
  <div class="card-header">
    Edit Patient's Information <a href="{{route('profilePatient',['id'=>$patient->id])}}">"{{ucwords($patient->pname)}}"</a>
  </div>
  <div class="card-body">
    @if ($errors->any())
      <div class="alert alert-danger alert-dismissible fade show">
        <p>Errors detected during patient updating</p>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    @endif
    <div id="loading" class="text-center" style="display:none">
      <img src="{{asset('loading.gif')}}" alt="">
    </div>
    <form id="patient-create-form" action="{{route('updatePatient',['id'=>$patient->id])}}" method="post" enctype="multipart/form-data">
      <div class="form-group row">
        <label for="pname" class="col-sm-2">Patient Name</label>
        <div class="col-sm-10">
          <input type="text" name="pname" id="pname" placeholder="Enter Patient Name" value="{{$patient->pname}}" class="@if ($errors->has('pname'))
            is-invalid
          @endif form-control">
          @if ($errors->has("pname"))
            @foreach ($errors->get("pname") as $msg)
              <div style='display:block' class='invalid-feedback'>{{$msg}}</div>
            @endforeach
          @endif
        </div>
      </div>
      <div class="form-group row">
        <label class="col-sm-2">Gender</label>
        <div class="col-sm-10">
          <label for="male">
            <input @if($patient->gender)  checked   @endif type="radio" name="gender" id="male" value="1"> male
          </label>
          <label for="female" id="div-gender">
            <input @if(!$patient->gender)  checked   @endif type="radio" name="gender" id="female" value="0"> female
          </label>
          @if ($errors->has("gender"))
            @foreach ($errors->get("gender") as $msg)
              <div style='display:block' class='invalid-feedback'>{{$msg}}</div>
            @endforeach
          @endif
        </div>
      </div>
      <div class="form-group row">
        <label for="dob" class="col-sm-2">Age</label>
        <div class="col-sm-10">
          <input type="text" name="dob" id="dob" placeholder="Enter Age" class="form-control @if ($errors->has('dob'))
            is-invalid
          @endif" value="{{round((time()-strtotime($patient->dob))/(3600*24*365.25))}}">
          @if ($errors->has("dob"))
            @foreach ($errors->get("dob") as $msg)
              <div style='display:block' class='invalid-feedback'>{{$msg}}</div>
            @endforeach
          @endif
        </div>
      </div>
      <div class="form-group row">
        <label for="address" class="col-sm-2">Address</label>
        <div class="col-sm-10">
          <input type="text" name="address" id="address" placeholder="Enter address" class="form-control @if ($errors->has('address'))
            is-invalid
          @endif" value="{{$patient->address}}">
          @if ($errors->has("address"))
            @foreach ($errors->get("address") as $msg)
              <div style='display:block' class='invalid-feedback'>{{$msg}}</div>
            @endforeach
          @endif
        </div>
      </div>
      <div class="form-group row">
        <label for="phone" class="col-sm-2">Phone No.</label>
        <div class="col-sm-10">
          <input type="text" name="phone" id="phone" placeholder="Enter phone number" class="form-control @if ($errors->has('phone'))
            is-invalid
          @endif" value="{{$patient->phone}}">
          @if ($errors->has("phone"))
            @foreach ($errors->get("phone") as $msg)
              <div style='display:block' class='invalid-feedback'>{{$msg}}</div>
            @endforeach
          @endif
        </div>
      </div>
      <div class="form-group row">
        <label for="" class="col-sm-2">Diabetes</label>
        <div class="col-sm-10">
          <label for="d-no">
            <input @if(!$patient->diabetes)  checked   @endif type="radio" name="diabetes" id="d-no" value="0"> No
          </label>
          <label for="d-yes" id="div-diabetes">
            <input @if($patient->diabetes)  checked   @endif type="radio" name="diabetes" id="d-yes" value="1"> Yes
          </label>
          @if ($errors->has("diabetes"))
            @foreach ($errors->get("diabetes") as $msg)
              <div style='display:block' class='invalid-feedback'>{{$msg}}</div>
            @endforeach
          @endif
        </div>
      </div>
      <div class="form-group row">
        <label for="" class="col-sm-2">Blood Pressure</label>
        <div class="col-sm-10">
          <label for="b-low">
            <input @if($patient->blood_pressure=="low")  checked   @endif type="radio" name="blood_pressure" id="b-low" value="low"> Low
          </label>
          <label for="b-normal">
            <input @if($patient->blood_pressure=="normal")  checked   @endif type="radio" name="blood_pressure" id="b-normal" value="normal"> Normal
          </label>
          <label for="b-high" id="div_blood_pressure">
            <input @if($patient->blood_pressure=="high")  checked   @endif type="radio" name="blood_pressure" id="b-high" value="high"> High
          </label>
          @if ($errors->has("blood_pressure"))
            @foreach ($errors->get("blood_pressure") as $msg)
              <div style='display:block' class='invalid-feedback'>{{$msg}}</div>
            @endforeach
          @endif
        </div>
      </div>
      <div class="form-group row">
        <label for="medical_compromise" class="col-sm-2">Medical Compromise</label>
        <div class="col-sm-10">
          <textarea height="120px" name="medical_compromise" id="medical_compromise" placeholder="Write the Medical Compromise" class="form-control">{{$patient->medical_compromise}}</textarea>
        </div>
      </div>
      <button class="btn btn-secondary btn-lg submit-btn">Edit <span class="glyphicon glyphicon-edit"></span></button>
      @method('PUT')
      @csrf
    </form>
  </div>
</div>
@endsection
