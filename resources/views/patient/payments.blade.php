@extends("layout.master")
@section("title")
  Patient {{ucwords($patient->pname)}}
@endsection
@section('container')
<div class="card">
  <div class="card-header">
    <h4>
      <a href="{{route('profilePatient',['id'=>$patient->id])}}">{{ucwords($patient->pname)}}</a>
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
    @if($diagnoses->count()>0)
      @php
        $count=1;
      @endphp
      <table class="table table-striped">
        <tr>
          <th>#</th>
          <th>Diagnosis</th>
          <th>Discount</th>
          <th>Total Paid</th>
          <th>Total Price</th>
          <th>Without Discount</th>
        </tr>
        @php
        $allDiagnosisPaid=0;
        $allDiagnosisPrice=0;
        $withoutDiscount=0;
        @endphp
        @foreach ($diagnoses as $diagnose)
        <tr>
          <th>{{$count++}}</th>
          <th><a href="{{route('showDiagnose',['id'=>$diagnose->id])}}">Diagnose Nr. {{$diagnose->id}}</a></th>
          @if ($diagnose->discount!=0 || $diagnose->discount!=null)
          <th>{{$diagnose->discount}} @if($diagnose->discount_type==0) % @else EGP @endif</th>
          @else
          <th>No Discount</th>
          @endif
          <th>@if($diagnose->total_paid==null) 0 EGP @else {{$diagnose->total_paid}} EGP @endif</th>
          @php
          $total_price=0;
          foreach($diagnose->teeth as $tooth){
            $total_price+=$tooth->price;
          }
          $withoutDiscount+=$total_price;
          if ($diagnose->discount!=null || $diagnose->discount!=0) {
            if($diagnose->discount_type==0){
              $discount = $total_price * ($diagnose->discount/100);
              $total_price -= $discount;
            }else {
              $total_price -= $diagnose->discount;
            }
          }
          $allDiagnosisPaid+=$diagnose->total_paid;
          $allDiagnosisPrice+=$total_price;
          @endphp
          <th>{{$total_price}} EGP</th>
          <th>{{$diagnose->teeth()->where('deleted',0)->sum('price')}} EGP</th>
        </tr>
        @endforeach
        <tr>
          <th colspan="3">Total</th>
          <th>{{$allDiagnosisPaid}} EGP</th>
          <th>{{$allDiagnosisPrice}} EGP</th>
          <th>{{$withoutDiscount}} EGP</th>
        </tr>
      </table>
    @else
    <div class="alert alert-warning">
      There is no Diagnosis
    </div>
    @endif
  </div>
</div>
@endsection
