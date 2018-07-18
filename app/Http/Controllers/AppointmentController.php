<?php

namespace App\Http\Controllers;

use Validator;
use Auth;
use App\UserLog;
use App\Patient;
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
    public function index($date)
    {
      if (strtotime($date)===false) {
        return redirect()->back()->with('error','Invalid date detected');
      }
      $visits= Appointment::where('deleted',0)->where('date',$date)->orderBy('approved','DESC')->orderBy('approved_time','ASC')->orderBy('time','ASC')->get();
      $data=[
        'date'=>$date,
        'visits'=>$visits
      ];
      return view('visit.all',$data);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function allWithinDiagnose($id)
    {
      $diagnose = Diagnose::findOrFail($id);
      $visits = $diagnose->appointments()->where('deleted',0)->orderBy('approved','DESC')->orderBy('date','ASC')->orderBy('time','ASC')->get();
      $data=[
        'diagnose'=>$diagnose,
        'visits'=>$visits
      ];
      return view('visit.all_diagnose',$data);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function allWithinPatient($id)
    {
      $patient = Patient::findOrFail($id);
      $visits = $patient->appointments()->where('deleted',0)->orderBy('date','ASC')->orderBy('time','ASC')->get();
      $data=[
        'patient'=>$patient,
        'visits'=>$visits
      ];
      return view('');
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
      $diagnose = Diagnose::where("deleted",0)->where('id',$id)->firstOrFail();
      $data=[
        'diagnose'=>$diagnose
      ];
      return view('visit.add',$data);
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
        if(strtotime($request->visit_time)==strtotime($visit->time)){
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
      $visit = Appointment::findOrFail($id);
      return view('visit.edit',['visit'=>$visit]);
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
      $visit = Appointment::findOrFail($id);
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
      $reservedAppointments = Appointment::where('deleted',0)->where('id','!=',$id)->where('date', $request->visit_date)->get();
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
      foreach ($reservedAppointments as $res_visit) {
        if(strtotime($request->visit_time)==strtotime($res_visit->time)){
          return redirect()->back()->with('error',"There's already a reserved visit at this time ".date('h:i a',strtotime($request->visit_time)));
        }
      }

      //store the appointment
      $description="";
      if($visit->date!=$request->visit_date){
        $description.="has changed visit date from ".$visit->date." to ".$request->visit_date.". ";
        $visit->date=$request->visit_date;
      }
      if($visit->time != date('H:i:s',strtotime($request->visit_time))){
        $description.="has changed visit time from ".date('h:i a',strtotime($visit->date))." to ".date('h:i a',strtotime($request->visit_time)).". ";
        $visit->time= date('H:i:s',strtotime($request->visit_time));
      }
      if ($visit->treatment!=$request->visit_treatment) {
        $description.='has changed visit treatment from "'.$visit->treatment.'" to "'.$request->visit_treatment.'". ';
        $visit->treatment=$request->visit_treatment;
      }
      if ($description!="") {
        $saved= $visit->save();
        if (!$saved) {
          return redirect()->back()->with('error',"A server error happened during editing this visit");
        }
        $log = new UserLog;
        $log->affected_table="appointments";
        $log->affected_row=$visit->id;
        $log->process_type="update";
        $log->description=$description;
        $log->user_id=Auth::user()->id;
        $log->save();
        return redirect()->back()->with('success',"The visit is edited successfully");
      }
      return redirect()->back()->with('warning',"There is nothing to change");
    }

    /**
     * Approve an Appointment
     *
     * @param  \App\Appointment  $appointment
     *
     */
     public function approve($id)
     {
       $visit = Appointment::findOrFail($id);
       $visit->approved=3;
       $visit->approved_time= date('Y-m-d H:i:s');
       $saved= $visit->save();
       if(!$saved){
         return redirect()->back()->with('error','A server error happened during approving visit, <br> Please try again later');
       }
       $log= new UserLog;
       $log->affected_table="appointments";
       $log->affected_row=$visit->id;
       $log->process_type="update";
       $log->description="has approved the visit at".date('d-m-Y',strtotime($visit->date))." ".date('h:i a',strtotime($visit->time)).' with the treatment "'.$visit->treatment.'"';
       $log->user_id=Auth::user()->id;
       $log->save();
       return redirect()->back()->with('success','The visit is successfully approved');
     }
    /**
     * end an Appointment
     *
     * @param  \App\Appointment  $appointment
     *
     */
     public function endAppointment($id)
     {
       $visit = Appointment::findOrFail($id);
       $visit->approved=1;
       $visit->approved_time= date('Y-m-d H:i:s');
       $saved= $visit->save();
       if(!$saved){
         return redirect()->back()->with('error','A server error happened during approving visit, <br> Please try again later');
       }
       $log= new UserLog;
       $log->affected_table="appointments";
       $log->affected_row=$visit->id;
       $log->process_type="update";
       $log->description="has ended the visit at".date('d-m-Y',strtotime($visit->date))." ".date('h:i a',strtotime($visit->time)).' with the treatment "'.$visit->treatment.'"';
       $log->user_id=Auth::user()->id;
       $log->save();
       return redirect()->back()->with('success','The visit is successfully approved');
     }




    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      $visit = Appointment::findOrFail($id);
      $visit->deleted=1;
      $saved=$visit->save();
      if(!$saved){
        return redirect()->back()->with('error','A server error happened during deleting visit, <br> Please try again later');
      }
      $log = new UserLog;
      $log->affected_table="appointments";
      $log->affected_row=$visit->id;
      $log->process_type="delete";
      $log->description="has deleted the visit at ".date('d-m-Y',strtotime($visit->date))." ".date('h:i a',strtotime($visit->time)).' with treatment "'.$visit->treatment.'"';
      $log->user_id=Auth::user()->id;
      $log->save();
      return redirect()->back()->with('success','The visit is successfully deleted');
    }
}
