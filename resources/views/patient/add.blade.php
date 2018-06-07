@extends('layout.master')
@section('title','Create Patient')
@section('container')
<br>
<div class="card">
  <div class="card-header">
    Create Patient
  </div>
  <div class="card-body">
    <form class="" action="{{route('addPatient')}}" method="post" enctype="multipart/form-data">
      <div class="form-group row">
        <label for="pname" class="col-sm-2">Patient Name</label>
        <div class="col-sm-10">
          <input type="text" name="pname" autofocus id="pname" placeholder="Enter Patient Name" value="{{old('pname')}}" class="form-control">
        </div>
      </div>
      <div class="form-group row">
        <label class="col-sm-2">Gender</label>
        <div class="col-sm-10">
          <label for="male">
            <input type="radio" name="gender" checked id="male" value="1"> male
          </label>
          <label for="female">
            <input type="radio" name="gender" id="female" value="0"> female
          </label>
        </div>
      </div>
      <div class="form-group row">
        <label for="dob" class="col-sm-2">Date of birth</label>
        <div class="col-sm-10">
          <input type="date" name="dob" id="dob" placeholder="Enter date of birth" class="form-control" value="{{old('dob')}}">
        </div>
      </div>
      <div class="form-group row">
        <label for="address" class="col-sm-2">Address</label>
        <div class="col-sm-10">
          <input type="text" name="address" id="address" placeholder="Enter address" class="form-control" value="{{old('address')}}">
        </div>
      </div>
      <div class="form-group row">
        <label for="phone" class="col-sm-2">Phone No.</label>
        <div class="col-sm-10">
          <input type="text" name="phone" id="phone" placeholder="Enter phone number" class="form-control" value="">
        </div>
      </div>
      <div class="form-group row">
        <label for="" class="col-sm-2">Diabetes</label>
        <div class="col-sm-10">
          <label for="d-no">
            <input type="radio" name="diabetes" checked id="d-no" value="0"> No
          </label>
          <label for="d-yes">
            <input type="radio" name="diabetes" id="d-yes" value="1"> Yes
          </label>
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
          <label for="b-high">
            <input type="radio" name="blood_pressure" id="b-high" value="high"> High
          </label>
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
            <input type="file" class="custom-file-input" id="photo">
            <label class="custom-file-label" for="photo">Choose file</label>
          </div>
        </div>
      </div>
      <button class="btn btn-primary btn-lg submit-btn">Create</button>

      @csrf
    </form>
  </div>
</div>
@endsection
