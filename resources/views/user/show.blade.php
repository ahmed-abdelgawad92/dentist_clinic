@extends('layout.master')
@section('title')
{{$user->uname}}'s profile
@endsection
@section('container')
<div class="card">
  <div class="card-header">
    <h4>{{ucwords($user->uname)}}'s Profile</h4>
  </div>
  <div class="card-body">
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
    <div class="row">
      <div class="col-md-4 col-lg-3 col-sm-5 col-6 offset-3 offset-md-0 offset-lg-0 offset-sm-0">
        <div id="profile-div">
        @if(Storage::disk('local')->exists($user->photo))
        <img src="{{Storage::url($user->photo)}}" id="patient_profile_photo" alt="{{$user->name}}" class="profile rounded-circle">
        @else
        <img src="{{asset('unknown.png')}}" id="patient_profile_photo" alt="{{$user->name}}" class="profile rounded-circle">
        @endif
        <form id="change_profile_pic_form" action="{{route('uploadUserPhoto',['id'=>$user->id])}}" method="post" enctype="multipart/form-data">
          <input type="file" name="photo" id="photo">
          @method('PUT')
          @csrf
        </form>
        <span class="glyphicon glyphicon-picture"></span>
        </div>
        <div class="center" id="change_profile_pic">
          <label></label><button class="btn btn-home ml-3" id="upload_new_photo">upload photo</button>
        </div>
        <h4 class="center">{{ucwords($user->name)}}</h4>
        <h4 class="center" title="Phone No.">{{$user->phone}}</h4>
      </div>
      <div class="col-md-9 col-lg-9 col-sm-12 col-12">
        <div class="controls">
          <h4>Account Details</h4>
          <div class="btn-group">
            @if($user->id==auth()->user()->id)
            <a href="{{route("changePassword")}}" class="btn btn-home">change password</a>
            @endif
            @if(auth()->user()->role==1)
            <a href="{{route("updateUser",["id"=>$user->id])}}" class="btn btn-secondary">edit</a>
            @endif
          </div>
        </div>
        <table class="table table-striped info">
          <tr>
            <th>Full Name</th>
            <td>{{$user->name}}</td>
          </tr>
          <tr>
            <th>Username</th>
            <td>{{$user->uname}}</td>
          </tr>
          <tr>
            <th>Phone</th>
            <td>{{$user->phone}}</td>
          </tr>
          <tr>
            <th>Role</th>
            <td>@if ($user->role==0) User @else Admin @endif </td>
          </tr>
        </table>
      </div>
    </div>
    <div class="row">
      <div class="col-10 offset-1 mt-3">
      <h4 class="center">Last {{$user_logs->count()}} processes the user made in Users</h4>
      @if($user_logs->count()>0)
      @php
        $count=1;
      @endphp
      <table class="table table-striped">
        <tr>
          <th>#</th>
          <th>Affected User</th>
          <th>Process</th>
          <th>Description</th>
          <th>Date</th>
        </tr>
        @foreach ($user_logs as $log)
        <tr>
          <th>{{$count++}}</th>
          <th><a href="{{route('showUser',['id'=>$log->affected_row])}}">{{$log->userName()}}</a></th>
          <th>{{$log->process_type}}</th>
          <th>{{$log->description}}</th>
          <th style="white-space:nowrap;">{{date("d-m-Y h:i a",strtotime($log->created_at))}}</th>
        </tr>
        @endforeach
      </table>
      <a href="{{route('showAllUserLog',['id'=>$user->id,'table'=>'users'])}}">show all processes this user made in Users</a>
      @else
      <div class="alert alert-warning">
        He didn't make any process on Users
      </div>
      @endif
      </div>
    </div>
    <div class="row">
      <div class="col-10 offset-1 mt-3">
      <h4 class="center">Last {{$patient_logs->count()}} processes the user made in Patients</h4>
      @if($patient_logs->count()>0)
      @php
        $count=1;
      @endphp
      <table class="table table-striped">
        <tr>
          <th>#</th>
          <th>Affected Patient</th>
          <th>Process</th>
          <th>Description</th>
          <th>Date</th>
        </tr>
        @foreach ($patient_logs as $log)
        <tr>
          <th>{{$count++}}</th>
          <th><a href="{{route('profilePatient',['id'=>$log->affected_row])}}">{{$log->patient()}}</a></th>
          <th>{{$log->process_type}}</th>
          <th>{{$log->description}}</th>
          <th style="white-space:nowrap;">{{date("d-m-Y h:i a",strtotime($log->created_at))}}</th>
        </tr>
        @endforeach
      </table>
      <a href="{{route('showAllUserLog',['id'=>$user->id,'table'=>'patients'])}}">show all processes this user made in Patients</a>
      @else
      <div class="alert alert-warning">
        He didn't make any process on Patients
      </div>
      @endif
      </div>
    </div>
    <div class="row">
      <div class="col-10 offset-1 mt-3">
      <h4 class="center">Last {{$diagnose_logs->count()}} processes the user made in Diagnosis</h4>
      @if($diagnose_logs->count()>0)
      @php
        $count=1;
      @endphp
      <table class="table table-striped">
        <tr>
          <th>#</th>
          <th>Affected Diagnosis</th>
          <th>Process</th>
          <th>Description</th>
          <th>Date</th>
        </tr>
        @foreach ($diagnose_logs as $log)
        <tr>
          <th>{{$count++}}</th>
          <th style="white-space:nowrap;"><a href="{{route('showDiagnose',['id'=>$log->affected_row])}}">Diagnosis Nr. {{$log->diagnose()}}</a></th>
          <th>{{$log->process_type}}</th>
          <th>{{$log->description}}</th>
          <th style="white-space:nowrap;">{{date("d-m-Y h:i a",strtotime($log->created_at))}}</th>
        </tr>
        @endforeach
      </table>
      <a href="{{route('showAllUserLog',['id'=>$user->id,'table'=>'diagnoses'])}}">show all processes this user made in Diagnosis</a>
      @else
      <div class="alert alert-warning">
        He didn't make any process on Diagnosis
      </div>
      @endif
      </div>
    </div>
    <div class="row">
      <div class="col-10 offset-1 mt-3">
      <h4 class="center">Last {{$visit_logs->count()}} processes the user made in Visits</h4>
      @if($visit_logs->count()>0)
      @php
        $count=1;
      @endphp
      <table class="table table-striped">
        <tr>
          <th>#</th>
          <th>Affected Visit</th>
          <th>Process</th>
          <th>Description</th>
          <th>Date</th>
        </tr>
        @foreach ($visit_logs as $log)
        <tr>
          <th>{{$count++}}</th>
          <th style="white-space:nowrap;"><a href="">Visit Nr. {{$log->appointment()}}</a></th>
          <th>{{$log->process_type}}</th>
          <th>{{$log->description}}</th>
          <th style="white-space:nowrap;">{{date("d-m-Y h:i a",strtotime($log->created_at))}}</th>
        </tr>
        @endforeach
      </table>
      <a href="{{route('showAllUserLog',['id'=>$user->id,'table'=>'appointments'])}}">show all processes this user made in Visits</a>
      @else
      <div class="alert alert-warning">
        He didn't make any process on Visits
      </div>
      @endif
      </div>
    </div>
    <div class="row">
      <div class="col-10 offset-1 mt-3">
      <h4 class="center">Last {{$xray_logs->count()}} processes the user made in X-rays</h4>
      @if($xray_logs->count()>0)
      @php
        $count=1;
      @endphp
      <table class="table table-striped">
        <tr>
          <th>#</th>
          <th>Affected X-ray</th>
          <th>Process</th>
          <th>Description</th>
          <th>Date</th>
        </tr>
        @foreach ($xray_logs as $log)
        <tr>
          <th>{{$count++}}</th>
          <th><a class="show-xray" href="{{url('storage/'.$log->xray())}}">{{$log->xray()}}</a></th>
          <th>{{$log->process_type}}</th>
          <th>{{$log->description}}</th>
          <th style="white-space:nowrap;">{{date("d-m-Y h:i a",strtotime($log->created_at))}}</th>
        </tr>
        @endforeach
      </table>
      <a href="{{route('showAllUserLog',['id'=>$user->id,'table'=>'oral_radiologies'])}}">show all processes this user made in X-rays</a>
      @else
      <div class="alert alert-warning">
        He didn't make any process on X-rays
      </div>
      @endif
      </div>
    </div>
    <div class="row">
      <div class="col-10 offset-1 mt-3">
      <h4 class="center">Last {{$drug_logs->count()}} processes the user made in Medications</h4>
      @if($drug_logs->count()>0)
      @php
        $count=1;
      @endphp
      <table class="table table-striped">
        <tr>
          <th>#</th>
          <th>Affected Medication</th>
          <th>Process</th>
          <th>Description</th>
          <th>Date</th>
        </tr>
        @foreach ($drug_logs as $log)
        <tr>
          <th>{{$count++}}</th>
          <th>{{$log->drug()}}</th>
          <th>{{$log->process_type}}</th>
          <th>{{$log->description}}</th>
          <th style="white-space:nowrap;">{{date("d-m-Y h:i a",strtotime($log->created_at))}}</th>
        </tr>
        @endforeach
      </table>
      <a href="{{route('showAllUserLog',['id'=>$user->id,'table'=>'drugs'])}}">show all processes this user made in Medications</a>
      @else
      <div class="alert alert-warning">
        He didn't make any process on Medications
      </div>
      @endif
      </div>
    </div>
    <div class="row">
      <div class="col-10 offset-1 mt-3">
      <h4 class="center">Last {{$working_times_logs->count()}} processes the user made in Medications</h4>
      @if($working_times_logs->count()>0)
      @php
        $count=1;
      @endphp
      <table class="table table-striped">
        <tr>
          <th>#</th>
          <th>Affected Time</th>
          <th>Process</th>
          <th>Description</th>
          <th>Date</th>
        </tr>
        @foreach ($working_times_logs as $log)
        <tr>
          <th>{{$count++}}</th>
          <th>{{$log->working_time()}}</th>
          <th>{{$log->process_type}}</th>
          <th>{{$log->description}}</th>
          <th style="white-space:nowrap;">{{date("d-m-Y h:i a",strtotime($log->created_at))}}</th>
        </tr>
        @endforeach
      </table>
      <a href="{{route('showAllUserLog',['id'=>$user->id,'table'=>'working_times'])}}">show all processes this user made in Working Times</a>
      @else
      <div class="alert alert-warning">
        He didn't make any process on Working Times
      </div>
      @endif
      </div>
    </div>
  </div>
</div>
@endsection
