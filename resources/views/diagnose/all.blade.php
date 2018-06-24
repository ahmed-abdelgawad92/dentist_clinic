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
      <div class="col-md-3 col-lg-3 col-sm-6 col-6 offset-3 offset-md-0 offset-lg-0 offset-sm-3">
        <div id="profile-div">
        @if(Storage::disk('local')->exists($patient->photo))
        <img src="{{url('storage/'.$patient->photo)}}" id="patient_profile_photo" alt="{{$patient->pname}}" class="profile rounded-circle">
        @else
        <img src="{{asset('unknown.png')}}" id="patient_profile_photo" alt="{{$patient->pname}}" class="profile rounded-circle">
        @endif
        </div>
        <h4 class="center">{{ucwords($patient->pname)}}</h4>
        <h4 class="center" title="Phone No.">{{$patient->phone}}</h4>
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
                <span class="badge badge-success">Done</span>
              @else
                <span class="badge badge-secondary">Undone</span>
              @endif
            </h3>
            @php
            $diagnoseArray = explode("**",substr($diagnose->diagnose,2));
            @endphp
            @foreach ($diagnoseArray as $row)
              @php
              $r= explode(">>>",$row);
              @endphp
              <h4>{{$r[0]}}</h4>
              <p>{{$r[1]}}</p>
            @endforeach
          </div>
          <div class="col-12 col-sm-3">
            @if ($diagnose->total_price==null)
              <button class="btn btn-home btn-block action" data-action="#add_total_price" data-url="/patient/diagnosis/{{$diagnose->id}}/add/total_price">Add Total Price</button>
            @else
              <p>Total Price: {{$diagnose->total_price}}</p>
            @endif
            @if ($diagnose->already_payed==null&&$diagnose->total_price==null)
            @elseif ($diagnose->already_payed==null)
              <p>Total Paid: 0</p>
              <button href="" class="btn btn-home btn-block action" data-action="#add_payment" data-url="/patient/diagnosis/{{$diagnose->id}}/add/payment">add payment</button>
            @else
              <p>Total Paid: {{$diagnose->already_payed}}</p>
              <button href="" class="btn btn-home btn-block action" data-action="#add_payment" data-url="/patient/diagnosis/{{$diagnose->id}}/add/payment">add payment</button>
            @endif
            <button href="" class="btn btn-home btn-block action" data-action="#add_visit" data-url="/patient/diagnosis/{{$diagnose->id}}/">Add visit</button>
            <button href="" class="btn btn-success btn-block action" data-action="#finish" data-url="/patient/diagnosis/{{$diagnose->id}}/finish">finish</button>
            <button href="" class="btn btn-danger btn-block action" data-action="#delete" data-url="/patient/diagnosis/delete/{{$diagnose->id}}">delete</button>
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
          <input type="text" name="payment" id="payment" placeholder="Enter Payment" class="form-control">
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
  <div id="delete" class="float_form bg-home">
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
</div>
@endsection
