@extends("layout.master")
@section("title","Deleted Working Times")
@section('container')
<div class="card">
  <div class="card-header">
    <h4>Deleted Working Times <img src="{{asset('recycle.ico')}}" class="float-right recycle" alt=""></h4>
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
    @if ($working_times->count()>0)
      @php
        $count=1;
      @endphp
    <table class="table table-striped">
      <tr>
        <th>#</th>
        <th>Day</th>
        <th>From</th>
        <th>To</th>
        <th>Creation Date</th>
        <th>Deletion Date</th>
        <th>Action</th>
      </tr>
      @foreach ($working_times as $working_time)
      <tr>
        <td>{{$count++}}</td>
        <td>{{$working_time->getDayName()}}</td>
        <td>{{date('h:i a',strtotime($working_time->time_from))}}</td>
        <td>{{date('h:i a',strtotime($working_time->time_to))}}</td>
        <td style="white-space:nowrap">{{date('d-m-Y h:i a',strtotime($working_time->created_at))}}</td>
        <td style="white-space:nowrap">{{date('d-m-Y h:i a',strtotime($working_time->updated_at))}}</td>
        <td style="white-space:nowrap">
          <a href="{{route('recoverWorkingTime',['id'=>$working_time->id])}}" class="btn btn-success mr-1">recovery</a>
          <a href="{{route('deletePerWorkingTime',['id'=>$working_time->id])}}" class="btn btn-danger">delete</a>
        </td>
      </tr>
      @endforeach
    </table>
    @else
    <div class="alert alert-warning">There is no deleted Working Times</div>
    @endif
  </div>
</div>
@endsection
