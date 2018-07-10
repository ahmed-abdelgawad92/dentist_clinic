@extends('layout.master')
@section("title","Edit Medicine")
@section('container')
<div class="card">
  <div class="card-header">
    Edit Medicine / <a href="{{route('showAllSystemDrugs',['id'=>$drug->id])}}">Back to All Medicines</a>
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
    <form action="{{route('updateSystemDrug',['id'=>$drug->id])}}" method="post">
      <div class="form-group row">
        <label for="drug" class="col-sm-2">Medicine</label>
        <div class="col-sm-10">
          <input type="text" class="form-control @if ($errors->has('drug')) is-invalid  @endif" name="drug" value="{{$drug->name}}" id="drug" autofocus placeholder="Enter a medicine">
          @if ($errors->has("drug"))
            @foreach ($errors->get("drug") as $msg)
              <div style='display:block' class='invalid-feedback'>{{$msg}}</div>
            @endforeach
          @endif
        </div>
      </div>
      @csrf
      @method('PUT')
      <div class="center">
        <button style="width: 150px; display: inline-block;" type="submit" class="btn btn-secondary">Edit <span class="glyphicon glyphicon-edit"></span></button>
      </div>
    </form>
  </div>
</div>
@endsection
