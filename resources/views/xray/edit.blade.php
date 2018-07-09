@extends('layout.master')
@section('title','Edit X-ray')
@section('container')
<div class="card">
  <div class="card-header">
    <h4>Edit Description of X-ray of <a href="{{route('showDiagnose',['id'=>$xray->diagnose->id])}}">This Diagnosis</a></h4>
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
    <div class="w-75 mx-auto mb-3 center">
      <img src="{{url('storage',$xray->photo)}}" class="img-fluid" alt="">
    </div>
    <form action="{{route('updateOralRadiology',$xray->id)}}" method="post">
      <div class="form-group row">
        <label for="description" class="col-sm-2">Description</label>
        <div class="col-sm-10">
          <input placeholder="Write down description" class="form-control @if ($errors->has('description')) is-invalid @endif" type="text" name="description" id="description" value="{{$xray->description}}">
          @if ($errors->has("description"))
            @foreach ($errors->get("description") as $msg)
              <div style='display:block' class='invalid-feedback'>{{$msg}}</div>
            @endforeach
          @endif
        </div>
      </div>
      @csrf
      @method('PUT')
      <button type="submit" class="btn btn-secondary submit-btn">Edit <span class="glyphicon glyphicon-edit"></span></button>
    </form>
  </div>
</div>
@endsection
