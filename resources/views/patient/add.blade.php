@extends('layout.master')
@section('title','Create Patient')
@section('container')
<div class="card">
  <div class="card-header">
    Create Patient
  </div>
  <div class="card-body">
    @if ($errors->any())
      <div class="alert alert-danger alert-dismissible fade show">
        <p>Errors detected during patient creation</p>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    @endif
    <div id="loading" class="text-center" style="display:none">
      <img src="{{asset('loading.gif')}}" alt="">
    </div>
    <form id="patient-create-form" action="{{route('addPatient')}}" method="post" enctype="multipart/form-data">
      <div class="form-group row">
        <label for="pname" class="col-sm-2">Patient Name</label>
        <div class="col-sm-10">
          <input type="text" name="pname" autofocus id="pname" placeholder="Enter Patient Name" value="{{old('pname')}}" class="@if ($errors->has('pname'))
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
            <input type="radio" name="gender" checked id="male" value="1"> male
          </label>
          <label for="female" id="div-gender">
            <input type="radio" name="gender" @if (old('gender')===0) checked @endif id="female" value="0"> female
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
          @endif" value="{{old('dob')}}">
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
          @endif" value="{{old('address')}}">
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
          @endif" value="{{old('phone')}}">
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
            <input type="radio" name="diabetes" checked id="d-no" value="0"> No
          </label>
          <label for="d-yes" id="div-diabetes">
            <input type="radio" name="diabetes" id="d-yes" value="1"> Yes
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
            <input type="radio" name="blood_pressure" id="b-low" value="low"> Low
          </label>
          <label for="b-normal">
            <input type="radio" name="blood_pressure" checked id="b-normal" value="normal"> Normal
          </label>
          <label for="b-high" id="div_blood_pressure">
            <input type="radio" name="blood_pressure" id="b-high" value="high"> High
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
          <textarea height="120px" name="medical_compromise" id="medical_compromise" placeholder="Write the Medical Compromise" class="form-control" value="{{old('medical_compromise')}}"></textarea>
        </div>
      </div>
      <div class="form-group row">
        <label for="photo" class="col-sm-2">Upload Photo</label>
        <div class="col-sm-10">
          <div class="custom-file">
            <input type="file" class="custom-file-input @if ($errors->has('photo'))
              is-invalid
            @endif" id="photo" name="photo">
            <label class="custom-file-label" for="photo">Choose file</label>
          </div>
          @if ($errors->has("photo"))
            @foreach ($errors->get("photo") as $msg)
              <div style='display:block' class='invalid-feedback'>{{$msg}}</div>
            @endforeach
          @endif
        </div>
      </div>
      <button class="btn btn-home btn-lg submit-btn">Create Patient</button>
      @csrf
    </form>
  </div>
</div>
@endsection
