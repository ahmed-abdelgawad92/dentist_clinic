@extends("layout.master")
@section("title")
  Patient {{ucwords($patient->pname)}}
@endsection
@section('container')
<div class="card">
  <div class="card-header">
    {{$card_title}} of <a class="home" href="{{route('profilePatient',['id'=>$patient->id])}}"> "{{$patient->pname}}" </a>
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
      {{session("error")}}
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
          {{$msg}} <br />
        @endforeach
      </div>
    @endif
    <div class="row">
      <div class="col-md-3 col-lg-3 col-sm-6 col-6 offset-3 offset-md-0 offset-lg-0 offset-sm-3">
        <div id="profile-div">
        @if(Storage::disk('local')->exists($patient->photo))
        <a href="{{route('profilePatient',['id'=>$patient->id])}}"><img src="{{url('storage/'.$patient->photo)}}" id="patient_profile_photo" alt="{{$patient->pname}}" class="profile rounded-circle"></a>
        @else
        <a href="{{route('profilePatient',['id'=>$patient->id])}}"><img src="{{asset('unknown.png')}}" id="patient_profile_photo" alt="{{$patient->pname}}" class="profile rounded-circle"></a>
        @endif
        </div>
        <h4 class="center"><a href="{{route('profilePatient',['id'=>$patient->id])}}">{{ucwords($patient->pname)}}</a></h4>
        <h4 class="center" title="Phone No.">{{$patient->phone}}</h4>
        <a href="{{route('addDiagnose',['id'=>$patient->id])}}" class="btn btn-home btn-block mt-3 mb-3">Add New Diagnosis</a>
      </div>
      <div class="col-md-9 col-lg-9 col-sm-12 col-12">
        @php
        $count=1;
        @endphp
        @foreach ($diagnoses as $diagnose)
          <div class="row stripe p-3">
            <div class="col-12 col-sm-9">
            <h3>
              <span class="badge badge-primary btn-home">{{$count++}}</span>
              <a class="home" href="{{route("showDiagnose",['id'=>$diagnose->id])}}">Diagnosis created at {{$diagnose->created_at->toDateString()}}</a>
              @if ($diagnose->done==1)
                <span class="badge badge-success">Finished</span>
              @else
                <span class="badge badge-secondary">In Progress</span>
              @endif
            </h3>
            @php
              $total_price=0;
            @endphp
            @foreach ($diagnose->teeth as $tooth)
            <h4>{{ substr($tooth->teeth_name,0,strpos($tooth->teeth_name,"{"))." ".$tooth->teeth_convert()}}</h4>
            <p>{{$tooth->diagnose_type}}</p>
            <p>{{$tooth->description}}</p>
            @php
              $total_price+=$tooth->price;
            @endphp
            @endforeach
          </div>
          <div class="col-12 col-sm-3">
            @php
              if($diagnose->discount!=0&& $diagnose->discount!=null){
                if($diagnose->discount_type==0){
                  $total_price-=($total_price*($diagnose->discount/100));
                }else{
                  $total_price-=$diagnose->discount;
                }
              }
            @endphp
            @if($diagnose->total_paid<$total_price)
              <div class="calendar-date stamp  reverse float-left">
                <div class="calendar-day stamp-day reverse ">Total Paid</div>
                <div class="calendar-day-nr reverse ">{{$diagnose->total_paid+0}}<br />EGP</div>
              </div>
              <div class="calendar-date stamp  reverse float-left">
                <div class="calendar-day stamp-day reverse ">Total Price</div>
                <div class="calendar-day-nr reverse ">{{$total_price+0}}<br />EGP</div>
              </div>
              <button href="" class="btn btn-home btn-block action" data-action="#add_payment" data-url="/patient/diagnosis/{{$diagnose->id}}/add/payment">Add Payment</button>
            @endif
            @if ($diagnose->done!=1)
              <button href="" class="btn btn-home btn-block action" data-action="#add_visit" data-url="/patient/diagnosis/{{$diagnose->id}}/">Add visit</button>
              <button href="" class="btn btn-success btn-block action" data-action="#finish" data-url="/patient/diagnosis/{{$diagnose->id}}/finish">finish</button>
            @endif
            <button href="" class="btn btn-danger btn-block action" data-action="#delete_diagnosis" data-url="/patient/diagnosis/delete/{{$diagnose->id}}">delete  <span class="glyphicon glyphicon-trash"></span></button>
          </div>
          </div>
        @endforeach
        {{$diagnoses->links()}}
      </div>
    </div>
  </div>
</div>
<div class="float_form_container">
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
  <div id="delete_diagnosis" class="float_form bg-home">
    <span class="close bg-home">&times;</span>
      <h4 class="center mb-3">Are you sure that you want to delete this diagnosis? This means that you will lose any data related to it from visits, drugs and Dental X-rays!
      <br>Do you still want to proceed</h4>
      <div class="center">
        <a style="width: 150px; display: inline-block;" class="btn btn-danger">YES</a>
        <button style="width: 150px; display: inline-block;" type="button" class="close_button btn btn-secondary">NO</button>
      </div>
  </div>
</div>
@endsection
