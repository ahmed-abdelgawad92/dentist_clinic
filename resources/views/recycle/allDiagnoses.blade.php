@extends("layout.master")
@section("title","Deleted Diagnosis")
@section('container')
<div class="card">
  <div class="card-header">
    <h4>Deleted Diagnosis <img src="{{asset('recycle.ico')}}" class="float-right recycle" alt=""></h4>
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
    @if ($diagnoses->count()>0)
      @php
        $count=1;
      @endphp
    <table class="table table-striped">
      <tr>
        <th>#</th>
        <th>Patient</th>
        <th>Diagnosis</th>
        <th>Total Paid</th>
        <th>Creation Date</th>
        <th>Deletion Date</th>
        <th>Action</th>
      </tr>
      @foreach ($diagnoses as $diagnose)
      <tr>
        <td>{{$count++}}</th>
        <td><a href="{{route('profilePatient',['id'=>$diagnose->patient_id])}}">{{$diagnose->patient->pname}}</a></td>
        <td><a href="{{route('showDiagnose',['id'=>$diagnose->id])}}">Diagnosis Nr. {{$diagnose->id}}</a></td>
        <td>{{$diagnose->total_paid+0}} EGP</td>
        <td style="white-space:nowrap">{{date('d-m-Y h:i a',strtotime($diagnose->created_at))}}</td>
        <td style="white-space:nowrap">{{date('d-m-Y h:i a',strtotime($diagnose->updated_at))}}</td>
        <td style="white-space:nowrap">
          <a href="{{route('recoverDiagnose',['id'=>$diagnose->id])}}" class="btn btn-success mr-1">recovery</a>
          <a href="{{route('deletePerDiagnose',['id'=>$diagnose->id])}}" class="btn btn-danger">delete</a>
        </td>
      </tr>
      @endforeach
    </table>
    @else
    <div class="alert alert-warning">There is no deleted Diagnosis</div>
    @endif
  </div>
</div>
@endsection
