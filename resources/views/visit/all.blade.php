@extends('layout.master')
@section('title')
All Visits @if ($date==date('Y-m-d')) at Today @elseif($date==date('Y-m-d',strtotime('+1 day'))) at Tomorrow @elseif($date==date('Y-m-d',strtotime('-1 day'))) at Yesterday @elseif(strtotime($date)!==false) {{date('d-m-Y',strtotime($date))}} @else {{$date->pname}} @endif
@endsection
@section('container')
<div class="card">
  <div class="card-header">
    <h4>All Visits
    @if ($date==date('Y-m-d'))
      at Today <div class="calendar-date float-right reverse"><div class="calendar-day">{{date('D',strtotime($date))}}</div><div class="calendar-day-nr">{{date('d',strtotime($date))}}</div></div>
    @elseif($date==date('Y-m-d',strtotime('+1 day')))
      at Tomorrow <div class="calendar-date float-right reverse"><div class="calendar-day">{{date('D',strtotime($date))}}</div><div class="calendar-day-nr">{{date('d',strtotime($date))}}</div></div>
    @elseif($date==date('Y-m-d',strtotime('-1 day')))
      at Yesterday <div class="calendar-date float-right reverse"><div class="calendar-day">{{date('D',strtotime($date))}}</div><div class="calendar-day-nr">{{date('d',strtotime($date))}}</div></div>
    @elseif(strtotime($date)!==false)
      at {{date('l jS \of F Y',strtotime($date))}} <div class="calendar-date float-right reverse"><div class="calendar-day">{{date('D',strtotime($date))}}</div><div class="calendar-day-nr">{{date('d',strtotime($date))}}</div></div>
    @else
      <a class="float-right" href="{{route('profilePatient',['id'=>$date->id])}}">{{$date->pname}}</a>
    @endif
    </h4>
  </div>
  <div class="card-body">
    @if (strtotime($date)!==false)
    <form action="" id="search_visit_form" method="get" style="position: relative" class="mb-3">
      <input type="text" autocomplete="off"  name="search_visit" id="search_visit" placeholder="search visits with date" class="form-control" value="">
      <button class="search" type="submit">
        <span class="glyphicon glyphicon-search"></span>
      </button>
      @csrf
    </form>
    @endif
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
    @if ($visits->count()>0)
    @php
      $counter=1;
    @endphp
    <table class="table table-striped">
      <tr>
        <th id="stateVisit" data-state="{{$stateVisit->value}}" @if(strtotime($date)!==false)data-date="{{$date}}" @endif>#</th>
        <th>Patient</th>
        <th>Diagnosis</th>
        <th>Treatment</th>
        @if (strtotime($date)===false)
        <th>Date</th>
        @endif
        <th>Time</th>
        <th>State Time</th>
        <th>State</th>
        @if (strtotime($date)!==false)
        <th>Action</th>
        @endif
      </tr>
      @foreach ($visits as $visit)
      <tr>
        <td>{{$counter++}}</td>
        <td style="white-space:nowrap;"><a href="{{route('profilePatient',['id'=>$visit->patient()->id])}}">{{$visit->patient()->pname}}</a></td>
        <td style="white-space:nowrap;"><a href="{{route('showDiagnose',['id'=>$visit->diagnose_id])}}">Diagnosis Nr. {{$visit->diagnose_id}}</a></td>
        <td>{{$visit->treatment}}</td>
        @if (strtotime($date)===false)
        <td style="white-space:nowrap;">{{date('d-m-Y',strtotime($visit->date))}}</td>
        @endif
        <td style="white-space:nowrap;">{{date("h:i a",strtotime($visit->time))}}</td>
        @if ($visit->approved_time!=null)
        <td style="white-space:nowrap;">{{date("h:i a",strtotime($visit->approved_time))}}</td>
        @else
        <td style="white-space:nowrap;">not yet</td>
        @endif
        @if ($visit->approved==3)
        <td style="white-space:nowrap;"><div class="btn btn-home">in waiting room <span class="glyphicon glyphicon-time"></span></div></td>
        @elseif ($visit->approved==1)
        <td style="white-space:nowrap;"><div class="btn btn-success">finished visit <span class="glyphicon glyphicon-ok-circle"></span></div></td>
        @elseif($visit->approved==0)
        <td style="white-space:nowrap;"><div class="btn btn-danger">cancelled <span class="glyphicon glyphicon-remove-sign"></span></div></td>
        @else
        <td style="white-space:nowrap;"><div class="btn btn-secondary">not approved <span class="glyphicon glyphicon-remove-sign"></span></div></td>
        @endif
        @if (strtotime($date)!==false)
        <td style="white-space:nowrap;">
          @if ($visit->approved==2)
          <a href="{{route('approveAppointment',['id'=>$visit->id])}}" class="btn btn-home">approve</a>
          <a href="{{route('cancelAppointment',['id'=>$visit->id])}}" class="btn btn-danger">cancel</a>
          @elseif($visit->approved==3)
          <a href="{{route('endAppointment',['id'=>$visit->id])}}" class="btn btn-success">end visit</a>
          @elseif($visit->approved==0)
          <a href="" class="btn btn-danger action" data-action="#delete_visit" data-url="/patient/diagnosis/visit/delete/{{$visit->id}}">delete</a>
          @endif
          <a href="{{route('updateAppointment',['id'=>$visit->id])}}" class="btn btn-secondary">edit</a>
        </td>
        @endif
      </tr>
      @endforeach
    </table>
    @else
    <div class="alert alert-warning">
      <span class="glyphicon glyphicon-exclamation-sign"></span> There is no visits reserved @if ($date==date('Y-m-d')) at Today @elseif($date==date('Y-m-d',strtotime('+1 day'))) at Tomorrow @elseif($date==date('Y-m-d',strtotime('-1 day'))) at Yesterday @elseif(strtotime($date)!==false) at {{date('d-m-Y',strtotime($date))}} @else <a class="float-right" href="{{route('profilePatient',['id'=>$date->id])}}"{{$date->pname}}></a> @endif
    </div>
    @endif
  </div>
</div>
<div class="float_form_container">
  <div id="delete_visit" class="float_form bg-home">
    <span class="close" style="color:whitesmoke;">&times;</span>
      <h4 class="center mb-3">Are you sure that you want to delete this visit?</h4>
      <div class="center">
        <a style="width: 150px; display: inline-block;" class="btn btn-danger">YES</a>
        <button style="width: 150px; display: inline-block;" type="button" class="close_button btn btn-secondary">NO</button>
      </div>
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
<script>
  $(document).ready(function() {
    var state=$('#stateVisit').attr('data-state');
    var date=$('#stateVisit').attr('data-date');
    function checkState() {
      $.ajax({
        url: '/patient/diagnosis/visit/check/state',
        type: 'GET',
        dataType: 'JSON',
        success: function(response){
          if(response.state=='OK'){
            if (date==response.date) {
              if (response.stateVisit!=state) {
                window.location.reload(true);
              }
            }
          }
        }
      });
    }
    if(validateDate(date)){
      setInterval(checkState,3000);
    }

  });
</script>
@endsection
