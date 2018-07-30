@extends('layout.master')
@section('title','home')
@section('container')
  <div class="container my-3">
  @if (session("success")!=null)
  <div class="alert alert-success alert-dismissible fade show">
    <h4 class="alert-heading">Completed Successfully</h4>
    {{session("success")}}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
  @endif
  <div class="row">
    <div class="col-md-4 col-sm-6 col-lg-4">
      <div class="card">
        <a href="{{route('addPatient')}}" class="home-img"><img src="{{asset('patient.png')}}" alt="" class="card-img-top"></a>
        <div class="card-body">
          <h5 class="card-title">Create new patient</h5>
          <p class="card-text">Here you can create a new patient and all the details you need to know about the patient.</p>
          <a href="{{route('addPatient')}}" class="btn btn-home">Create Now</a>
        </div>
      </div>
    </div>
    <div class="col-md-4 col-sm-6 col-lg-4">
      <div class="card">
        <a href="{{route('allAppointment',['date'=>date('Y-m-d')])}}" class="home-img"><img src="{{asset('calendar.png')}}" alt="" class="card-img-top"></a>
        <div class="card-body">
          <h5 class="card-title">Todays Visits</h5>
          <p class="card-text">Here you can check all of your todays appointments and edit,approve or cancel them.</p>
          <a href="{{route('allAppointment',['date'=>date('Y-m-d')])}}" class="btn btn-home">Check Now</a>
        </div>
      </div>
    </div>
    <div class="col-md-4 col-sm-6 col-lg-4">
      <div class="card">
        <a href="{{route('allPatient')}}" class="home-img"><img src="{{asset('list.png')}}" alt="" class="card-img-top"></a>
        <div class="card-body">
          <h5 class="card-title">Show All Patients</h5>
          <p class="card-text">Here you can get a list of all patients , you can select the one you want and add diagnosis</p>
          <a href="{{route('allPatient')}}" class="btn btn-home">Show Now</a>
        </div>
      </div>
    </div>
  </div>
  </div>
  @if($visits->count()>0)
    <h4 class="center mb-3">Today's Visits</h4>
  <div class="row mt-3">
    <div class="col-4">
      <div class="card">
        <div class="card-header bg-secondary center" style="color:white;">Not Approved</div>
          <ul class="list-group list-group-flush">
            @if ($visits->where('approved',2)->count()>0)
            @foreach ($visits as $v)
            @if ($v->approved==2)
              <li class="list-group-item">
                <div class="row">
                <div class="col-2">
                @if(Storage::disk('local')->exists($v->patient()->photo))
                <img src="{{Storage::url($v->patient()->photo)}}" alt="" class="list-img">
                @else
                <img src="{{asset('unknown.png')}}" alt="" class="list-img">
                @endif
                </div>
                <div class="col-10">
                <a href="{{route('profilePatient',['id'=>$v->patient()->id])}}">{{ucwords($v->patient()->pname)}}</a>
                <a href="{{route('approveAppointment',['id'=>$v->id])}}" class="btn btn-home float-right">approve</a>
                <br>
                {{date('h:i a',strtotime($v->time))}}
                </div>
                </div>
              </li>
            @endif
            @endforeach
            @else
            <li class="list-group-item">No More Visits</li>
            @endif
          </ul>
      </div>
    </div>
    <div class="col-4">
      <div class="card">
        <div class="card-header bg-home center">Waiting Room</div>
          <ul class="list-group list-group-flush">
            @if ($visits->where('approved',3)->count()>0)
            @foreach ($visits as $v)
            @if ($v->approved==3)
              <li class="list-group-item">
                <div class="row">
                <div class="col-2">
                @if(Storage::disk('local')->exists($v->patient()->photo))
                <img src="{{Storage::url($v->patient()->photo)}}" alt="" class="list-img">
                @else
                <img src="{{asset('unknown.png')}}" alt="" class="list-img">
                @endif
                </div>
                <div class="col-10">
                <a href="{{route('profilePatient',['id'=>$v->patient()->id])}}">{{ucwords($v->patient()->pname)}}</a>
                <a href="{{route('endAppointment',['id'=>$v->id])}}" class="btn btn-success float-right">finish</a>
                <br>
                {{date('h:i a',strtotime($v->time))}}
                </div>
                </div>
              </li>
            @endif
            @endforeach
            @else
            <li class="list-group-item">No Patients in waiting room</li>
            @endif
          </ul>
      </div>
    </div>
    <div class="col-4">
      <div class="card">
        <div class="card-header bg-success center" style="color:white;">Finished</div>
          <ul class="list-group list-group-flush">
            @if ($visits->where('approved',1)->count()>0)
            @foreach ($visits as $v)
            @if ($v->approved==1)
              <li class="list-group-item">
                <div class="row">
                <div class="col-2">
                @if(Storage::disk('local')->exists($v->patient()->photo))
                <img src="{{Storage::url($v->patient()->photo)}}" alt="" class="list-img">
                @else
                <img src="{{asset('unknown.png')}}" alt="" class="list-img">
                @endif
                </div>
                <div class="col-10">
                <a href="{{route('profilePatient',['id'=>$v->patient()->id])}}">{{ucwords($v->patient()->pname)}}</a>
                <br>
                {{date('h:i a',strtotime($v->time))}}
                </div>
                </div>
              </li>
            @endif
            @endforeach
            @else
            <li class="list-group-item">No Finished Visits</li>
            @endif
          </ul>
      </div>
    </div>
  </div>
  @else
    <div class="alert alert-warning">
      There is no visits today
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
  @endif
@endsection
