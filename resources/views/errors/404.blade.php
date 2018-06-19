@extends('layout.master')
@section("title","Page Not Found")
@section('container')
  <div class="text-center">
    <img src="{{asset('page404.png')}}" alt="">
    <h2>Error</h2>
    <h4>Sorry the page you are looking for is not found</h4>
  </div>

@endsection
