@extends('layout.master')
@section('title')
cases photos @if (isset($diagnose)&&!empty($diagnose)) Diagnosis Nr. {{$diagnose->id}}@elseif (isset($patient)&&!empty($patient))Patient {{$patient->pname}}@endif
@endsection
@section('container')
<div class="card">
  <div class="card-header">
    Cases Photos of
    @if (isset($diagnose)&&!empty($diagnose))
      <a href="{{route('showDiagnose',['id'=>$diagnose->id])}}">Diagnosis Nr. {{$diagnose->id}}</a>
    @elseif (isset($patient)&&!empty($patient))
      <a href="{{route('profilePatient',['id'=>$patient->id])}}">Patient {{$patient->pname}}</a>
    @endif
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
    @if ($cases_photos->count()>0)
      <div class="gallery row">
        <div class="col-12 col-sm-12 col-md-7 col-lg-8 col-xl-8 mt-3">
          <div class="w-100" style="position:relative">
            <div id="more_options">
              <span class="more_options" id="xray_description" class="mt-3 pl-3">@if($cases_photos->first()->before_after==0) Before @else After @endif</span>
              <a id="delete_xray_gallery" href="{{route('deleteCasePhoto',['id'=>$cases_photos->first()->id])}}"><span class="glyphicon glyphicon-trash"></span></a>
            </div>
            <img src="{{url('storage',$cases_photos->first()->photo)}}" id="display_xray_gallery" class="w-100 img-thumbnail" alt="">
          </div>
        </div>
        <div class="col-12 col-sm-12 col-md-5 col-lg-4 col-xl-4 mt-3">
          @if($cases_photos->where('before_after',0)->count()>0)
          <h4 class="center">Before</h4>
          <div class="row">
          @foreach ($cases_photos as $photo)
          @if ($photo->before_after==0)
          <div class="col-6 col-sm-4 col-md-6 col-lg-6 col-xl-6">
            <img src="{{url('storage',$photo->photo)}}" style="height:100px" data-edit="" data-delete="{{route('deleteCasePhoto',['id'=>$photo->id])}}" alt="@if($photo->before_after==0) Before @else After @endif" class="w-100 img-thumbnail mb-3 gallery_items @if ($photo->photo == $cases_photos->first()->photo)
              selected
            @endif" alt="">
          </div>
          @endif
          @endforeach
          </div>
          @endif
          @if($cases_photos->where('before_after',1)->count()>0)
          <h4 class="center">After</h4>
          <div class="row">
          @foreach ($cases_photos as $photo)
          @if ($photo->before_after==1)
          <div class="col-6 col-sm-4 col-md-6 col-lg-6 col-xl-6">
            <img src="{{url('storage',$photo->photo)}}" style="height:100px" data-edit="" data-delete="{{route('deleteCasePhoto',['id'=>$photo->id])}}" alt="@if($photo->before_after==0) Before @else After @endif" class="w-100 img-thumbnail mb-3 gallery_items @if ($photo->photo == $cases_photos->first()->photo)
              selected
            @endif" alt="">
          </div>
          @endif
          @endforeach
          </div>
          @endif
        </div>
      </div>
    @else
    <div class="alert alert-warning"><span class="glyphicon glyphicon-exclamation-sign"></span> There is no case photos</div>
    @endif
  </div>
</div>
<div class="float_form_container">
  <div id="delete_xray" class="float_form bg-home">
    <span class="close bg-home">&times;</span>
      <h4 class="center mb-3">Are you sure that you want to delete this Case Photo?</h4>
      <div class="center">
        <a style="width: 150px; display: inline-block;" class="btn btn-danger">YES</a>
        <button style="width: 150px; display: inline-block;" type="button" class="close_button btn btn-secondary">NO</button>
      </div>
  </div>
</div>
@endsection
