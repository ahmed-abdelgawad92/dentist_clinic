@extends('layout.master')
@section("title")
  @if (isset($patient))
    Create Diagnosis to "{{$patient->pname}}"
  @else
    Create Diagnosis
  @endif
@endsection
@section('container')
<div class="card">
  <div class="card-header">
    @if (isset($patient))
      Create Diagnosis to "{{$patient->pname}}"
    @else
      Create Diagnosis
    @endif
  </div>
  <div class="card-body">
    <img src="{{asset('teeth.png')}}" alt="" id="diagnose_chart" usemap="#teeth">
    <map name="teeth">
      <area target="" alt="teeth_1" title="teeth_1" href="teeth_1" coords="92,324,25" shape="circle" class="diagnose_map">
      <area target="" alt="teeth_2" title="teeth_2" href="teeth_2" coords="95,274,26" shape="circle" class="diagnose_map">
      <area target="" alt="teeth_3" title="teeth_3" href="teeth_3" coords="102,227,26" shape="circle" class="diagnose_map">
      <area target="" alt="teeth_4" title="teeth_4" href="teeth_4" coords="115,180,25" shape="circle" class="diagnose_map">
      <area target="" alt="teeth_5" title="teeth_5" href="teeth_5" coords="136,138,24" shape="circle" class="diagnose_map">
      <area target="" alt="teeth_6" title="teeth_6" href="teeth_6" coords="162,104,20" shape="circle" class="diagnose_map">
      <area target="" alt="teeth_7" title="teeth_7" href="teeth_7" coords="187,76,20" shape="circle" class="diagnose_map">
      <area target="" alt="teeth_8" title="teeth_8" href="teeth_8" coords="226,57,24" shape="circle" class="diagnose_map">
      <area target="" alt="teeth_9" title="teeth_9" href="teeth_9" coords="271,57,23" shape="circle" class="diagnose_map">
      <area target="" alt="teeth_10" title="teeth_10" href="teeth_10" coords="311,75,24" shape="circle" class="diagnose_map">
      <area target="" alt="teeth_11" title="teeth_11" href="teeth_11" coords="337,104,21" shape="circle" class="diagnose_map">
      <area target="" alt="teeth_12" title="teeth_12" href="teeth_12" coords="361,137,23" shape="circle" class="diagnose_map">
      <area target="" alt="teeth_13" title="teeth_13" href="teeth_13" coords="382,181,25" shape="circle" class="diagnose_map">
      <area target="" alt="teeth_14" title="teeth_14" href="teeth_14" coords="395,226,24" shape="circle" class="diagnose_map">
      <area target="" alt="teeth_15" title="teeth_15" href="teeth_15" coords="402,275,24" shape="circle" class="diagnose_map">
      <area target="" alt="teeth_16" title="teeth_16" href="teeth_16" coords="404,323,25" shape="circle" class="diagnose_map">
      <area target="" alt="teeth_17" title="teeth_17" href="teeth_17" coords="401,397,28" shape="circle" class="diagnose_map">
      <area target="" alt="teeth_18" title="teeth_18" href="teeth_18" coords="398,451,26" shape="circle" class="diagnose_map">
      <area target="" alt="teeth_19" title="teeth_19" href="teeth_19" coords="388,502,27" shape="circle" class="diagnose_map">
      <area target="" alt="teeth_20" title="teeth_20" href="teeth_20" coords="370,553,27" shape="circle" class="diagnose_map">
      <area target="" alt="teeth_21" title="teeth_21" href="teeth_21" coords="345,594,25" shape="circle" class="diagnose_map">
      <area target="" alt="teeth_22" title="teeth_22" href="teeth_22" coords="318,625,17" shape="circle" class="diagnose_map">
      <area target="" alt="teeth_23" title="teeth_23" href="teeth_23" coords="293,642,14" shape="circle" class="diagnose_map">
      <area target="" alt="teeth_24" title="teeth_24" href="teeth_24" coords="263,649,16" shape="circle" class="diagnose_map">
      <area target="" alt="teeth_25" title="teeth_25" href="teeth_25" coords="233,648,15" shape="circle" class="diagnose_map">
      <area target="" alt="teeth_26" title="teeth_26" href="teeth_26" coords="202,641,17" shape="circle" class="diagnose_map">
      <area target="" alt="teeth_27" title="teeth_27" href="teeth_27" coords="179,625,19" shape="circle" class="diagnose_map">
      <area target="" alt="teeth_28" title="teeth_28" href="teeth_28" coords="153,594,23" shape="circle" class="diagnose_map">
      <area target="" alt="teeth_29" title="teeth_29" href="teeth_29" coords="127,553,27" shape="circle" class="diagnose_map">
      <area target="" alt="teeth_30" title="teeth_30" href="teeth_30" coords="108,503,27" shape="circle" class="diagnose_map">
      <area target="" alt="teeth_31" title="teeth_31" href="teeth_31" coords="99,451,27" shape="circle" class="diagnose_map">
      <area target="" alt="teeth_32" title="teeth_32" href="teeth_32" coords="94,397,28" shape="circle" class="diagnose_map">
      <area target="" alt="teeth_a" title="teeth_a" href="teeth_a" coords="170,226,18" shape="circle" class="diagnose_map">
      <area target="" alt="teeth_b" title="teeth_b" href="teeth_b" coords="178,193,16" shape="circle" class="diagnose_map">
      <area target="" alt="teeth_c" title="teeth_c" href="teeth_c" coords="193,169,16" shape="circle" class="diagnose_map">
      <area target="" alt="teeth_d" title="teeth_d" href="teeth_d" coords="209,147,13" shape="circle" class="diagnose_map">
      <area target="" alt="teeth_e" title="teeth_e" href="teeth_e" coords="232,132,15" shape="circle" class="diagnose_map">
      <area target="" alt="teeth_f" title="teeth_f" href="teeth_f" coords="263,133,16" shape="circle" class="diagnose_map">
      <area target="" alt="teeth_g" title="teeth_g" href="teeth_g" coords="287,147,15" shape="circle" class="diagnose_map">
      <area target="" alt="teeth_i" title="teeth_i" href="teeth_i" coords="318,194,17" shape="circle" class="diagnose_map">
      <area target="" alt="teeth_j" title="teeth_j" href="teeth_j" coords="326,227,18" shape="circle" class="diagnose_map">
      <area target="" alt="teeth_k" title="teeth_k" href="teeth_k" coords="329,480,20" shape="circle" class="diagnose_map">
      <area target="" alt="teeth_l" title="teeth_l" href="teeth_l" coords="317,515,19" shape="circle" class="diagnose_map">
      <area target="" alt="teeth_m" title="teeth_m" href="teeth_m" coords="303,549,15" shape="circle" class="diagnose_map">
      <area target="" alt="teeth_n" title="teeth_n" href="teeth_n" coords="283,569,14" shape="circle" class="diagnose_map">
      <area target="" alt="teeth_o" title="teeth_o" href="teeth_o" coords="260,577,14" shape="circle" class="diagnose_map">
      <area target="" alt="teeth_p" title="teeth_p" href="teeth_p" coords="236,577,15" shape="circle" class="diagnose_map">
      <area target="" alt="teeth_q" title="teeth_q" href="teeth_q" coords="211,570,13" shape="circle" class="diagnose_map">
      <area target="" alt="teeth_r" title="teeth_r" href="teeth_r" coords="193,548,16" shape="circle" class="diagnose_map">
      <area target="" alt="teeth_s" title="teeth_s" href="teeth_s" coords="179,517,20" shape="circle" class="diagnose_map">
      <area target="" alt="teeth_t" title="teeth_t" href="teeth_t" coords="168,481,21" shape="circle" class="diagnose_map">
      <area target="" alt="teeth_h" title="teeth_h" href="teeth_h" coords="303,170,13" shape="circle" class="diagnose_map">
    </map>
    <form id="diagnose-form" action="{{route('addDiagnose',["id"=>$patient->id])}}" method="post">
      <div class="form-group row">
        <label for="diagnose" class="col-sm-2">Diagnosis</label>
        <div class="col-sm-10">
          <textarea height="120px" name="diagnose" id="diagnose" placeholder="Write the Diagnosis" class="form-control" value="{{old('diagnose')}}"></textarea>
          @if ($errors->has("diagnose"))
            @foreach ($errors->get("diagnose") as $msg)
              <div style='display:block' class='invalid-feedback'>{{$msg}}</div>
            @endforeach
          @endif
        </div>
      </div>
      <div class="form-group row">
        <label for="total_price" class="col-sm-2">Total Price</label>
        <div class="col-sm-10 input-group">
          <input type="text" name="total_price" id="total_price" placeholder="Enter Total Price" class="form-control @if ($errors->has('total_price'))
            is-invalid
          @endif" value="{{old('total_price')}}">
          <div class="input-group-append">
            <span class="input-group-text" title="Egyptian Pound">EGP</span>
          </div>
          @if ($errors->has("total_price"))
            @foreach ($errors->get("total_price") as $msg)
              <div style='display:block' class='invalid-feedback'>{{$msg}}</div>
            @endforeach
          @endif
        </div>
      </div>
      <button class="btn btn-home btn-lg submit-btn">Create Diagnosis</button>
      @csrf
    </form>
  </div>
</div>
@endsection
