@extends("layout.master")
@section("title","All Payments")
@section('container')
<div class="card">
  <div class="card-header">
    <h4>All Payments </h4>
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
      <div class="centerize">
        <div class="calendar-date stamp">
          <div class="calendar-day stamp-day">Total Paid</div>
          <div class="calendar-day-nr">{{$total_paidAllDiagnoses+0}}<br>EGP</div>
        </div>
        <div class="calendar-date stamp">
          <div class="calendar-day stamp-day">Total Price</div>
          <div class="calendar-day-nr">{{$total_priceAllDiagnoses+0}}<br>EGP</div>
        </div>
        <div class="calendar-date stamp">
          <div class="calendar-day stamp-day">Amount Outstanding</div>
          <div class="calendar-day-nr">{{$total_priceAllDiagnoses-$total_paidAllDiagnoses}}<br>EGP</div>
        </div>
      </div>
      @php
        $count=1;
      @endphp
      <table class="table table-striped mt-3">
        <tr>
          <th>#</th>
          <th>Diagnosis</th>
          <th>Discount</th>
          <th>Total Paid</th>
          <th>Total Price</th>
          <th>Without Discount</th>
          <th>Diagnosis Date</th>
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
          @if ($diagnose->discount!=0 && $diagnose->discount!=null)
          <th>{{$diagnose->discount}} @if($diagnose->discount_type==0) % @else EGP @endif</th>
          @else
          <th>No Discount</th>
          @endif
          <th>@if($diagnose->total_paid==null) 0 EGP @else {{$diagnose->total_paid}} EGP @endif</th>
          @php
          $total_price=0;
          foreach($diagnose->teeth()->where('deleted',0)->get() as $tooth){
            $total_price+=$tooth->price;
          }
          $withoutDiscount+=$total_price;
          if ($diagnose->discount!=null && $diagnose->discount!=0) {
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
          <th>{{$diagnose->teeth()->where('deleted',0)->sum('price')+0}} EGP</th>
          <th style="white-space:nowrap;">{{date('d-m-Y h:i a',strtotime($diagnose->created_at))}}</th>
        </tr>
        @endforeach
        <tr class="bg-home">
          <td colspan="3">Total</td>
          <td>{{$allDiagnosisPaid}} EGP</td>
          <td>{{$allDiagnosisPrice}} EGP</td>
          <td colspan="3">{{$withoutDiscount}} EGP</td>
        </tr>
      </table>
    @else
    <div class="alert alert-warning">
      There is no Payments
    </div>
    @endif
  </div>
</div>
@endsection
