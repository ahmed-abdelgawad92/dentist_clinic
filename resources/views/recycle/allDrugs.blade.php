@extends("layout.master")
@section("title","Deleted Drugs")
@section('container')
<div class="card">
  <div class="card-header">
    <h4>Deleted Drugs <img src="{{asset('recycle.ico')}}" class="float-right recycle" alt=""></h4>
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
    @if ($drugs->count()>0)
      @php
        $count=1;
      @endphp
    <table class="table table-striped">
      <tr>
        <th>#</th>
        <th>Medication</th>
        <th>Creation Date</th>
        <th>Deletion Date</th>
        <th>Action</th>
      </tr>
      @foreach ($drugs as $drug)
      <tr>
        <td>{{$count++}}</td>
        <td>{{$drug->name}}</td>
        <td style="white-space:nowrap">{{date('d-m-Y h:i a',strtotime($drug->created_at))}}</td>
        <td style="white-space:nowrap">{{date('d-m-Y h:i a',strtotime($drug->updated_at))}}</td>
        <td style="white-space:nowrap">
          <a href="{{route('recoverDrug',['id'=>$drug->id])}}" class="btn btn-success mr-1">recovery</a>
        </td>
      </tr>
      @endforeach
    </table>
    @else
    <div class="alert alert-warning">There is no deleted Drugs</div>
    @endif
  </div>
</div>
@endsection
