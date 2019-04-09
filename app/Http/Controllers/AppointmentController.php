<?php

namespace App\Http\Controllers;

use Validator;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests\StoreAppointment;

use App\Repositories\AppointmentRepository;
use App\Repositories\AppointmentStateRepository;
use App\Repositories\UserLogRepository;
use App\Repositories\DiagnoseRepository;
use App\Repositories\PatientRepository;
use App\Repositories\WorkingTimeRepository;

class AppointmentController extends Controller
{
    protected $appointment;
    protected $userlog;
    protected $appState;
    protected $diagnose;
    protected $patient;
    protected $workTime;

    public function __construct(
      AppointmentRepository $appointment, 
      UserLogRepository $userlog, 
      AppointmentStateRepository $appState,
      DiagnoseRepository $diagnose,
      PatientRepository $patient,
      WorkingTimeRepository $workTime
    )
    {
      $this->appointment = $appointment;
      $this->userlog = $userlog;
      $this->appState = $appState;
      $this->diagnose = $diagnose;
      $this->patient = $patient;
      $this->workTime = $workTime;
    }
    /**
     * Display Home.
     *
     * @return \Illuminate\Http\Response
     */
     public function home(){
       $visits = $this->appointment->allOnDate(date('Y-m-d'));
       $stateVisit=$this->appState->get();
       $data =[
         'visits'=>$visits,
         'stateVisit'=>$stateVisit
       ];
       return view('home',$data);
     }
    /**
     * Display Home.
     *
     * @return \Illuminate\Http\Response
     */
     public function ajaxGetVisits(){
       $notApproved = $this->appointment->allNotApproved();
       $approved = $this->appointment->allApproved();
       $finished = $this->appointment->allFinished();
       $data =[
         'state'=>"OK",
         'notApproved'=>$notApproved,
         'approved'=>$approved,
         'finished'=>$finished,
         'code'=>422
       ];
       return json_encode($data);
     }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function checkState()
     {
       $stateVisit = $this->appState->get();
       return json_encode(['state'=>'OK','stateVisit'=>$stateVisit->value,'date'=>$stateVisit->date,'code'=>422]);
     }
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
      $visits= $this->appointment->allOnDate($date);
      $stateVisit=$this->appState->get();
      $data=[
        'date'=>$date,
        'visits'=>$visits,
        'stateVisit'=>$stateVisit
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
      $diagnose = $this->diagnose->get($id);
      $visits = $this->appointment->allByDiagnoseId($id);
      $stateVisit=$this->appState->get();
      $data=[
        'diagnose'=>$diagnose,
        'visits'=>$visits,
        'stateVisit'=>$stateVisit
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
      $patient = $this->patient->get($id);
      $visits = $this->appointment->allByPatient($patient);
      $stateVisit=$this->appState->get();
      $data=[
        'date'=>$patient,
        'visits'=>$visits,
        'stateVisit'=>$stateVisit
      ];
      return view('visit.all',$data);
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
      $reservedAppointments = $this->appointment->allOnDate($request->visit_date);
      $workingTimes = $this->workTime->onDay($day);
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
      $diagnose = $this->diagnose->get($id);
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
    public function store(StoreAppointment $request,$id)
    {
      $diagnose = $this->diagnose->getUndone($id);
      $today= date("Y-m-d");
      if($request->visit_date<$today){
        return redirect()->back()->with('error',"Date must be equal to or greater than today's date");
      }
      $day=date('N',strtotime($request->visit_date));
      $reservedAppointments = $this->appointment->allOnDate($request->visit_date);
      $workingTimes = $this->workTime->onDay($day);
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
      $data['time']= date('H:i:s',strtotime($request->visit_time));
      $data['date']=$request->visit_date;
      $data['treatment']=$request->visit_treatment;
      $data['diagnose_id']=$id;
      $this->appointment->create($data);
      if($request->visit_date==$today){
        $this->appState->update($request->visit_date);
      }
      $this->userlog->create([
        'table' => 'appointments',
        'id' => $visit->id,
        'action' => 'create',
        'description' => "has created a visit at ".$request->visit_date." ".date('h:i a',strtotime($request->visit_time))
      ]);
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
      $visit = $this->appointment->get($id);
      return view('visit.edit',['visit'=>$visit]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function update(StoreAppointment $request, $id)
    {
      $visit = $this->appointment->get($id);
      $today= date("Y-m-d");;
      if($request->visit_date<$today){
        return redirect()->back()->with('error',"Date must be equal to or greater than today's date");
      }
      $day=date('N',strtotime($request->visit_date));
      $reservedAppointments = $this->appointment->allOnDate($request->visit_date, $id);
      $workingTimes = $this->workTime->onDay($day);
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
      $data = ['time' => $request->visit_time, 'date' => $request->visit_date, 'treatment'=> $visit->treatment];
      $description = $this->appointment->update($id, $data);
      if ($description!="") {
        $log['table']="appointments";
        $log['id']=$visit->id;
        $log['action']="update";
        $log['description']=$description;
        $this->userlog->create($log);
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
       $visit = $this->appointment->approve($id);
       $this->appState->update($visit->date);
       $log['table']="appointments";
       $log['id']=$visit->id;
       $log['action']="update";
       $log['description']="has approved the visit at".date('d-m-Y',strtotime($visit->date))." ".date('h:i a',strtotime($visit->time)).' with the treatment "'.$visit->treatment.'"';
       $this->userlog->create($log);
       return redirect()->back()->with('success','The visit is successfully approved');
     }
    /**
     * cancel an Appointment
     *
     * @param  \App\Appointment  $appointment
     *
     */
     public function cancelAppointment($id)
     {
       $visit = $this->appointment->cancel($id);
       $this->appState->update($visit->date);
       $log['table']="appointments";
       $log['id']=$visit->id;
       $log['action']="update";
       $log['description']="has cancelled the visit at".date('d-m-Y',strtotime($visit->date))." ".date('h:i a',strtotime($visit->time)).' with the treatment "'.$visit->treatment.'"';
       $this->userlog->create($log);
       return redirect()->back()->with('success','The visit is successfully cancelled');
     }
    /**
     * end an Appointment
     *
     * @param  \App\Appointment  $appointment
     *
     */
     public function endAppointment($id)
     {
       $visit = $this->appointment->finish($id);
       $diagnose= $visit->diagnose;
       $countOfAllVisits = $diagnose->appointments()->where('approved',"!=",0)->count();
       $countOfDoneVisits = $diagnose->appointments()->finished()->count();
       $successMsg="The visit is successfully finished";
       if ($countOfAllVisits==$countOfDoneVisits) {
         $successMsg.="<br>There is no more visits within this diagnosis, it would be nice if you either end this diagnosis or add another visit";
         $successMsg.='<br><form method="post" action="'.route("finishDiagnose",["id"=>$diagnose->id]).'"><input type="submit" value="finish diagnosis" class="m-2 btn btn-success">';
         $successMsg.="<input type='hidden' name='_token' value='".csrf_token()."'> <input type='hidden' name='_method' value='PUT'>";
         $successMsg.='<a href="'.route("addAppointment",["id"=>$diagnose->id]).'" class="m-2 btn btn-home">add visit</a></form>';
       }
       $this->appState->update($visit->date);
       $log['table']="appointments";
       $log['id']=$visit->id;
       $log['action']="update";
       $log['description']="has ended the visit at".date('d-m-Y',strtotime($visit->date))." ".date('h:i a',strtotime($visit->time)).' with the treatment "'.$visit->treatment.'"';
       $this->userlog->create($log);
       return redirect()->back()->with('success',$successMsg);
     }




    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      $visit = $this->appointment->delete($id);
      $log['table']="appointments";
      $log['id']=$visit->id;
      $log['action']="delete";
      $log['description']="has deleted the visit at ".date('d-m-Y',strtotime($visit->date))." ".date('h:i a',strtotime($visit->time)).' with treatment "'.$visit->treatment.'"';
      $this->userlog->create($log);
      return redirect()->back()->with('success','The visit is successfully deleted');
    }
}
