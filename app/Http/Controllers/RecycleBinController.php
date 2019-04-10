<?php

namespace App\Http\Controllers;


use Auth;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use App\Repositories\AppointmentRepository;
use App\Repositories\AppointmentStateRepository;
use App\Repositories\UserLogRepository;
use App\Repositories\UserRepository;
use App\Repositories\DiagnoseRepository;
use App\Repositories\PatientRepository;
use App\Repositories\ToothRepository;
use App\Repositories\DrugRepository;
use App\Repositories\WorkingTimeRepository;

class RecycleBinController extends Controller
{

  protected $appointment;
  protected $userlog;
  protected $user;
  protected $appState;
  protected $diagnose;
  protected $patient;
  protected $tooth;
  protected $drug;
  protected $workTime;

  public function __construct(
    AppointmentRepository $appointment, 
    UserRepository $user, 
    UserLogRepository $userlog, 
    AppointmentStateRepository $appState,
    DiagnoseRepository $diagnose,
    PatientRepository $patient,
    DrugRepository $drug,
    ToothRepository $tooth,
    WorkingTimeRepository $workTime
  )
  {
    $this->appointment = $appointment;
    $this->userlog = $userlog;
    $this->user = $user;
    $this->appState = $appState;
    $this->diagnose = $diagnose;
    $this->patient = $patient;
    $this->tooth = $tooth;
    $this->drug = $drug;
    $this->workTime = $workTime;
  }

  /*
  *
  *
  * Get a list of deleted rows
  *
  */
  public function getTeeth()
  {
    if(Auth::user()->role==1||Auth::user()->role==2){
      $teeth = $this->tooth->allDeleted();
      $data=[
        'teeth'=>$teeth
      ];
      return view('recycle.allTeeth',$data);
    }else {
      return view('errors.404');
    }
  }
  public function getPatients()
  {
    if(Auth::user()->role==1||Auth::user()->role==2){
      $patients = $this->patient->allDeleted();
      $data=[
        'patients'=>$patients
      ];
      return view('recycle.allPatients',$data);
    }else {
      return view('errors.404');
    }
  }
  public function getDiagnoses()
  {
    if(Auth::user()->role==1||Auth::user()->role==2){
      $diagnoses = $this->diagnose->allDeleted();
      $data=[
        'diagnoses'=>$diagnoses
      ];
      return view('recycle.allDiagnoses',$data);
    }else {
      return view('errors.404');
    }
  }
  public function getDrugs()
  {
    if(Auth::user()->role==1||Auth::user()->role==2){
      $drugs = $this->drug->allDeleted();
      $data=[
        'drugs'=>$drugs
      ];
      return view('recycle.allDrugs',$data);
    }else {
      return view('errors.404');
    }
  }
  public function getAppointments()
  {
    if(Auth::user()->role==1||Auth::user()->role==2){
      $appointments = $this->appointment->allDeleted();
      $data=[
        'visits'=>$appointments
      ];
      return view('recycle.allVisits',$data);
    }else {
      return view('errors.404');
    }
  }
  public function getWorkingTimes()
  {
    if(Auth::user()->role==1||Auth::user()->role==2){
      $working_times = $this->workTime->allDeleted();
      $data=[
        'working_times'=>$working_times
      ];
      return view('recycle.allWorkingTimes',$data);
    }else {
      return view('errors.404');
    }
  }
  public function getUsers()
  {
    if(Auth::user()->role==1||Auth::user()->role==2){
      $users = $this->user->allDeleted();
      $data=[
        'users'=>$users
      ];
      return view('recycle.allUsers',$data);
    }else {
      return view('errors.404');
    }
  }

