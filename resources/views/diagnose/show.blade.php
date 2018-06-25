@extends('layout.master')
@section('title')
  {{$patient->pname}}'s Diagnosis
@endsection
@section('container')
<div class="card">
  <div class="card-header">
    <h4>Display Diagnosis of Patient "<a href="{{route('profilePatient',['id'=>$patient->id])}}">{{$patient->pname}}</a>"</h4>
  </div>
  <div class="card-body">
    <div class="nav justify-content-center mb-3">
      <div class="btn-group" role="group" aria-label="Basic example">
        @if ($diagnose->already_payed!=$diagnose->total_price)
        <a class="btn btn-home control action" data-action="#add_payment" data-url="/patient/diagnosis/{{$diagnose->id}}/add/payment">Add Payment</a>
        @endif
        @if ($diagnose->total_price==null)
        <a class="btn btn-home control action" data-action="#add_total_price" data-url="/patient/diagnosis/{{$diagnose->id}}/add/total_price">Add Total Price</a>
        @endif
        <a class="btn btn-home control action" data-action="#add_oral_radiology" data-url="/patient/diagnosis/{{$diagnose->id}}/add/oralradiology">Add Dental X-ray</a>
        <a class="btn btn-home control action" data-action="#add_drug" data-url="/patient/diagnosis/{{$diagnose->id}}/add/drug">Add Prescription</a>
        @if ($diagnose->done==0)
        <a class="btn btn-home control action">Add Visit</a>
        @endif
        <a href="{{route('updateDiagnose',['id'=>$diagnose->id])}}" class="btn btn-secondary control">Edit <span class="glyphicon glyphicon-edit"></span></a>
        <a class="btn btn-danger control action" data-action="#delete" data-url="/patient/diagnosis/delete/{{$diagnose->id}}">Delete <span class="glyphicon glyphicon-trash"></span></a>
        @if ($diagnose->done==0)
        <a class="btn btn-success control action" data-action="#finish" data-url="/patient/diagnosis/{{$diagnose->id}}/finish">Finish</a>
        @endif
      </div>
    </div>
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
    @if($errors->count()>0)
      <div class="alert alert-danger">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        @foreach ($errors->all() as $msg)
          {{$msg}} <br />
        @endforeach
      </div>
    @endif
    <div class="svg">
      <img src="{{asset('teeth.png')}}" alt="" class="diagnose_chart">
      <svg class="svg" xmlns="http://www.w3.org/2000/svg" version="1.1">
        @php
          print($svg);
        @endphp
      </svg>
    </div>
    <div class="center">
      <button type="button" id="show_diagnose_img" class="btn btn-secondary mt-3"><span class="glyphicon glyphicon-chevron-down"></span></button>
    </div>
    <table class="table table-striped info mt-3">
      <caption>Creation Date {{$diagnose->created_at}}</caption>
      <tr>
        <th>Tooth Name</th>
        <th>Diagnosis</th>
      </tr>
      @foreach ($diagnoseArray as $item)
      @php
        $array=explode(">>>",$item);
      @endphp
      <tr>
      <td>{{$array[0]}}</td>
      <td>{{$array[1]}}</td>
      </tr>
      @endforeach
      <tr>
        <th>Total Paid</th>
        @if($diagnose->already_payed!=null)
        <td>{{$diagnose->already_payed}} <strong>EGP</strong></td>
        @else
        <td>0</td>
        @endif
      </tr>
      <tr>
        <th>Total Price</th>
        @if($diagnose->total_price!=null)
        <td>{{$diagnose->total_price}} <strong>EGP</strong></td>
        @else
        <td>0</td>
        @endif
      </tr>
      <tr>
        <th>State</th>
        @if($diagnose->done!=0)
        <td>Finished</td>
        @else
        <td>In Progress</td>
        @endif
      </tr>
    </table>
    <div class="row">
      <div class="col-12 col-sm-6 col-md-4">
        <div class="card bg-light mb-3">
          <div class="card-header">Prescription</div>
          <div class="card-body">
            @if ($drugs->count()>0)
              @foreach ($drugs as $drug)
                <h5 class="card-title">{{$drug->drug}}: {{$drug->dose}}</h5>
              @endforeach
            @else
              <div class="card-title">There is no drugs are added to Prescription</div>
            @endif
            <a class="btn btn-home action" data-action="#add_drug" data-url="/patient/diagnosis/{{$diagnose->id}}/add/drug">Add Prescription</a>
            <a class="btn btn-home" id="print">Print Prescription <span class="glyphicon glyphicon-print"></span></a>
          </div>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-md-4">

      </div>
      <div class="col-12 col-sm-6 col-md-4">

      </div>
    </div>
  </div>
