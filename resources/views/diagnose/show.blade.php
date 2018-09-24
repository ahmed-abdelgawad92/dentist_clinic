@extends('layout.master')
@section('title')
  {{$patient->pname}}'s Diagnosis
@endsection
@section('container')
<div class="card">
  <div class="card-header">
    <h4>
      Display Diagnosis of Patient "<a href="{{route('profilePatient',['id'=>$patient->id])}}">{{$patient->pname}}</a>"
      <a href="{{route('showCasePhotoDiagnosis',['id'=>$diagnose->id])}}" class="float-right"><div class="folder">case gallery</div></a>
    </h4>
  </div>
  <div class="card-body">
    <div class="nav justify-content-center mb-3">
      <div class="btn-group" role="group" aria-label="Basic example">
        @if ($total_price>$diagnose->total_paid)
        <a class="btn btn-home control action" data-action="#add_payment" data-url="/patient/diagnosis/{{$diagnose->id}}/add/payment">Add Payment</a>
        @endif
        <a class="btn btn-home control action" data-action="#add_oral_radiology" data-url="/patient/diagnosis/{{$diagnose->id}}/add/oralradiology">Add Dental X-ray</a>
        <a class="btn btn-home control action" data-action="#add_drug" data-url="/patient/diagnosis/{{$diagnose->id}}/add/medication">Add Prescription</a>
        @if ($diagnose->done==0)
        <a class="btn btn-home control action" data-action="#add_visit" data-url="/patient/diagnosis/visit/add/{{$diagnose->id}}">Add Visit</a>
        <a class="btn btn-home control" href="{{route('addTeeth',['id'=>$diagnose->id])}}">Add Teeth</a>
        @endif
        <a class="btn btn-home control action" data-action="#add_discount" data-url="/patient/diagnosis/{{$diagnose->id}}/add/discount">@if($diagnose->discount!=0) Change @else Add @endif Discount</a>
        <a class="btn btn-home control action" data-action="#add_case_photo" data-url="/patient/diagnosis/{{$diagnose->id}}/add/case_photo">Add Case Photo</a>
        <a href="{{route('updateDiagnose',['id'=>$diagnose->id])}}" class="btn btn-secondary control">Edit <span class="glyphicon glyphicon-edit"></span></a>
        <a class="btn btn-danger control action" data-action="#delete_diagnosis" data-url="/patient/diagnosis/delete/{{$diagnose->id}}">Delete <span class="glyphicon glyphicon-trash"></span></a>
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
      <img src="{{asset('teeth_new.png')}}" alt="" class="diagnose_chart">
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
        <th scope="row">Tooth Name</th>
        <th>Diagnosis Type</th>
        <th>Description</th>
        @if (Auth::user()->role!=0)
        <th>Price</th>
        @endif
      </tr>
      @php
        $total_price=0;
      @endphp
      @foreach ($teeth as $tooth)
      <tr>
        <td scope="row">{{ substr($tooth->teeth_name,0,strpos($tooth->teeth_name,"{"))." ".$tooth->teeth_convert()}}</td>
        <td>{{$tooth->diagnose_type}}</td>
        <td>{{$tooth->description}}</td>
        @if (Auth::user()->role!=0)
        <td>{{number_format($tooth->price,2)}} EGP</td>
        @php
          $total_price+=$tooth->price;
        @endphp
        @endif
      </tr>
      @endforeach
      @if (Auth::user()->role!=0)
      <tr>
        <th colspan="3" scope="row">Total Price</th>
        <td>{{number_format($total_price,2)}} <strong>EGP</strong></td>
      </tr>
      @if ($diagnose->discount!=null && $diagnose->discount!=0)
      <tr>
        <th colspan="3" scope="row">Discount</th>
        <td>-{{$diagnose->discount}} @if($diagnose->discount_type)<strong>EGP</strong>@else<strong>%</strong>@endif</td>
      </tr>
      <tr>
        <th colspan="3" scope="row">Total after Discount</th>
        <td>@if($diagnose->discount_type){{$total_price-=$diagnose->discount}}@else{{$total_price-=(($diagnose->discount/100)*$total_price)}}@endif <strong>EGP</strong></td>
      </tr>
      @endif
      <tr>
        <th colspan="3" scope="row">Total Paid</th>
        @if($diagnose->total_paid!=null)
        <td>{{number_format($diagnose->total_paid,2)}} <strong>EGP</strong></td>
        @else
        <td>0</td>
        @endif
      </tr>
      <tr>
        <th colspan="3" scope="row">Amount Outstanding</th>
        <td>{{number_format($total_price-$diagnose->total_paid,2)}} <strong>EGP</strong></td>
      </tr>
      @endif
      <tr>
        <th @if (Auth::user()->role!=0) colspan="3" @else colspan="2" @endif scope="row">State</th>
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
          <div class="card-header">Visits</div>
          <div class="card-body">
            @if ($appointments->count()>0)
            <table class="table table-striped">
              <tr>
                <th>date</th>
                <th>time</th>
                <th>state</th>
              </tr>
            @foreach ($appointments as $visit)
              <tr>
                <td>{{date('d-m-Y',strtotime($visit->date))}}</td>
                <td>{{date('h:i a',strtotime($visit->time))}}</td>
                @if ($visit->approved==3)
                <td style="white-space:nowrap;"><div class="btn btn-home">in waiting room <span class="glyphicon glyphicon-time"></span></div></td>
                @elseif ($visit->approved==2)
                <td style="white-space:nowrap;"><div class="btn btn-secondary">not approved <span class="glyphicon glyphicon-remove-sign"></span></div></td>
                @else
                <td style="white-space:nowrap;"><div class="btn btn-success">finished visit <span class="glyphicon glyphicon-ok-circle"></span></div></td>
                @endif
              </tr>
            @endforeach
            </table>
            @else
            <div class="alert alert-warning"><span class="glyphicon glyphicon-exclamation-sign"></span> There's no visits reserved for this diagnosis</div>
            @endif
            <a class="btn btn-home " href="{{route('showAllDiagnoseAppointments',['id'=>$diagnose->id])}}">All Visits</a>
            @if ($diagnose->done==0)
            <a class="btn btn-home action" data-action="#add_visit" data-url="/patient/diagnosis/visit/add/{{$diagnose->id}}">Add Visit</a>
            @endif
          </div>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-md-4">
        <div class="card bg-light mb-3">
          <div class="card-header">Prescription</div>
          <div class="card-body">
            @if ($drugs->count()>0)
              @foreach ($drugs as $drug)
                <h5 class="card-title">{{$drug->name}}: {{$drug->pivot->dose}}</h5>
              @endforeach
              <a class="btn btn-home action" data-action="#add_drug" data-url="/patient/diagnosis/{{$diagnose->id}}/add/medication">Add Prescription</a>
              <a href="{{route('showAllDrugs',['id'=>$diagnose->id])}}" class="btn btn-home">Print Prescription <span class="glyphicon glyphicon-print"></span></a>
            @else
              <div class="alert alert-warning"><span class="glyphicon glyphicon-exclamation-sign"></span> There is no drugs are added to Prescription</div>
              <a class="btn btn-home action" data-action="#add_drug" data-url="/patient/diagnosis/{{$diagnose->id}}/add/medication">Add Prescription</a>
            @endif
          </div>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-md-4">
        <div class="card bg-light mb-3">
          <div class="card-header">Dental X-rays</div>
          <div class="card-body">
            @if ($oral_radiologies->count()>0)
              <div class="row mb-3">
              @foreach ($oral_radiologies as $xray)
                  <img src="{{Storage::url($xray->photo)}}" alt="{{$xray->description}}" data-update="{{route('updateOralRadiology',['id'=>$xray->id])}}" data-delete="{{route('deleteOralRadiology',['id'=>$xray->id])}}" data-id="{{$xray->id}}" class="rounded xray">
              @endforeach
              </div>
              <a class="btn btn-home" href="{{route("showAllOralRadiologies",['id'=>$diagnose->id])}}">show all X-rays of this Diagnosis</a>
            @else
              <div class="alert alert-warning"><span class="glyphicon glyphicon-exclamation-sign"></span> There is no Dental X-ray</div>
            @endif
            <a class="btn btn-home action" data-action="#add_oral_radiology" data-url="/patient/diagnosis/{{$diagnose->id}}/add/oralradiology">Add Dental X-ray</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="float_form_container">
  <div id="add_drug" class="float_form bg-home">
    <h4 class="center mb-3">Create Prescription</h4>
    <span class="close" style="color:whitesmoke;">&times;</span>
    <form id="add_drug_form" method="post">
      @php
        $old=session()->getOldInput();
      @endphp
      @if (count($old)!=null)
        @for ($i=0;$i<count($old['drug']);$i++)
        <div id="new_drug">
          <div class="col-12 center mb-3">Medicine {{$i+1}}</div>
          <div class="form-group row drug_input">
            <label class="col-sm-2">Drug</label>
            <div class="col-sm-10 ">
              <div class="input-group">
                @if ($allDrugs->count()>0)
                  <select name="drug_list[]" class="custom-select @if ($errors->has('drug.'.$i)|| $errors->has('drug_list.'.$i)) is-invalid @endif">
                    <option value="">select a drug from here, if it exists Or Enter a new in the next box</option>
                    @foreach ($allDrugs as $drug)
                      @if ($drug->id==old('drug_list.'.$i))
                        <option value="{{$drug->id}}" selected>{{$drug->name}}</option>
                      @else
                        <option value="{{$drug->id}}">{{$drug->name}}</option>
                      @endif
                    @endforeach
                  </select>
                @endif
                <input type="text" class="form-control @if ($errors->has('drug.'.$i)|| $errors->has('drug_list.'.$i)) is-invalid @endif" value="{{old('drug.'.$i)}}" name="drug[]" placeholder="Enter a new drug">
                @if ($errors->has('drug.'.$i)||$errors->has('drug_list.'.$i))
                  @foreach ($errors->get("drug.".$i) as $msg)
                    <div style='display:block' class='invalid-feedback'>{{$msg}}</div>
                  @endforeach
                @endif
              </div>
            </div>
            <label class="col-sm-2 mt-3">Dose</label>
            <div class="col-sm-10 input-group">
              <input type="text" name="dose[]" value="{{old('dose.'.$i)}}" placeholder="Write down the dose of this drug"  class="mt-3 form-control @if ($errors->has('dose.'.$i)) is-invalid @endif ">
              @if ($errors->has('dose.'.$i))
                @foreach ($errors->get("dose.".$i) as $msg)
                  <div style='display:block' class='invalid-feedback'>{{$msg}}</div>
                @endforeach
              @endif
            </div>
          </div>
        </div>
        @endfor
      @else
      <div id="new_drug">
        <div class="col-12 center mb-3">Medicine 1</div>
        <div class="form-group row drug_input">
          <label class="col-sm-2">Drug</label>
          <div class="col-sm-10 ">
            <div class="input-group">
              @if ($allDrugs->count()>0)
                <select name="drug_list[]" class="custom-select">
                  <option value="">select a drug from here, if it exists Or Enter a new in the next box</option>
                  @foreach ($allDrugs as $drug)
                    <option value="{{$drug->id}}">{{$drug->name}}</option>
                  @endforeach
                </select>
              @endif
              <input type="text" class="form-control" name="drug[]" placeholder="Enter a new drug">
            </div>
          </div>
          <label class="col-sm-2 mt-3">Dose</label>
          <div class="col-sm-10 input-group">
            <input type="text" name="dose[]" value="" placeholder="Write down the dose of this drug"  class="mt-3 form-control">
          </div>
        </div>
      </div>
      @endif
      <div class="center">
        <input style="width: 150px; display: inline-block;" type="submit" class="btn btn-primary" value="create">
        <button style="width: 150px; display: inline-block;" type="button" id="add_new_drug" class="btn btn-secondary">Add new drug</button>
      </div>
      @csrf
    </form>
  </div>
  <div id="add_payment" class="float_form bg-home">
    <span class="close" style="color:whitesmoke;">&times;</span>
    <form id="add_payment_form" method="post">
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
  <div id="add_discount" class="float_form bg-home">
    <span class="close" style="color:whitesmoke;">&times;</span>
    <form id="add_discount_form" method="post">
      <h4 class="center mb-3">Here you can discount on a specific Diagnosis</h4>
      <div class="form-group row">
        <label for="discount" class="col-sm-2">Discount</label>
        <div class="col-sm-10 input-group">
          <input autofocus type="text" name="discount" id="discount" placeholder="Enter Discount" class="form-control">
          <div class="input-group-append">
            <select name="discount_type" id="discount_type" class="custom-select discount">
              <option value="">select discount type</option>
              <option value="0">%</option>
              <option value="1">EGP</option>
            </select>
          </div>
        </div>
      </div>
      <input style="width: 150px; display: block; margin:0 auto;" type="submit" class="btn btn-secondary" value="Add Discount">
      @csrf
      @method('PUT')
    </form>
  </div>
  <div id="add_visit" class="float_form bg-home">
    <span class="close" style="color:whitesmoke;">&times;</span>
    <form method="post" id="add_visit_form">
      <h4 class="center mb-3">Here you can add a new visit to a specific Diagnosis</h4>
      <div class="form-group row">
        <label for="visit_treatment" class="col-sm-2">Treatment</label>
        <div class="col-sm-10">
          <textarea name="visit_treatment" id="visit_treatment" placeholder="Write down the Treatment" class="form-control"></textarea>
        </div>
      </div>
      <div class="form-group row">
        <label for="visit_date" class="col-sm-2">Date</label>
        <div class="col-sm-10">
          <input type="date" name="visit_date" id="visit_date" placeholder="Select Date" class="form-control">
        </div>
      </div>
      <div id="loading" class="text-center" style="display:none">
        <img width="50px" height="50px" src="{{asset('load.gif')}}" alt="">
      </div>
      <div class="form-group row">
        <label class="col-sm-2">Time</label>
        <div class="col-sm-10">
          <select name="visit_time" id="visit_time" class="custom-select">
            <option value="">select the desired date first</option>
          </select>
        </div>
      </div>
      <input style="width: 150px; display: block; margin:0 auto;" type="submit" class="btn btn-secondary" value="Add Visit">
      @csrf
    </form>
  </div>
  <div id="finish" class="float_form bg-home">
    <span class="close" style="color:whitesmoke;">&times;</span>
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
  <div id="delete_diagnosis" class="float_form bg-home">
    <span class="close" style="color:whitesmoke;">&times;</span>
      <h4 class="center mb-3">Are you sure that you want to delete this diagnosis? This means that you will lose any data related to it from visits, drugs and Dental X-rays!
      <br>Do you still want to proceed</h4>
      <div class="center">
        <a style="width: 150px; display: inline-block;" class="btn btn-danger">YES</a>
        <button style="width: 150px; display: inline-block;" type="button" class="close_button btn btn-secondary">NO</button>
      </div>
  </div>
  <div id="add_oral_radiology" class="float_form bg-home">
    <span class="close" style="color:whitesmoke;">&times;</span>
    <form id="add_oral_radiology_form" method="post" enctype="multipart/form-data">
      <h4 class="center mb-3">Upload a Dental X-ray</h4>
      <div class="form-group row">
        <label for="xray" class="col-sm-2">Upload Dental X-ray</label>
        <div class="col-sm-10">
          <div class="custom-file">
            <input style="cursor:pointer;" type="file" class="custom-file-input" id="xray" name="xray">
            <label class="custom-file-label" for="photo">Choose file</label>
          </div>
        </div>
      </div>
      <div class="form-group row">
        <label for="xray_description" class="col-sm-2">Description</label>
        <div class="col-sm-10">
          <textarea class="form-control" name="xray_description" placeholder="Write down the X-ray description" id="xray_description"></textarea>
        </div>
      </div>
      <input style="width: 150px; display: block; margin:0 auto;" type="submit" class="btn btn-secondary" value="Upload Dental X-ray">
      @csrf
    </form>
  </div>
  <div id="add_case_photo" class="float_form bg-home">
    <span class="close" style="color:whitesmoke;">&times;</span>
    <form id="add_case_photo_form" method="post" enctype="multipart/form-data">
      <h4 class="center mb-3">Upload Case Photo</h4>
      <div class="form-group row">
        <label for="case_photo" class="col-sm-2">Upload Case Photo</label>
        <div class="col-sm-10">
          <div class="custom-file">
            <input style="cursor:pointer;" type="file" class="custom-file-input" id="case_photo" name="case_photo">
            <label class="custom-file-label" for="case_photo">Choose file</label>
          </div>
        </div>
      </div>
      <div class="form-group row">
        <label for="before_after" class="col-sm-2">(Before/After) Treatment</label>
        <div class="col-sm-10">
          <select class="custom-select" name="before_after" id="before_after">
            <option value="">select whether before or after treatment</option>
            <option value="0">Before</option>
            <option value="1">After</option>
          </select>
        </div>
      </div>
      <input style="width: 150px; display: block; margin:0 auto;" type="submit" class="btn btn-secondary" value="Upload Now">
      @csrf
    </form>
  </div>
  <div class="pos">
    <div id="xray_gallery" class="float_form bg-home">
      <span class="close" style="color:whitesmoke;">&times;</span>
      <img src="" alt="" class="rounded">
      <div class="btn-group justify-content-center mb-3" style="width:100%">
          <button id="prev_img" class="btn btn-secondary">previous</button>
          <button id="next_img" class="btn btn-home">next</button>
      </div>
      <div id="img_desc">
      </div>
    </div>
    <a href="" id="delete_xray_gallery" class="btn btn-danger float_link_left">Delete <span class="glyphicon glyphicon-trash"></span></a>
    <div id="delete_xray" class="float_form bg-home">
      <span class="close" style="color:whitesmoke;">&times;</span>
        <h4 class="center mb-3">Are you sure that you want to delete this X-ray?</h4>
        <div class="center">
          <a style="width: 150px; display: inline-block;" class="btn btn-danger">YES</a>
          <button style="width: 150px; display: inline-block;" type="button" class="close_button btn btn-secondary">NO</button>
        </div>
    </div>
    <a href="" id="edit_xray_gallery" class="btn btn-secondary float_link_right">Edit <span class="glyphicon glyphicon-edit"></span></a>
  </div>
</div>
@endsection
