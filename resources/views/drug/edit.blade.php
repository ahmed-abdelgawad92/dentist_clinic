@extends('layout.master')
@section("title","Edit Drug")
@section('container')
<div class="card">
  <div class="card-header">
    Edit Drug / <a href="{{route('showAllDrugs',['id'=>$drug->diagnose->id])}}">Back to Prescription</a>
  </div>
  <div class="card-body">
    @if (session('error')!=null)
    <div class="alert alert-danger">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
      <h4>Error</h4>
      {!!session("error")!!}
    </div>
    @endif
    @if (session("success"))
    <div class="alert alert-success">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
      <h4>Completed Successfully</h4>
      {!!session("success")!!}
    </div>
    @endif
    <form action="{{route('updateDrug',['id'=>$drug->id])}}" method="post">
      {{-- <div class="form-group row">
        <label for="drug" class="col-sm-2">Drug</label>
        <div class="col-sm-10 input-group">
          <input type="text" class="form-control" list="list" name="drug" value="{{$drug->drug->name}}" id="drug" autofocus placeholder="Enter a drug">
          <datalist id="list">
            @foreach ($drugs as $drug_item)
              <option value="{{$drug_item->id}}">{{$drug_item->name}}</option>
            @endforeach
          </datalist>
        </div>
        <label for="dose" class="col-sm-2 mt-3">Dose</label>
        <div class="col-sm-10 input-group">
          <input type="text" name="dose" id="dose" value="{{$drug->dose}}" placeholder="Write down the dose of this drug"  class="mt-3 form-control">
        </div>
      </div> --}}
      <div class="form-group row drug_input">
        <label class="col-sm-2">Medicine</label>
        <div class="col-sm-10 ">
          <div class="input-group">
            @if ($drugs->count()>0)
              <select name="drug_list" class="custom-select">
                <option value="">select a medicine from here, if it exists Or Enter a new in the next box</option>
                @foreach ($drugs as $drug_item)
                  <option @if ($drug_item->id==$drug->drug_id) selected @endif value="{{$drug_item->id}}">{{$drug_item->name}}</option>
                @endforeach
              </select>
            @endif
            <input type="text" class="form-control" name="drug" placeholder="Enter a new drug">
          </div>
        </div>
        <label class="col-sm-2 mt-3">Dose</label>
        <div class="col-sm-10 input-group">
          <input type="text" name="dose" value="{{$drug->dose}}" placeholder="Write down the dose of this drug"  class="mt-3 form-control">
        </div>
      </div>
      @csrf
      @method('PUT')
      <div class="center">
        <button style="width: 150px; display: inline-block;" type="submit" id="add_drug_to_prescription" class="btn btn-secondary">Edit <span class="glyphicon glyphicon-edit"></span></button>
      </div>
    </form>
  </div>
</div>
@endsection