  /*
  *
  *
  * recover deleted rows
  *
  */
  public function recoverTooth($id)
  {
    if(Auth::user()->role==1){
      $tooth=$this->tooth->recover($id);
      $log['id']= $tooth->diagnose_id;
      $log['table']="diagnoses";
      $log['action']="recover";
      $log['description']='has recovered a deleted tooth from this diagnosis. details of this tooth "Name" '.$tooth->teeth_name.' "Diagnosis Type" '.$tooth->diagnose_type;
      $log['description'].=' "Price" '.$tooth->price.' "Description" '.$tooth->description;
      $this->userlog->create($log);
      return redirect()->back()->with('success','The tooth successfully recovered');
    }else {
      return view('errors.404');
    }
  }
  public function recoverAppointment($id)
  {
    if(Auth::user()->role==1){
      $visit=$this->appointment->recover($id);
      $log['id']= $visit->id;
      $log['table']="appointments";
      $log['action']="recover";
      $log['description']="has recovered a deleted visit ";
      $this->userlog->create($log);
      return redirect()->back()->with('success','The visit successfully recovered');
    }else{
      return view('errors.404');
    }
  }
  public function recoverPatient($id)
  {
    if(Auth::user()->role==1){
      $patient = $this->patient->recover($id);
      $log['id']= $patient->id;
      $log['table']="patients";
      $log['action']="recover";
      $log['description']="has recovered a deleted patient ";
      $this->userlog->create($log);
      return redirect()->route('profilePatient',['id'=>$patient->id])->with('success','The patient successfully recovered');
    }else {
      return view('errors.404');
    }
  }
  public function recoverDiagnose($id)
  {
    if(Auth::user()->role==1){
      $diagnose = $this->diagnose->recover($id);
      $log['id']= $diagnose->id;
      $log['table']="diagnoses";
      $log['action']="recover";
      $log['description']="has recovered a deleted diagnosis ";
      $this->userlog->create($log);
      return redirect()->back()->with('success','The diagnosis successfully recovered');
    }else {
      return view('errors.404');
    }
  }
  public function recoverDrug($id)
  {
    if(Auth::user()->role==1){
      $drug = $this->drug->recover($id);
      $log['table']="drugs";
      $log['id']=$drug->id;
      $log['action']="recover";
      $log['description']="has recovered a deleted medication back to the system";
      $this->userlog->create($log);
      return redirect()->back()->with('success','The medication successfully recovered');
    }else {
      return view('errors.404');
    }
  }
  public function recoverUser($id)
  {
    if(Auth::user()->role==1){
      $user = $this->user->recover($id);
      $log['table']="users";
      $log['id']=$user->id;
      $log['action']="recover";
      $log['description']="has recovered a deleted user";
      $this->userlog->create($log);
      return redirect()->back()->with('success','The user successfully recovered');
    }else {
      return view('errors.404');
    }
  }
  public function recoverWorkingTime($id)
  {
    if(Auth::user()->role==1){
      $working_time = $this->workTime->recover($id);
      $log['table']="working_times";
      $log['id']=$working_time->id;
      $log['action']="recover";
      $log['description']="has recovered a deleted working time";
      $this->userlog->create($log);
      return redirect()->back()->with('success','The working time successfully recovered');
    }else {
      return view('errors.404');
    }
  }
  /*
  *
  *
  * delete rows permanently
  *
  */
  public function deleteTooth($id)
  {
    if (Auth::user()->role==1) {
      $tooth = $this->tooth->permanentDelete($id);
      $log['table']="permanent delete";
      $log['id']=$tooth->id;
      $log['action']="permanent delete";
      $log['description']="has deleted a tooth ".$tooth->teeth_name;
      $this->userlog->create($log);
      return redirect()->back()->with('success','the tooth is deleted successfully');
    } else {
      return view('errors.404');
    }
  }
  public function deleteAppointment($id)
  {
    if (Auth::user()->role==1) {
      $visit = $this->appointment->permanentDelete($id);
      $log['table']="permanent delete";
      $log['id']=$visit->id;
      $log['action']="permanent delete";
      $log['description']="has deleted visit that was at ".date('d-m-Y',strtotime($visit->date));
      $this->userlog->create($log);
      return redirect()->back()->with('success','the visit is deleted successfully');
    } else {
      return view('errors.404');
    }
  }
  public function deletePatient($id)
  {
    if (Auth::user()->role==1) {
      $patient = $this->patient->permanentDelete($id);
      $log['table']="permanent delete";
      $log['id']=$patient->id;
      $log['action']="permanent delete";
      $log['description']="has deleted patient ".$patient->pname;
      $this->userlog->create($log);
      return redirect()->back()->with('success',"The patient and all its related diagnosis, visits, x-rays, medicines and case photos are delted successfully");
    } else {
      return view('errors.404');
    }
  }
  public function deleteUser($id)
  {
    if (Auth::user()->role==1) {
      $user = $this->user->permanentDelete($id);
      $log['table']="permanent delete";
      $log['id']=$user->id;
      $log['action']="permanent delete";
      $log['description']="has deleted user ".$user->name;
      $this->userlog->create($log);
      return redirect()->back()->with('success','The user successfully deleted');
    } else {
      return view('errors.404');
    }
  }
  public function deleteDiagnose($id)
  {
    if (Auth::user()->role==1) {
      $diagnose = $this->diagnose->permanentDelete($id);
      $log['table']="permanent delete";
      $log['id']=$diagnose->id;
      $log['action']="permanent delete";
      $log['description']="has deleted Diagnosis Nr. ".$diagnose->id;
      $this->userlog->create($log);
      return redirect()->back()->with('success','The Diagnosis Nr. '.$diagnose->id.' successfully deleted');
    } else {
      return view('errors.404');
    }
  }
  public function deleteWorkingTime($id)
  {
    if (Auth::user()->role==1) {
      $working_time = $this->workTime->permanentDelete($id);
      $log['table']="permanent delete";
      $log['id']=$working_time->id;
      $log['action']="permanent delete";
      $log['description']="has deleted working time at ".$working_time->getDayName()." from ".date('h:i a',strtotime($working_time->time_from))." to ".date('h:i a',strtotime($working_time->time_to));
      $this->userlog->create($log);
      return redirect()->back()->with('success','the working time is deleted successfully');
    } else {
      return view('errors.404');
    }
  }
}
