@extends("layout.master")
@section("title")
  Patient {{ucwords($patient->pname)}}
@endsection
@section('container')
<div class="card">
  <div class="card-header">
    {{$card_title}}
  </div>
  <div class="card-body">
    @if (session("success")!=null)
    <div class="alert alert-success alert-dismissible fade show">
      <h4 class="alert-heading">Completed Successfully</h4>
      {{session("success")}}
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    @endif
    @if (session("error")!=null)
    <div class="alert alert-danger alert-dismissible fade show">
      <h4 class="alert-heading">Error</h4>
      {{session("error")}}
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    @endif
    <div class="row">
      <div class="col-md-4 col-lg-3 col-sm-5 col-6 offset-3 offset-md-0 offset-lg-0 offset-sm-0">
        <div id="profile-div">
        @if(Storage::disk('local')->exists($patient->photo))
        <img src="{{url('storage/'.$patient->photo)}}" id="patient_profile_photo" alt="{{$patient->pname}}" class="profile rounded-circle">
        @else
        <img src="{{asset('unknown.png')}}" id="patient_profile_photo" alt="{{$patient->pname}}" class="profile rounded-circle">
        @endif
        </div>
        <h4 class="center">{{ucwords($patient->pname)}}</h4>
        <h4 class="center" title="Phone No.">{{$patient->phone}}</h4>
      </div>
      <div class="col-md-8 col-lg-9 col-sm-7 col-12">
      </div>
    </div>
  </div>
</div>
@endsection