</div>
<div class="float_form_container">
  <div id="add_drug" class="float_form bg-home">
    <h4 class="center mb-3">Create Prescription</h4>
    <span class="close bg-home">&times;</span>
    <form method="post">
      <div id="new_drug">
      <div class="col-12 center mb-3">Drug 1</div>
      <div class="form-group row drug_input">
        <label class="col-sm-2">Drug</label>
        <div class="col-sm-10 input-group">
          @if ($allDrugs->count()>0)
          <select name="drug_list[]" class="form-control">
            <option value="">select a drug from here, if it exists Or Enter anew in the next box</option>
              @foreach ($allDrugs as $drug)
                <option value="{{$drug->drug}}">{{$drug->drug}}</option>
              @endforeach
          </select>
          @endif
          <input type="text" class="form-control" name="drug[]" placeholder="Enter a new drug">
        </div>
        <label class="col-sm-2 mt-3">Dose</label>
        <div class="col-sm-10 input-group">
          <input type="text" name="dose[]" value="" placeholder="Write down the dose of this drug"  class="mt-3 form-control">
        </div>
      </div>
      </div>
      <div class="center">
        <input style="width: 150px; display: inline-block;" type="submit" class="btn btn-primary" value="create">
        <button style="width: 150px; display: inline-block;" type="button" id="add_new_drug" class="btn btn-secondary">Add new drug</button>
      </div>
      @csrf
    </form>
  </div>
  <div id="add_payment" class="float_form bg-home">
    <span class="close bg-home">&times;</span>
    <form method="post">
      <h4 class="center mb-3">Here you can add a payment to a specific Diagnosis</h4>
      <div class="form-group row">
        <label for="payment" class="col-sm-2">Payment Amount</label>
        <div class="col-sm-10 input-group">
          <input autofocus type="text" name="payment" id="payment" placeholder="Enter Payment" class="form-control">
          <div class="input-group-append">
            <span class="input-group-text" title="Egyptian Pound">EGP</span>
          </div>
        </div>
      </div>
      <input style="width: 150px; display: block; margin:0 auto;" type="submit" class="btn btn-secondary" value="Add Payment">
      @csrf
      @method('PUT')
    </form>
  </div>
  <div id="add_total_price" class="float_form bg-home">
    <span class="close bg-home">&times;</span>
    <form method="post">
      <h4 class="center mb-3">Here you can the Total price of a specific Diagnosis</h4>
      <div class="form-group row">
        <label for="total_price" class="col-sm-2">Total Price</label>
        <div class="col-sm-10 input-group">
          <input autofocus type="text" name="total_price" id="total_price" placeholder="Enter Total Price" class="form-control">
          <div class="input-group-append">
            <span class="input-group-text" title="Egyptian Pound">EGP</span>
          </div>
        </div>
      </div>
      <input style="width: 150px; display: block; margin:0 auto;" type="submit" class="btn btn-secondary" value="Add Payment">
      @csrf
      @method('PUT')
    </form>
  </div>
  <div id="add_visit" class="float_form bg-home">
    <span class="close bg-home">&times;</span>
    <form method="post">
      <h4 class="center mb-3">Here you can add a payment to a specific Diagnosis</h4>
      <div class="form-group row">
        <label for="total_price" class="col-sm-2">Total Price</label>
        <div class="col-sm-10 input-group">
          <input type="text" name="total_price" id="total_price" placeholder="Enter Total Price" class="form-control">
          <div class="input-group-append">
            <span class="input-group-text" title="Egyptian Pound">EGP</span>
          </div>
        </div>
      </div>
      <input style="width: 150px; display: block; margin:0 auto;" type="submit" class="btn btn-secondary" value="Add Payment">
      @csrf
      @method('PUT')
    </form>
  </div>
  <div id="finish" class="float_form bg-home">
    <span class="close bg-home">&times;</span>
    <form method="post">
      <h4 class="center mb-3">Take care, you are going to finish this Diagnosis, this means that there is no more visits related to this diagnosis</h4>
      <div class="form-group row">
        <div class="col-sm-10 input-group">
          <input type="hidden" name="done" id="done" value="1" class="form-control">
        </div>
      </div>
      <div class="center">
        <input style="width: 150px; display: inline-block;" type="submit" class="btn btn-success" value="YES">
        <button style="width: 150px; display: inline-block;" type="button" class="close_button btn btn-secondary">NO</button>
      </div>
      @csrf
      @method('PUT')
    </form>
  </div>
  <div id="delete" class="float_form bg-home">
    <span class="close bg-home">&times;</span>
      <h4 class="center mb-3">Are you sue that you want to delete this diagnosis? This means that you will lose any data related to it from visits, drugs and Dental X-rays!
      <br>Do you still want to proceed</h4>
      <div class="center">
        <a style="width: 150px; display: inline-block;" class="btn btn-danger">YES</a>
        <button style="width: 150px; display: inline-block;" type="button" class="close_button btn btn-secondary">NO</button>
      </div>
  </div>
</div>
@endsection
