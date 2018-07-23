@extends('layout.master')
@section("title","Edit Diagnosis Nr. ".$diagnose->id)
@section('container')
<div class="card">
  <div class="card-header">
    Edit <a href="{{route('showDiagnose',['id'=>$diagnose->id])}}">Diagnose Nr. {{$diagnose->id}}</a> of Patient <a href="{{route('profilePatient',['id'=>$diagnose->patient_id])}}">{{$diagnose->patient->pname}}</a>
  </div>
  <div class="card-body">
    @if(session("error")!=null)
      <div class="alert alert-danger">
        <h4>Error Occured</h4>
        <div>{!!session("error")!!}</div>
      </div>
    @endif
    @if(session("success")!=null)
      <div class="alert alert-success">
        <h4>Successfully Completed</h4>
        <div>{!!session("success")!!}</div>
      </div>
    @endif
    @if(session("warning")!=null)
      <div class="alert alert-warning">
        <h4>Warning</h4>
        <div>{!!session("warning")!!}</div>
      </div>
    @endif
    @if (isset($svg)&&!empty($svg))
    <div class="svg" style="display: block !important;">
      <img src="{{asset('teeth.png')}}" alt="" id="diagnose_chart" class="using_map" usemap="#teeth">
      <svg class="svg" xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink">{!!$svg!!}</svg>
    </div>
    @endif
    <div id="loading" class="text-center" style="display:none">
      <img src="{{asset('loading.gif')}}" alt="">
    </div>
    <form id="edit-diagnose-form" action="{{route('updateDiagnose',["id"=>$diagnose->id])}}" method="post">
      @foreach ($teeth as $tooth)
      <div class="form-group row stripe" id="div_0">
        <label id="label_0" class="col-lg-2">{{ucwords($tooth->teeth_name)}}</label>
        <div class="col-lg-3">
          @php
            $selected=false;
          @endphp
          <select type="text" class="form-control mb-3 type-diagnose" name="diagnose_type[]">
            <option data-color="#123" @if($tooth->diagnose_type=="Cairous") selected @php $selected=true; @endphp @endif value="Cairous">Cairous</option>
            <option data-color="#adf" @if($tooth->diagnose_type=="Gingivitis") selected @php $selected=true; @endphp @endif value="Gingivitis">Gingivitis</option>
            <option data-color="#FBF606" @if($tooth->diagnose_type=="Ento Treatment") selected @php $selected=true; @endphp @endif value="Ento Treatment">Ento Treatment</option>
            <option data-color="#098" @if($tooth->diagnose_type=="Endo Treatment") selected @php $selected=true; @endphp @endif value="Endo Treatment">Endo Treatment</option>
            <option data-color="#FF9022" @if($tooth->diagnose_type=="Endo Retreatment") selected @php $selected=true; @endphp @endif value="Endo Retreatment">Endo Retreatment</option>
            <option data-color="#258" @if($tooth->diagnose_type=="Missed Tooth due to extraction") selected @php $selected=true; @endphp @endif value="Missed Tooth due to extraction">Missed Tooth due to extraction</option>
            <option data-color="#ff3333" @if($tooth->diagnose_type=="Missed tooth due to genitics") selected @php $selected=true; @endphp @endif value="Missed tooth due to genitics">Missed tooth due to genitics</option>
            <option data-color="#529" @if($tooth->diagnose_type=="Fixed Crown") selected @php $selected=true; @endphp @endif value="Fixed Crown">Fixed Crown</option>
            <option data-color="#951" @if($tooth->diagnose_type=="Fixed Bridge") selected @php $selected=true; @endphp @endif value="Fixed Bridge">Fixed Bridge</option>
            <option data-color="#f39" @if($tooth->diagnose_type=="Impacted teeth") selected @php $selected=true; @endphp @endif value="Impacted teeth">Impacted teeth</option>
            <option data-color="#5cf53d" @if($tooth->diagnose_type=="Extraction") selected @php $selected=true; @endphp @endif value="Extraction">Extraction</option>
            @if(!$selected)
            <option data-color="#2137ff" class="variation" selected value="{{$tooth->diagnose_type}}">{{$tooth->diagnose_type}}</option>
            @else
            <option data-color="#2137ff" class="variation" value="Variation">Variation</option>
            @endif
          </select>
          <input type="hidden" class="name" value="{{$tooth->teeth_name}}">
          <input type="hidden" name="teeth_color[]" class="color" value="{{$tooth->color}}">
          <input type="hidden" name="teeth_id[]" class="id" value="{{$tooth->id}}">
          <div class="input-group mb-3"><input type="text" class="form-control price" name="price[]" value="{{$tooth->price}}" placeholder="Price in EGP">
            <div class="input-group-append">
              <span class="input-group-text">EGP</span>
            </div>
          </div>
        </div>
        <div class="col-lg-7">
          <textarea autofocus="" name="description[]" placeholder="Write the Diagnosis" class="form-control diagnose_textarea">{{$tooth->description}}</textarea>
        </div>
        <div class="col-sm-12">
          <a href="" class="btn btn-danger action" data-action="#delete_diagnosis" data-url="{{route('deleteTeeth',['id'=>$tooth->id])}}">delete from diagnosis</a>
        </div>
      </div>
      @endforeach
      @method('PUT')
      @csrf
      <button class="btn btn-secondary btn-lg submit-btn">Edit Diagnosis</button>
    </form>
  </div>
</div>
<div class="float_form_container">
  <div id="delete_diagnosis" class="float_form bg-home">
    <span class="close" style="color:whitesmoke;">&times;</span>
      <h4 class="center mb-3">Are you sure that you want to delete this tooth?</h4>
      <div class="center">
        <a style="width: 150px; display: inline-block;" class="btn btn-danger">YES</a>
        <button style="width: 150px; display: inline-block;" type="button" class="close_button btn btn-secondary">NO</button>
      </div>
  </div>
</div>
@endsection
