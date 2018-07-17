<?php

namespace App\Http\Controllers;

use Validator;
use Auth;
use App\UserLog;
use App\Diagnose;
use App\Appointment;
use App\WorkingTime;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     * $id of Diagnose
     * @return \Illuminate\Http\Response
     */
    public function getAvailableTime(Request $request)
    {
      $rules=[
        'visit_date'=>"date"
      ];
      $validator=Validator::make($request->all(),$rules);
      if ($validator->fails()) {
        return json_encode(['state'=>'NOK','error'=>"Please select a date from the calendar","code"=>422]);
      }
      $today= date("Y-m-d");;
      if($request->visit_date<$today){
        return json_encode(['state'=>'NOK','error'=>"Date must be equal to or greater than today's date","code"=>422]);
      }
      $day=date('N',strtotime($request->visit_date));
      $reservedAppointments = Appointment::where('deleted',0)->where('date', $request->visit_date)->get();
      $workingTimes = WorkingTime::where("deleted",0)->where('day',$day)->orderBy('time_from','ASC')->get();
      $workTimeArray= array();
      foreach ($workingTimes as $time) {
        for ($i=strtotime($time->time_from); $i < strtotime($time->time_to); $i=strtotime('+30 minutes',$i)) {
          array_push($workTimeArray,date('h:i a',$i));
        }
      }
      if(count($workTimeArray)==0){
        return json_encode(['state'=>'NOK','error'=>"The date you have choosed is not a working day","code"=>422]);
      }
      foreach ($reservedAppointments as $visit) {
        $key = array_search(date('h:i a',strtotime($visit->time)),$workTimeArray);
        if($key!==false){
          unset($workTimeArray[$key]);
        }
      }
      return json_encode(['state'=>'OK','success'=>"Success","available_appointments"=>$workTimeArray,"code"=>422]);
    }
    /**
     * Show the form for creating a new resource.
     * $id of Diagnose
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {

    }

    /**
     * Store a newly created resource in storage.
     * $id of Diagnose
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,$id)
    {
      $diagnose = Diagnose::where("deleted",0)->where('id',$id)->firstOrFail();
      $rules=[
        'visit_date'=>"date",
        'visit_treatment'=>'required'
      ];
      $validator=Validator::make($request->all(),$rules);
      if ($validator->fails()) {
        return redirect()->back()->with('error',"Please select a date from the calendar and fill down the treatment");
      }
      $today= date("Y-m-d");;
      if($request->visit_date<$today){
        return redirect()->back()->with('error',"Date must be equal to or greater than today's date");
      }
      $day=date('N',strtotime($request->visit_date));
      $reservedAppointments = Appointment::where('deleted',0)->where('date', $request->visit_date)->get();
      $workingTimes = WorkingTime::where("deleted",0)->where('day',$day)->orderBy('time_from','ASC')->get();
      $workTimeArray= array();
      foreach ($workingTimes as $time) {
        for ($i=strtotime($time->time_from); $i < strtotime($time->time_to); $i=strtotime('+30 minutes',$i)) {
          array_push($workTimeArray,date('h:i a',$i));
        }
      }
      $key = array_search(date('h:i a',strtotime($request->visit_time)),$workTimeArray);
      if($key===false){
        return redirect()->back()->with('error',"The Visit time is out of the working times at ".$request->date."  ".date('h:i a',strtotime($request->visit_time)));
      }
      foreach ($reservedAppointments as $visit) {
        if(strtotime($request->visit_time)==$visit->time){
          return redirect()->back()->with('error',"There's already a reserved visit at this time ".date('h:i a',strtotime($request->visit_time)));
        }
      }

      //store the appointment
      $visit = new Appointment;
      $visit->time= date('H:i:s',strtotime($request->visit_time));
      $visit->date=$request->visit_date;
      $visit->treatment=$request->visit_treatment;
      $saved= $diagnose->appointments()->save($visit);
      if (!$saved) {
        return redirect()->back()->with('error',"A server error happened during saving this visit");
      }
      $log = new UserLog;
      $log->affected_table="appointments";
      $log->affected_row=$visit->id;
      $log->process_type="create";
      $log->description="has created a visit at ".$request->visit_date." ".date('h:i a',strtotime($request->visit_time));
      $log->user_id=Auth::user()->id;
      $log->save();
      return redirect()->back()->with('success',"The visit is created successfully");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
