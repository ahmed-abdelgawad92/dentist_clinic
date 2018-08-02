@extends('layout.master')
@section("title")
  @if (strpos(url()->current(),"search")===false)
    All Patients
  @else
    Search Patients
  @endif
@endsection
@section('container')
  @if (session('warning')!=null)
    <div class="alert alert-warning">
      <h4>{!!session('warning')!!}</h4>
    </div>
  @elseif ($patients->count()>0)
    <div class="card-header" style="margin-bottom:15px !important;">
      <h3>No. of Patients #{{$patients->count()}}</h3>
    </div>
    {{-- <form method="post" style="padding:5px;">
      <div class="input-group mb-3">
        <input type="text" class="form-control" placeholder="Search..." autofocus aria-label="Recipient's username" aria-describedby="basic-addon2">
        <div class="input-group-append">
          <button class="btn btn-outline-secondary" type="button">search</button>
        </div>
      </div>
    </form> --}}
    @foreach ($patients as $patient)
      <div class="card mb-3">
      <div class="card-body">
      <div class="row">
        <div class="col-3 col-sm-2 col-md-2 col-lg-2 border-right center">
          @if (Storage::disk('local')->exists($patient->photo))
            <a href="{{route('profilePatient',['id'=>$patient->id])}}"><img style="max-width:100px;"src="{{url('storage/'.$patient->photo)}}" class="profile rounded-circle" alt=""></a>
          @else
            <a href="{{route('profilePatient',['id'=>$patient->id])}}"><img style="max-width:100px;"src="{{asset('unknown.png')}}" class="profile rounded-circle" alt=""></a>
          @endif
        </div>
        <div class="col-9 col-sm-10 col-md-10 col-lg-10">
          <h4><a href="{{route('profilePatient',['id'=>$patient->id])}}">{{ucfirst($patient->pname)}}</a></h4>
          <p>File Number : {{$patient->id}} | Phone : {{ucfirst($patient->phone)}} | Address : {{ucfirst($patient->address)}}</p>
          <a href="{{route('profilePatient',['id'=>$patient->id])}}" class="btn btn-home">Details</a>
          <a href="{{route('updatePatient',['id'=>$patient->id])}}" class="btn btn-secondary">Edit</a>
          <a href="{{route('addDiagnose',['id'=>$patient->id])}}" class="btn btn-secondary">Add Diagnosis</a>
        </div>
      </div>
      </div>
    </div>
    @endforeach
    {{$patients->links()}}
  @else
    <div class="alert alert-warning">
      <h4>There is no patients</h4>
    </div>
  @endif
@endsection
