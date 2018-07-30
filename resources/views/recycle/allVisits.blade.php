@extends("layout.master")
@section("title","Deleted Visits")
@section('container')
<div class="card">
  <div class="card-header">
    <h4>Deleted Visits <img src="{{asset('recycle.ico')}}" class="float-right recycle" alt=""></h4>
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
    @if ($visits->count()>0)
      @php
        $count=1;
      @endphp
    <table class="table table-striped">
      <tr>
        <th>#</th>
        <th>Diagnosis</th>
        <th>Date</th>
        <th>Time</th>
        <th>Treatment</th>
        <th>Created at</th>
        <th>Deleted at</th>
        <th>Action</th>
      </tr>
      @foreach ($visits as $visit)
      <tr>
        <td>{{$count++}}</td>
        <td><a href="{{route('showDiagnose',['id'=>$visit->diagnose_id])}}">Diagnosis Nr. {{$visit->diagnose_id}}</a></td>
        <td style="white-space:nowrap">{{date('d-m-Y h:i a',strtotime($visit->date))}}</td>
        <td style="white-space:nowrap">{{date('d-m-Y h:i a',strtotime($visit->time))}}</td>
        <td>{{$visit->treatment}}</td>
        <td style="white-space:nowrap">{{date('d-m-Y h:i a',strtotime($visit->created_at))}}</td>
        <td style="white-space:nowrap">{{date('d-m-Y h:i a',strtotime($visit->updated_at))}}</td>
        <td style="white-space:nowrap">
          <a href="{{route('recoverAppointment',['id'=>$visit->id])}}" class="btn btn-success mr-1">recovery</a>
          <a href="{{route('deletePerAppointment',['id'=>$visit->id])}}" class="btn btn-danger">delete</a>
        </td>
      </tr>
      @endforeach
    </table>
    @else
    <div class="alert alert-warning">There is no deleted visits</div>
    @endif
  </div>
</div>
@endsection
