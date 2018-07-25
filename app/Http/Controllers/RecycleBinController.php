<?php

namespace App\Http\Controllers;

use App\Appointment;
use App\Diagnose;
use App\Patient;
use App\Drug;
use App\Tooth;
use App\WorkingTime;
use App\User;
use App\UserLog;
use Auth;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class RecycleBinController extends Controller
{
  /*
  *
  *
  * Get a list of deleted rows
  *
  */
  public function getTeeth()
  {
    if(Auth::user()->role==1){
      $teeth = Tooth::where('deleted',1)->orderBy('updated_at','DESC')->get();
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
    if(Auth::user()->role==1){
      $patients = Patient::where('deleted',1)->orderBy('updated_at','DESC')->get();
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
    if(Auth::user()->role==1){
      $diagnoses = Diagnose::where('deleted',1)->orderBy('updated_at','DESC')->get();
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
    if(Auth::user()->role==1){
      $drugs = Drug::where('deleted',1)->orderBy('updated_at','DESC')->get();
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
    if(Auth::user()->role==1){
      $appointments = Appointment::where('deleted',1)->orderBy('updated_at','DESC')->get();
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
    if(Auth::user()->role==1){
      $working_times = WorkingTime::where('deleted',1)->orderBy('updated_at','DESC')->get();
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
    if(Auth::user()->role==1){
      $users = User::where('deleted',1)->orderBy('updated_at','DESC')->get();
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
      $tooth=Tooth::findOrFail($id);
      if($tooth->diagnose->deleted==1){
        return redirect()->back()->with('error','The tooth is related to a deleted diagnosis, if you want to recover it ,then you have to recover first its diagnosis<a class="btn btn-danger" href="'.route('recoverDiagnose',['id'=>$tooth->diagnose_id]).'">recover diagnosis now</a>');
      }
      $tooth->deleted=0;
      $saved=$tooth->save();
      if(!$saved){
        return redirect()->back()->with('error','A server error happened during recovering a tooth<br> Please try again later');
      }
      $log = new UserLog;
      $log->affected_row= $tooth->diagnose_id;
      $log->affected_table="diagnoses";
      $log->process_type="recover";
      $log->description='has recovered a deleted tooth from this diagnosis. details of this tooth "Name" '.$tooth->teeth_name.' "Diagnosis Type" '.$tooth->diagnose_type;
      $log->description.=' "Price" '.$tooth->price.' "Description" '.$tooth->description;
      $log->user_id=Auth::user()->id;
      $log->save();
      return redirect()->back()->with('success','The tooth successfully recovered');
    }else {
      return view('errors.404');
    }
  }
  public function recoverAppointment($id)
  {
    if(Auth::user()->role==1){
      $visit=Appointment::findOrFail($id);
      if($visit->diagnose->deleted==1){
        return redirect()->back()->with('error',"Sorry but this visit belongs to a deleted <a href='".route('showDiagnose',['id'=>$visit->diagnose_id])."'>Diagnosis</a>, recover this diagnosis first if you want to proceed <a href='".route('recoverDiagnose',['id'=>$visit->diagnose_id])."'>recover now!</a>");
      }
      $visit->deleted=0;
      $saved=$visit->save();
      if(!$saved){
        return redirect()->back()->with('error','A server error happened during recovering a visit<br> Please try again later');
      }
      $log = new UserLog;
      $log->affected_row= $visit->id;
      $log->affected_table="appointments";
      $log->process_type="recover";
      $log->description="has recovered a deleted visit ";
      $log->user_id=Auth::user()->id;
      $log->save();
      return redirect()->back()->with('success','The visit successfully recovered');
    }else{
      return view('errors.404');
    }
  }
  public function recoverPatient($id)
  {
    if(Auth::user()->role==1){
      $patient=Patient::findOrFail($id);
      $diagnoses=$patient->diagnoses()->whereDate('diagnoses.updated_at',date('Y-m-d',strtotime($patient->updated_at)))->get();
      $teeth=$patient->teeth()->whereDate('teeth.updated_at',date('Y-m-d',strtotime($patient->updated_at)))->get();
      $visits=$patient->appointments()->whereDate('appointments.updated_at',date('Y-m-d',strtotime($patient->updated_at)))->get();
      $diagnose_drug=$patient->diagnose_drug()->whereDate('diagnose_drug.updated_at',date('Y-m-d',strtotime($patient->updated_at)))->get();
      try{
        DB::beginTransaction();
        $patient->deleted=0;
        foreach ($diagnoses as $d) {
          $d->deleted=0;
          $d->save();
        }
        foreach ($teeth as $t) {
          $t->deleted=0;
          $t->save();
        }
        foreach ($diagnose_drug as $dr) {
          $dr->deleted=0;
          $dr->save();
        }
        foreach ($visits as $v) {
          $v->deleted=0;
          $v->save();
        }
        $patient->save();
        DB::commit();
      }catch (\PDOException $e){
        DB::rollBack();
        return redirect()->back()->with("error","An error happened during deleting patient".$e->getMessage());
      }
      $log = new UserLog;
      $log->affected_row= $patient->id;
      $log->affected_table="patients";
      $log->process_type="recover";
      $log->description="has recovered a deleted patient ";
      $log->user_id=Auth::user()->id;
      $log->save();
      return redirect()->route('profilePatient',['id'=>$patient->id])->with('success','The patient successfully recovered');
    }else {
      return view('errors.404');
    }
  }
  public function recoverDiagnose($id)
  {
    if(Auth::user()->role==1){
      $diagnose=Diagnose::findOrFail($id);
      if($diagnose->patient->deleted==1){
        return redirect()->back()->with('error',"Sorry but this diagnosis belongs to a deleted <a href='".route('profilePatient',['id'=>$diagnose->patient->id])."'>Diagnosis</a>, recover this patient first if you want to proceed <a href='".route('recoverPatient',['id'=>$diagnose->patient_id])."'>recover now!</a>");
      }
      $diagnose->deleted=0;
      $saved=$diagnose->save();
      if(!$saved){
        return redirect()->back()->with('error','A server error happened during recovering a diagnosis<br> Please try again later');
      }
      $log = new UserLog;
      $log->affected_row= $diagnose->id;
      $log->affected_table="diagnoses";
      $log->process_type="recover";
      $log->description="has recovered a deleted diagnosis ";
      $log->user_id=Auth::user()->id;
      $log->save();
      return redirect()->back()->with('success','The diagnosis successfully recovered');
    }else {
      return view('errors.404');
    }
  }
  public function recoverDrug($id)
  {
    if(Auth::user()->role==1){
      $drug= Drug::findOrFail($id);
      $drug->deleted=0;
      $saved=$drug->save();
      if(!$saved){
        return redirect()->back()->with('error',"A server error happened during recovering a medication to the system<br>Please try again later");
      }
      $log = new UserLog;
      $log->affected_table="drugs";
      $log->affected_row=$drug->id;
      $log->process_type="recover";
      $log->description="has recovered a deleted medication back to the system";
      $log->user_id=Auth::user()->id;
      $log->save();
      return redirect()->back()->with('success','The medication successfully recovered');
    }else {
      return view('errors.404');
    }
  }
  public function recoverUser($id)
  {
    if(Auth::user()->role==1){
      $user = User::findOrFail($id);
      $user->deleted=0;
      $saved = $user->save();
      if(!$saved){
        return redirect()->back()->with('error',"A server error happened during recovering a user<br>Please try again later");
      }
      $log = new UserLog;
      $log->affected_table="users";
      $log->affected_row=$user->id;
      $log->process_type="recover";
      $log->description="has recovered a deleted user";
      $log->user_id=Auth::user()->id;
      $log->save();
      return redirect()->back()->with('success','The user successfully recovered');
    }else {
      return view('errors.404');
    }
  }
  public function recoverWorkingTime($id)
  {
    if(Auth::user()->role==1){
      $working_time = WorkingTime::findOrFail($id);
      $working_time->deleted=0;
      $saved = $working_time->save();
      if(!$saved){
        return redirect()->back()->with('error',"A server error happened during recovering a working time<br>Please try again later");
      }
      $log = new UserLog;
      $log->affected_table="working_times";
      $log->affected_row=$working_time->id;
      $log->process_type="recover";
      $log->description="has recovered a deleted working time";
      $log->user_id=Auth::user()->id;
      $log->save();
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
      $tooth= Tooth::findOrFail($id);
      $deleted=$tooth->delete();
      if(!$deleted){
        return redirect()->back()->with('error','A server error happended during deleting a tooth<br> Please try again later');
      }
      return redirect()->back()->with('success','the tooth is deleted successfully');
    } else {
      return view('errors.404');
    }
  }
  public function deleteAppointment($id)
  {
    if (Auth::user()->role==1) {
      $visit= Appointment::findOrFail($id);
      $deleted=$visit->delete();
      if(!$deleted){
        return redirect()->back()->with('error','A server error happended during deleting a visit<br> Please try again later');
      }
      return redirect()->back()->with('success','the visit is deleted successfully');
    } else {
      return view('errors.404');
    }
  }
  public function deletePatient($id)
  {
    if (Auth::user()->role==1) {
      $patient= Patient::findOrFail($id);
      $diagnoses = $patient->diagnoses;
      $teeth = $patient->teeth;
      $xrays = $patient->oral_radiologies;
      $appointments = $patient->appointments;
      $diagnose_drug = $patient->diagnose_drug;
      $case_photos = $patient->cases_photos;
      try{
        DB::beginTransaction();
        $teeth->delete();
        $xrays->delete();
        $appointments->delete();
        $diagnose_drug->delete();
        $case_photos->delete();
        $diagnoses->delete();
        $patient->delete();
        DB::commit();
      }catch(\PDOException $e){
        DB::rollBack();
        return redirect()->back()->with("error","A server error happened during deleting patient<br>Please try again later");
      }
      foreach ($case_photos as $c) {
        if($c->photo!=null){
          Storage::delete($c->photo);
        }
      }
      foreach ($xrays as $x) {
        if($x->photo!=null){
          Storage::delete($x->photo);
        }
      }
      if($patient->photo!=null){
        Storage::delete($patient->photo);
      }
      return redirect()->back()->with('success',"The patient and all its related diagnosis, visits, x-rays, medicines and case photos are delted successfully");
    } else {
      return view('errors.404');
    }
  }
  public function deleteUser($id)
  {
    if (Auth::user()->role==1) {

    } else {
      return view('errors.404');
    }
  }
  public function deleteDiagnose($id)
  {
    if (Auth::user()->role==1) {
    } else {
      return view('errors.404');
    }
  }
  public function deleteDrug($id)
  {
    if (Auth::user()->role==1) {
      $drug= Drug::findOrFail($id);
      $deleted=$drug->delete();
      if(!$deleted){
        return redirect()->back()->with('error','A server error happended during deleting a medication<br> Please try again later');
      }
      return redirect()->back()->with('success','the medication is deleted successfully');
    } else {
      return view('errors.404');
    }
  }
  public function deleteWorkingTime($id)
  {
    if (Auth::user()->role==1) {
      $working_time= WorkingTime::findOrFail($id);
      $deleted=$working_time->delete();
      if(!$deleted){
        return redirect()->back()->with('error','A server error happended during deleting a working time<br> Please try again later');
      }
      return redirect()->back()->with('success','the working time is deleted successfully');
    } else {
      return view('errors.404');
    }
  }
}
