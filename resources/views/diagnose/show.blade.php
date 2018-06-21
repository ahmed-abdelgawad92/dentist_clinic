@extends('layout.master')
@section('title')
  {{$patient->pname}}'s Diagnosis
@endsection
@section('container')
<div class="card">
  <div class="card-header">
    <h4>Display Diagnosis of Patient "<a href="{{route('profilePatient',['id'=>$patient->id])}}">{{$patient->pname}}</a>"</h4>
  </div>
  <div class="card-body">
    @if (session('error')!=null)
    <div class="alert alert-danger">
      <h4>Error</h4>
      {{session("error")}}
    </div>
    @endif
    @if (session("success"))
    <div class="alert alert-success">
      <h4>Completed Successfully</h4>
      {{session("success")}}
    </div>
    @endif
    <div class="svg">
      <img src="{{asset('teeth.png')}}" alt="" class="diagnose_chart">
      <svg class="svg" xmlns="http://www.w3.org/2000/svg" version="1.1">
        @php
          print($svg);
        @endphp
      </svg>
    </div>
    <div class="center">
      <button type="button" id="show_diagnose_img" class="btn btn-secondary"><span class="glyphicon glyphicon-chevron-down"></span></button>
    </div>
    <table class="table table-striped info mt-3">
      <caption>Creation Date {{$diagnose->created_at}}</caption>
      <tr>
        <th>Tooth Name</th>
        <th>Diagnosis</th>
      </tr>
      @foreach ($diagnoseArray as $item)
      @php
        $array=explode(">>>",$item);
      @endphp
      <tr>
      <td>{{$array[0]}}</td>
      <td>{{$array[1]}}</td>
      </tr>
      @endforeach
    </table>
  </div>
</div>
@endsection
