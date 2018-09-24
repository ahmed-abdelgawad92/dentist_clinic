@extends("layout.master")
@section("title")
  Patient {{ucwords($patient->pname)}}
@endsection
@section('container')
<div class="card">
  <div class="card-header">
    <h4>
      Patient Information
      <a href="{{route('showCasePhotoPatient',['id'=>$patient->id])}}" class="float-right"><div class="folder">case gallery</div></a>
    </h4>
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
    <div class="row">
      <div class="col-md-4 col-lg-3 col-sm-5 col-6 offset-3 offset-md-0 offset-lg-0 offset-sm-0">
        <div id="profile-div">
        @if(Storage::disk('local')->exists($patient->photo))
        <img src="{{Storage::url($patient->photo)}}" id="patient_profile_photo" alt="{{$patient->pname}}" class="profile rounded-circle">
        @else
        <img src="{{asset('unknown.png')}}" id="patient_profile_photo" alt="{{$patient->pname}}" class="profile rounded-circle">
        @endif
        <form id="change_profile_pic_form" action="{{route('uploadPatientPhoto',['id'=>$patient->id])}}" method="post" enctype="multipart/form-data">
          <input type="file" name="photo" id="photo">
          @method('PUT')
          @csrf
        </form>
        <span class="glyphicon glyphicon-picture"></span>
        </div>
        <div class="center" id="change_profile_pic">
          <label></label><button class="btn btn-home ml-3" id="upload_new_photo">upload photo</button>
        </div>
        <h4 class="center">{{ucwords($patient->pname)}}</h4>
        <h4 class="center" title="Phone No.">{{$patient->phone}}</h4>
      </div>
      <div class="col-md-8 col-lg-9 col-sm-7 col-12">
        <div class="controls">
          <h4>Details</h4>
          <div class="btn-group">
            <a href="{{route('deletePatient',['id'=>"$patient->id"])}}" id="delete" class="btn btn-danger">delete <span class="glyphicon glyphicon-trash"></span></a>
            <div id="delete-modal" class="modal fade" tabindex="-1" role="dialog">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title">Delete</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <p>Are you sure that you want to delete the patient "{{$patient->pname}}"?</p>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">no</button>
                    <a href="{{route('deletePatient',['id'=>"$patient->id"])}}" class="btn btn-danger">yes</a>
                  </div>
                </div>
              </div>
            </div>
            <a href="{{route('updatePatient',['id'=>"$patient->id"])}}" class="btn btn-secondary">edit <span class="glyphicon glyphicon-edit"></span></a>
          </div>
        </div>
        <table class="table table-striped info">
          <tr>
            <th style="white-space:nowrap;">File Number</th>
            <td><svg style="margin: 0 auto;" id="barcode"
                jsbarcode-value="{{$patient->id}}"
                jsbarcode-textmargin="0"
                jsbarcode-width="2"
                jsbarcode-height="40px"
                jsbarcode-marginright="10"
                jsbarcode-background="rgb(243,243,243)"
                jsbarcode-fontoptions="bold"></svg> <button class="btn btn-home" id="print_barcode">Print Barcode</button></td>
          </tr>
          <tr>
            <th style="white-space:nowrap;">Gender</th>
            @if ($patient->gender)
              <td>male</td>
            @else
              <td>female</td>
            @endif
          </tr>
          <tr>
            <th style="white-space:nowrap;">Age</th>
              <td>{{round((time()-strtotime($patient->dob))/(3600*24*365.25))}}</td>
          </tr>
          <tr>
            <th style="white-space:nowrap;">Address</th>
            <td>{{ucfirst($patient->address)}}</td>
          </tr>
          <tr>
            <th style="white-space:nowrap;">Diabetes</th>
            @if ($patient->diabetes)
              <td>Yes</td>
            @else
              <td>No</td>
            @endif
          </tr>
          <tr>
            <th style="white-space:nowrap;">Blood Pressure</th>
            <td>{{ucfirst($patient->blood_pressure)}}</td>
          </tr>
          @if ($patient->medical_compromise)
          <tr>
            <th style="white-space:nowrap;">Medical Compromise</th>
              <td>{{$patient->medical_compromise}}</td>
          </tr>
          @endif
        </table>
      </div>
    </div>
    <div class="row mt-3">
      <div class="col-12 col-sm-6 col-md-4 col-lg-4 mb-3">
        <div class="card">
          <h4 class="card-header">{{$patient->pname}}'s Diagnosis</h4>
          <ul class="list-group list-group-flush">
            @if (isset($diagnose)&&!empty($diagnose))
            <li class="list-group-item profile-list-item"><a class="profile-link" href="{{route('showDiagnose',["id"=>$diagnose->id])}}">Current Diagnosis</a></li>
            @endif
            @if ($numOfUndoneDiagnose>0)
            <li class="list-group-item profile-list-item"><a class="profile-link" href="{{route('allUnDiagnosesPatient',["id"=>$patient->id])}}">Undone Diagnosis <span class="badge badge-secondary">{{$numOfUndoneDiagnose}}</span></a></li>
            @endif
            @if ($numOfDiagnose>0)
            <li class="list-group-item profile-list-item"><a class="profile-link" href="{{route('allDiagnosesPatient',["id"=>$patient->id])}}">Patient Diagnosis History <span class="badge badge-secondary">{{$numOfDiagnose}}</span></a></li>
            @endif
            <li class="list-group-item profile-list-item"><a class="profile-link" href="{{route('addDiagnose',['id'=>$patient->id])}}">Create a new Diagnosis</a></li>
          </ul>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-md-4 col-lg-4 mb-3">
        <div class="card">
          <h4 class="card-header">Visits</h4>
          @if (isset($diagnose)&&!empty($diagnose))
          <ul class="list-group list-group-flush">
            @if(isset($lastVisit)&&!empty($lastVisit))
            <li class="list-group-item profile-list-item"><a class="profile-link display_visit" data-treatment="{{$lastVisit->treatment}}" data-time="{{date('h:i a',strtotime($lastVisit->time))}}" data-day-nr="{{date('j',strtotime($lastVisit->date))}}" data-day="{{date('D',strtotime($lastVisit->date))}}" data-date="{{date('l jS \of F Y',strtotime($lastVisit->date))}}" href="">Last Visit</a></li>
            @endif
            @if(isset($nextVisit)&&!empty($nextVisit))
            <li class="list-group-item profile-list-item"><a class="profile-link display_visit" data-treatment="{{$nextVisit->treatment}}" data-time="{{date('h:i a',strtotime($nextVisit->time))}}" data-day-nr="{{date('j',strtotime($nextVisit->date))}}" data-day="{{date('D',strtotime($nextVisit->date))}}" data-date="{{date('l jS \of F Y',strtotime($nextVisit->date))}}" href="">Next Visit</a></li>
            @endif
            <li class="list-group-item profile-list-item"><a class="profile-link" href="{{route('addAppointment',['id'=>$diagnose->id])}}">Add Visit to the Current Diagnosis</a></li>
            <li class="list-group-item profile-list-item"><a class="profile-link" href="{{route('showAllDiagnoseAppointments',['id'=>$diagnose->id])}}">All Visits within Current Diagnosis</a></li>
            <li class="list-group-item profile-list-item"><a class="profile-link" href="{{route('showAllPatientAppointments',['id'=>$patient->id])}}">All Visits of this patient</a></li>
          </ul>
          @else
          <div class=" alert alert-warning" style="margin-bottom:0!important;border-radius:0">There's no Diagnosis</div>
          @endif
        </div>
      </div>
      @if (Auth::user()->role!=0)
      <div class="col-12 col-sm-6 col-md-4 col-lg-4 mb-3">
        <div class="card">
          <h4 class="card-header">Payments</h4>
          @if ($numOfDiagnose>0)
          <ul class="list-group list-group-flush">
            @if (isset($diagnose)&&!empty($diagnose)&&$total_price>$diagnose->total_paid)
            <li class="list-group-item profile-list-item">
              <a href="" class="profile-link action" data-action="#add_payment" data-url="/patient/diagnosis/{{$diagnose->id}}/add/payment">Add Payment</a>
            </li>
            @endif
            <li class="list-group-item profile-list-item"><a class="profile-link" href="{{route('allPaymentPatient',['id'=>$patient->id])}}">All Payments in all Diagnosis in details</a></li>
          </ul>
          @if (isset($diagnose)&&!empty($diagnose))
          <div class="payment_list">
            In Current Diagnosis
            <div class="calendar-date stamp float-right">
              <div class="calendar-day stamp-day">Total Price</div>
              <div class="calendar-day-nr">{{$total_price+0}}<br />EGP</div>
            </div>
            <div class="calendar-date stamp float-right">
              <div class="calendar-day stamp-day">Total Paid</div>
              <div class="calendar-day-nr">{{$total_paid+0}}<br />EGP</div>
            </div>
          </div>
          @endif
          <div class="payment_list">
            In All Diagnosis
            <div class="calendar-date stamp float-right">
              <div class="calendar-day stamp-day">Total Price</div>
              <div class="calendar-day-nr">{{$total_priceAllDiagnoses+0}}<br />EGP</div>
            </div>
            <div class="calendar-date stamp float-right">
              <div class="calendar-day stamp-day">Total Paid</div>
              <div class="calendar-day-nr">{{$total_paidAllDiagnoses}}<br />EGP</div>
            </div>
          </div>
          @else
          <div class=" alert alert-warning" style="margin-bottom:0!important;border-radius:0">There's no Diagnosis</div>
          @endif
        </div>
      </div>
      @endif
    </div>
  </div>
</div>
<div class="print-div">
  <svg style="margin: 0 auto;" id="barcode"
      jsbarcode-value="{{$patient->id}}"
      jsbarcode-textmargin="0"
      jsbarcode-width="6"
      jsbarcode-height="140px"
      jsbarcode-marginright="150"
      jsbarcode-fontoptions="bold"></svg> <button class="btn btn-home" id="print_barcode">Print Barcode</button>
</div>
<div class="float_form_container">
  <div id="show_visit" class="float_form bg-home">
    <span class="close" style="color:whitesmoke;">&times;</span>
    <h4 class="center">Visit Details</h4>
    <div class="data-visit"></div>
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
</div>
@endsection
