@extends("layout.master")
@section('title','Edit Working Time')
@section('container')
<div class="card">
  <div class="card-header"><h4>Edit working time</h4></div>
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
    @if (session("warning")!=null)
    <div class="alert alert-warning alert-dismissible fade show">
      <h4 class="alert-heading">Warning</h4>
      {!!session("warning")!!}
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    @endif
    <form class="timepicker_form" action="{{route('updateWorkingTime',['id'=>$time->id])}}" method="post">
      <div class="form-group row">
        <label for="day" class="col-sm-2">Day</label>
        <div class="col-sm-10">
          <select name="day" id="day" class="custom-select @if ($errors->has('day')) is-invalid @endif">
            <option value="">Select Day</option>
            <option @if($time->day==6) selected @endif value="6">Saturday</option>
            <option @if($time->day==7) selected @endif value="7">Sunday</option>
            <option @if($time->day==1) selected @endif value="1">Monday</option>
            <option @if($time->day==2) selected @endif value="2">Tuesday</option>
            <option @if($time->day==3) selected @endif value="3">Wendesday</option>
            <option @if($time->day==4) selected @endif value="4">Thursday</option>
            <option @if($time->day==5) selected @endif value="5">Friday</option>
          </select>
          @if ($errors->has("day"))
            @foreach ($errors->get("day") as $msg)
              <div style='display:block' class='invalid-feedback'>{{$msg}}</div>
            @endforeach
          @endif
        </div>
      </div>
      <div class="form-group row">
        <label class="col-sm-2">Time From</label>
        <div class="col-sm-10">
          <select name="time_from" id="time_from" class="custom-select @if ($errors->has('time_from')) is-invalid @endif">
            @php
              $quarter=15;
            @endphp
            @for ($i=0; $i <= 12; $i++)
              @for ($j=0; $j < 4; $j++)
                <option @if($time->time_from==str_pad($i,2,"0",STR_PAD_LEFT).":".str_pad($j*$quarter,2,"0",STR_PAD_LEFT).":00") selected @endif value="{{str_pad($i,2,"0",STR_PAD_LEFT).":".str_pad($j*$quarter,2,"0",STR_PAD_LEFT).":00"}}">{{str_pad($i,2,"0",STR_PAD_LEFT).":".str_pad($j*$quarter,2,"0",STR_PAD_LEFT)}}
                  @if ($i==12) pm @else am @endif</option>
              @endfor
            @endfor
            @for ($i=1; $i < 12; $i++)
              @for ($j=0; $j < 4; $j++)
                <option @if($time->time_from==str_pad($i+12,2,"0",STR_PAD_LEFT).":".str_pad($j*$quarter,2,"0",STR_PAD_LEFT).":00") selected @endif value="{{str_pad($i+12,2,"0",STR_PAD_LEFT).":".str_pad($j*$quarter,2,"0",STR_PAD_LEFT).":00"}}">{{str_pad($i,2,"0",STR_PAD_LEFT).":".str_pad($j*$quarter,2,"0",STR_PAD_LEFT)}} pm</option>
              @endfor
            @endfor
          </select>
          @if ($errors->has("time_from"))
            @foreach ($errors->get("time_from") as $msg)
              <div style='display:block' class='invalid-feedback'>{{$msg}}</div>
            @endforeach
          @endif
        </div>
      </div>
      <div class="form-group row">
        <label class="col-sm-2">Time To</label>
        <div class="col-sm-10">
          <select name="time_to" id="time_to" class="custom-select @if ($errors->has('time_to')) is-invalid @endif">
            @for ($i=0; $i <= 12; $i++)
              @for ($j=0; $j < 4; $j++)
                <option @if($time->time_to==str_pad($i,2,"0",STR_PAD_LEFT).":".str_pad($j*$quarter,2,"0",STR_PAD_LEFT).":00") selected @endif value="{{str_pad($i,2,"0",STR_PAD_LEFT).":".str_pad($j*$quarter,2,"0",STR_PAD_LEFT).":00"}}">{{str_pad($i,2,"0",STR_PAD_LEFT).":".str_pad($j*$quarter,2,"0",STR_PAD_LEFT)}}
                  @if ($i==12) pm @else am @endif</option>
              @endfor
            @endfor
            @for ($i=1; $i < 12; $i++)
              @for ($j=0; $j < 4; $j++)
                <option @if($time->time_to==str_pad($i+12,2,"0",STR_PAD_LEFT).":".str_pad($j*$quarter,2,"0",STR_PAD_LEFT).":00") selected @endif value="{{str_pad($i+12,2,"0",STR_PAD_LEFT).":".str_pad($j*$quarter,2,"0",STR_PAD_LEFT).":00"}}">{{str_pad($i,2,"0",STR_PAD_LEFT).":".str_pad($j*$quarter,2,"0",STR_PAD_LEFT)}} pm</option>
              @endfor
            @endfor
          </select>
          @if ($errors->has("time_to"))
            @foreach ($errors->get("time_to") as $msg)
              <div style='display:block' class='invalid-feedback'>{{$msg}}</div>
            @endforeach
          @endif
        </div>
      </div>
      <input style="width: 150px; display: block; margin:0 auto;" type="submit" class="btn btn-secondary" value="Edit Working Time">
      @csrf
      @method('PUT')
    </form>
  </div>
</div>
@endsection
