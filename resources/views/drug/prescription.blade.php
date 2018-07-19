@extends('layout.master')
@section("title","Prescription")
@section("container")
<div class="print-head">
  <h3>Prescription of <a href="{{route("profilePatient",['id'=>$diagnose->patient->id])}}">{{$diagnose->patient->pname}}</a></h3>
  <h4>This Prescription is added to this <a href="{{route('showDiagnose',$diagnose->id)}}">Diagnosis</a> check it to be sure </h4>
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
<div id="divPrint" class="print print-container">
  <header class="print row ">
    <div class="col-4 center">
      <h5>Gad Dental Clinics</h5>
      <h3>Dr. Mostafa Adel Gad</h3>
      <h5>Oral and Dental specialist</h5>
    </div>
    <div class="col-4 center">
      <img src="{{asset('presc.PNG')}}" class="prescription" alt="">
    </div>
    <div class="col-4 center">
      <h5>عيادات جاد للأسنان</h5>
      <h3>د/ مصطفى عادل جاد</h3>
      <h5>أخصائى طب و جراحة الفم و الأسنان</h5>
    </div>
  </header>
  <main class="print mt-3">
    <div>
      <svg id="barcode"
          jsbarcode-value="{{$diagnose->patient->id}}"
          jsbarcode-textmargin="0"
          jsbarcode-width="3"
          jsbarcode-height="100px"
          jsbarcode-marginright="10"
          jsbarcode-fontoptions="bold"></svg>
      <table class="table">
        @foreach ($drugs as $drug)
        <tr  class="drug_{{$drug->pivot->id}}">
        <td>{{$drug->name}}</td>
        <td>{{$drug->pivot->dose}}</td>
        <td>
          <button class="btn btn-danger delete_drug" id="{{$drug->pivot->id}}">Remove it just during print</button>
        </td>
        <td>
          <a style="color:white" data-url="{{route('deleteDrug',['id'=>$drug->pivot->id])}}" data-action="#delete_diagnosis" class="btn btn-danger action drug_{{$drug->pivot->id}}">Remove it permanently of Diagnosis</a>
        </td>
        <td>
          <a href="{{route('updateDrug',['id'=>$drug->pivot->id])}}" class="btn btn-secondary drug_{{$drug->pivot->id}}">Edit <span class="glyphicon glyphicon-edit"></span></a>
        </td>
        </tr>
        @endforeach
      </table>
    </div>
  </main>
  <footer class="print">
    <h3 dir="rtl"><span class="glyphicon glyphicon-earphone"></span>01069684600 للحجز : 01095051565</h3>
    <h5>نجع حمادى - بجوار بنك القاهرة - أمام مطعم الزعيم - الدور الرابع</h5>
    <h5>المواعيد : من السبت الى الخميس من 12 الى 3 صباحا ومن 6 الى 10 مساءا ماعداالجمعة</h5>
  </footer>
</div>
<div class="center mt-3">
  <a id="print" class="btn btn-home">Print Prescription <span class="glyphicon glyphicon-print"></span></a>
  <a id="show_prescription_form" class="btn btn-secondary">Add drug temporarily to prescription</a>
</div>
<div class="float_form_container">
  <div id="add_drug" class="float_form bg-home">
    <span class="close bg-home">&times;</span>
    <h4 class="center">Add a drug</h4>
    <form id="add_drug_to_print">
      <div class="form-group row drug_input">
        <label class="col-sm-2">Drug</label>
        <div class="col-sm-10 input-group">
          <input type="text" class="form-control" name="drug" id="drug" autofocus placeholder="Enter a drug">
        </div>
        <label class="col-sm-2 mt-3">Dose</label>
        <div class="col-sm-10 input-group">
          <input type="text" name="dose" id="dose" value="" placeholder="Write down the dose of this drug"  class="mt-3 form-control">
        </div>
      </div>
      <div class="center">
        <button style="width: 150px; display: inline-block;" type="button" id="add_drug_to_prescription" class="btn btn-secondary">Add a drug</button>
      </div>
    </form>
  </div>
  <div id="delete_diagnosis" class="float_form bg-home">
    <span class="close bg-home">&times;</span>
      <h4 class="center mb-3">Are you sure that you want to delete this drug? You will delete it permanently from this Diagnosis</h4>
      <div class="center">
        <a style="width: 150px; display: inline-block;" class="btn btn-danger">YES</a>
        <button style="width: 150px; display: inline-block;" type="button" class="close_button btn btn-secondary">NO</button>
      </div>
  </div>
</div>
@endsection
