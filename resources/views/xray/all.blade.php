@extends('layout.master')
@section('title','All X-rays')
@section('container')
<div class="card">
  <div class="card-header">
    <h4>All X-rays of <a href="{{route('showDiagnose',['id'=>$diagnose->id])}}">This Diagnosis</a></h4>
  </div>
  <div class="card-body" style="background: rgba(0,0,0,0.8);">
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
    @if ($xrays->count()>0)
    <div class="gallery row">
      <div class="col-12 col-sm-12 col-md-7 col-lg-8 col-xl-8 mt-3">
        <div class="w-100" style="position:relative">
          <div id="more_options">
            <a id="xray_edit_span" href="{{route("updateOralRadiology",['id'=>$xrays->first()->id])}}"><span class="glyphicon glyphicon-edit"></span></a>
            <a id="delete_xray_gallery" href="{{route("deleteOralRadiology",['id'=>$xrays->first()->id])}}"><span class="glyphicon glyphicon-trash"></span></a>
          </div>
          <img src="{{url('storage',$xrays->first()->photo)}}" id="display_xray_gallery" class="w-100 img-thumbnail" alt="">
          <p id="xray_description" class="mt-3 pl-3">{{$xrays->first()->description}}</p>
        </div>
      </div>
      <div class="col-12 col-sm-12 col-md-5 col-lg-4 col-xl-4 row mt-3">
        @foreach ($xrays as $xray)
        <div class="col-6 col-sm-4 col-md-6 col-lg-6 col-xl-6">
          <img src="{{url('storage',$xray->photo)}}" style="height:100px" data-edit="{{route("updateOralRadiology",['id'=>$xray->id])}}" data-delete="{{route('deleteOralRadiology',['id'=>$xray->id])}}" alt="{{$xray->description}}" class="w-100 img-thumbnail mb-3 gallery_items @if ($xray->photo == $xrays->first()->photo)
              selected
          @endif" alt="">
        </div>
        @endforeach
      </div>
    </div>
    @else
    <div class="alert alert-warning">
      There is no X-rays
    </div>
    @endif
  </div>
</div>
<div class="float_form_container">
  <div id="delete_xray" class="float_form bg-home">
    <span class="close bg-home">&times;</span>
      <h4 class="center mb-3">Are you sure that you want to delete this X-ray?</h4>
      <div class="center">
        <a style="width: 150px; display: inline-block;" class="btn btn-danger">YES</a>
        <button style="width: 150px; display: inline-block;" type="button" class="close_button btn btn-secondary">NO</button>
      </div>
  </div>
</div>
@endsection
