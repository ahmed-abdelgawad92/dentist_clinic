@extends("layout.master")
@section("title",'all drugs on the system')
@section('container')
<div class="card">
  <div class="card-header">
    <h4>All Medicines on System <a href="{{route('addSystemDrug')}}" class="btn btn-home float-right">Create Medicine</a></h4>
  </div>
  <div id="drugComponent">
    <div class="card-body">
    <drug-component drugs="{{$drugs}}"></drug-component>
    </div>
  </div>
</div>
<script src="{{asset('js/app.js')}}"></script>   
@endsection