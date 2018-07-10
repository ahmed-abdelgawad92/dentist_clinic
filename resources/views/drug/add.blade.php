@extends("layout.master")
@section('title',"Add a new Medicine to the system")
@section('container')
<div class="card">
  <div class="card-header">
    <h4>Add New Medicine to the system</h4>
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
    @if($errors->count()>0)
      <div class="alert alert-danger">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        @foreach ($errors->all() as $msg)
          {!!$msg!!} <br />
        @endforeach
      </div>
    @endif
    <form action="{{route('addSystemDrug')}}" method="post">
      <div id="new_drug">
      <div class="col-12 center mb-3">Medicine 1</div>
      <div class="form-group row drug_input">
        <label class="col-sm-2">Medicine</label>
        <div class="col-sm-10 ">
          <input type="text" class="form-control" name="drug[]" placeholder="Enter a new Medicine">
        </div>
      </div>
      </div>
      <div class="center">
        <input style="width: 150px; display: inline-block;" type="submit" class="btn btn-primary" value="create">
        <button style="width: 150px; display: inline-block;" type="button" id="add_new_drug" class="btn btn-secondary">Add new medicine</button>
      </div>
      @csrf
    </form>
  </div>
</div>
@endsection
