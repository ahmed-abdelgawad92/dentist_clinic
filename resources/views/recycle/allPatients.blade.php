@extends("layout.master")
@section("title","Deleted Patients")
@section('container')
<div class="card">
  <div class="card-header">
    <h4>Deleted Patients <img src="{{asset('recycle.ico')}}" class="float-right recycle" alt=""></h4>
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
    @if ($patients->count()>0)
      @php
        $count=1;
      @endphp
    <table class="table table-striped">
      <tr>
        <th>#</th>
        <th>Patient</th>
        <th>Gender</th>
        <th>Phone</th>
        <th>Birth Date</th>
        <th>Creation Date</th>
        <th>Deletion Date</th>
        <th>Action</th>
      </tr>
      @foreach ($patients as $patient)
      <tr>
        <td>{{$count++}}</td>
        <td><a href="{{route('profilePatient',['id'=>$patient->id])}}">{{$patient->pname}}</a></td>
        @if ($patient->gender==0)
        <td>female</td>
        @else
        <td>male</td>
        @endif
        <td>{{$patient->phone}}</td>
        <td style="white-space:nowrap">{{date('d-m-Y',strtotime($patient->dob))}}</td>
        <td style="white-space:nowrap">{{date('d-m-Y h:i a',strtotime($patient->created_at))}}</td>
        <td style="white-space:nowrap">{{date('d-m-Y h:i a',strtotime($patient->updated_at))}}</td>
        <td style="white-space:nowrap">
          <a href="{{route('recoverPatient',['id'=>$patient->id])}}" class="btn btn-success mr-1">recovery</a>
          <a href="{{route('deletePerPatient',['id'=>$patient->id])}}" class="btn btn-danger">delete</a>
        </td>
      </tr>
      @endforeach
    </table>
    @else
    <div class="alert alert-warning">There is no deleted Patients</div>
    @endif
  </div>
</div>
@endsection
