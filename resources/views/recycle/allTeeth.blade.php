@extends("layout.master")
@section("title","Deleted Teeth")
@section('container')
<div class="card">
  <div class="card-header">
    <h4>Deleted Teeth <img src="{{asset('recycle.ico')}}" class="float-right recycle" alt=""></h4>
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
    @if ($teeth->count()>0)
      @php
        $count=1;
      @endphp
    <table class="table table-striped">
      <tr>
        <th>#</th>
        <th>Diagnosis</th>
        <th>Tooth Name</th>
        <th>Diagnosis Type</th>
        <th>Description</th>
        <th>Price</th>
        <th>Deletion Date</th>
        <th>Action</th>
      </tr>
      @foreach ($teeth as $tooth)
      <tr>
        <td>{{$count++}}</td>
        <td><a href="{{route('showDiagnose',['id'=>$tooth->diagnose_id])}}">Diagnosis Nr. {{$tooth->diagnose_id}}</a></td>
        <td>{{$tooth->teeth_name}}</td>
        <td>{{$tooth->diagnose_type}}</td>
        <td>{{$tooth->description}}</td>
        <td>{{$tooth->price+0}} EGP</td>
        <td style="white-space:nowrap">{{date('d-m-Y h:i a',strtotime($tooth->updated_at))}}</td>
        <td style="white-space:nowrap">
          <a href="{{route('recoverTooth',['id'=>$tooth->id])}}" class="btn btn-success mr-1">recovery</a>
          <a href="{{route('deletePerTooth',['id'=>$tooth->id])}}" class="btn btn-danger">delete</a>
        </td>
      </tr>
      @endforeach
    </table>
    @else
    <div class="alert alert-warning">There is no deleted teeth</div>
    @endif
  </div>
</div>
@endsection
