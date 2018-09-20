@extends("layout.master")
@section("title",'all drugs on the system')
@section('container')
<div class="card">
  <div class="card-header">
    <h4>All Medicines on System <a href="{{route('addSystemDrug')}}" class="btn btn-home float-right">Create Medicine</a></h4>
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
    @if($drugs->count()>0)
    <form action="{{route('searchSystemDrug')}}" id="search_drug_form" method="post" style="position: relative" class="mb-3">
      <input type="text" autocomplete="off" name="search_drug" id="search_drug" placeholder="search for a medicine" class="form-control" value="">
      <button class="search" type="submit">
        <span class="glyphicon glyphicon-search"></span>
      </button>
      @csrf
    </form>
    <div id="loading" class="text-center" style="display:none">
      <img src="{{asset('loading.gif')}}" width="270px" height="200px" alt="">
    </div>
    <table class="table table-striped">
      <thead>
        <tr>
          <th>#</th>
          <th>Name</th>
          <th>Edit</th>
          <th>Delete</th>
        </tr>
      </thead>
      @php
        $count=1;
      @endphp
      <tbody id="drug_table">
      @foreach ($drugs as $drug)
        <tr>
          <td>{{$count++}}</td>
          <td>{{$drug->name}}</td>
          <td><a href="{{route('updateSystemDrug',$drug->id)}}" class="btn btn-secondary">edit <span class="glyphicon glyphicon-edit"></span></a></td>
          <td><a href="{{route('deleteSystemDrug',$drug->id)}}" class="btn delete_system_drug btn-danger">delete <span class="glyphicon glyphicon-trash"></span></a></td>
        </tr>
      @endforeach
      </tbody>
    </table>
    {!!$drugs->links()!!}
    @else
    <div class="alert alert-warning">There is no medications created on the system</div>
    @endif
  </div>
</div>
<div class="float_form_container">
  <div id="delete_system_drug" class="float_form bg-home">
    <span class="close bg-home">&times;</span>
      <h4 class="center mb-3">Are you sure that you want to delete this Medicine?</h4>
      <div class="center">
        <a style="width: 150px; display: inline-block;" class="btn btn-danger">YES</a>
        <button style="width: 150px; display: inline-block;" type="button" class="close_button btn btn-secondary">NO</button>
      </div>
  </div>
</div>
@endsection
