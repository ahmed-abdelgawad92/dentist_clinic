@extends("layout.master")
@section("title")
  Patient {{ucwords($patient->pname)}}
@endsection
@section('container')
<div class="card">
  <div class="card-header">
    Patient Information
  </div>
  <div class="card-body">
    @if (session("success")!=null)
    <div class="alert alert-success alert-dismissible fade show">
      <h4 class="alert-heading">Completed Successfully</h4>
      {{session("success")}}
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    @endif
    @if (session("error")!=null)
    <div class="alert alert-danger alert-dismissible fade show">
      <h4 class="alert-heading">Error</h4>
      {{session("error")}}
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
        <form action="" method="post">
          <input type="file" name="photo" id="photo">
        </form>
        <span class="glyphicon glyphicon-picture"></span>
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
            <th>File Number</th>
            <td>{{$patient->id}}</td>
          </tr>
          <tr>
            <th>Gender</th>
            @if ($patient->gender)
              <td>male</td>
            @else
              <td>female</td>
            @endif
          </tr>
          <tr>
            <th>Date of birth</th>
              <td>{{$patient->dob}}</td>
          </tr>
          <tr>
            <th>Address</th>
            <td>{{ucfirst($patient->address)}}</td>
          </tr>
          <tr>
            <th>Diabetes</th>
            @if ($patient->diabetes)
              <td>Yes</td>
            @else
              <td>No</td>
            @endif
          </tr>
          <tr>
            <th>Blood Pressure</th>
            <td>{{ucfirst($patient->blood_pressure)}}</td>
          </tr>
          @if ($patient->medical_compromise)
          <tr>
            <th>Medical Compromise</th>
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
            @if ($numOfUndoneDiagnose>1)
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
            <li class="list-group-item profile-list-item"><a class="profile-link" href="">Last Visit</a></li>
            <li class="list-group-item profile-list-item"><a class="profile-link" href="">Next Visit</a></li>
            <li class="list-group-item profile-list-item"><a class="profile-link" href="">Add Visit to the Current Diagnosis</a></li>
          </ul>
          @else
          <div class=" alert alert-warning" style="margin-bottom:0!important;border-radius:0">There's no Diagnosis</div>
          @endif
        </div>
      </div>
      <div class="col-12 col-sm-6 col-md-4 col-lg-4 mb-3">
        <div class="card">
          <h4 class="card-header">Payments</h4>
          @if (isset($diagnose)&&!empty($diagnose))
          <ul class="list-group list-group-flush">
            <li class="list-group-item profile-list-item"><a class="profile-link" href="">In Current Diagnosis</a></li>
            <li class="list-group-item profile-list-item"><a class="profile-link" href="">Add Payment in current Diagnosis</a></li>
            <li class="list-group-item profile-list-item"><a class="profile-link" href="">In All Diagnosis</a></li>
          </ul>
          @else
          <div class=" alert alert-warning" style="margin-bottom:0!important;border-radius:0">There's no Diagnosis</div>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
