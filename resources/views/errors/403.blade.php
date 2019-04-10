@extends('layout.master')
@section("title","Access Denied")
@section('container')
  <div class="text-center">
    <img class="rounded" src="{{asset('stop.jpg')}}" alt="">
    <h2>Error</h2>
    <h4>Sorry you are not allowed to access this page.</h4>
  </div>
@endsection
