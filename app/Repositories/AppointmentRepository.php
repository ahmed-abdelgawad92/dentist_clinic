<?php
namespace App\Repositories;

use App\Appointment;
use App\Patient;

class AppointmentRepository
{  
    //get by id 
    public function get($id)
    {
        return Appointment::findOrFail($id);
    }

    //get all appointment in a specific date
    public function allOnDate($date, $except_id = null)
    {
        return $except_id ? Appointment::onDate(date('Y-m-d'))->where('id','!=',$except_id)->order()->get() : Appointment::onDate(date('Y-m-d'))->order()->get();
    }

    //get all approved appointments in a specific date
    public function allApproved($date)
    {
        return Appointment::onDate(date('Y-m-d'))->approved()->with('diagnose.patient')->get();
    }

    //get all unapproved appointments in a specific date
    public function allNotApproved($date)
    {
        return Appointment::onDate(date('Y-m-d'))->notApproved()->with('diagnose.patient')->get();
    }

    //get all finished appointments in a specific date
    public function allFinished($date)
    {
        return Appointment::onDate(date('Y-m-d'))->finished()->with('diagnose.patient')->get();
    }

    //get all Appointments of a specific Diagnosis
    public function allByDiagnoseId($diagnose_id, int $take = null)
    {
        return $take ? Appointment::where('diagnose_id', $diagnose_id)->order()->take($take)->get() : Appointment::where('diagnose_id', $diagnose_id)->order()->get();
    }

    //get all appointments of a specific patient 
    public function allByPatient(Patient $patient)
    {
        return $patient->appointments()->order()->get();
    }

    //create an appointment
    public function create($data)
    {
        $visit = new Appointment;
        $visit->time= date('H:i:s',strtotime($data['time']));
        $visit->date=$data['date'];
        $visit->treatment=$data['treatment'];
        $visit->diagnose_id=$data['diagnose_id'];
        $saved= $visit->save();
        if (!$saved) {
            return redirect()->back()->with('error',"A server error happened during saving this visit");
        }
    }

    //update an appointment
    public function update($id, $data)
    {   
        $visit = Appointment::findOrFail($id);
        $description = "";
        if($visit->date!=$data['date']){
            $description.="has changed visit date from ".$visit->date." to ".$data['date'].". ";
            $visit->date=$data['date'];
        }
        if($visit->time != date('H:i:s',strtotime($data['time']))){
            $description.="has changed visit time from ".date('h:i a',strtotime($visit->date))." to ".date('h:i a',strtotime($data['time'])).". ";
            $visit->time= date('H:i:s',strtotime($data['time']));
        }
        if ($visit->treatment!=$data['treatment']) {
            $description.='has changed visit treatment from "'.$visit->treatment.'" to "'.$data['treatment'].'". ';
            $visit->treatment=$data['treatment'];
        }
        $saved= $visit->save();
        if (!$saved) {
          return redirect()->back()->with('error',"A server error happened during editing this visit");
        }
        return $description;
    }

    //delete an appointment
    public function delete($id)
    {
        $visit = Appointment::findOrFail($id);
        $visit->deleted=1;
        $saved=$visit->save();
        if(!$saved){
            return redirect()->back()->with('error','A server error happened during deleting visit, <br> Please try again later');
        }
        return $visit;
    }

    //approve an appointment
    public function approve($id)
    {
        $visit = Appointment::findOrFail($id);
        $patient= $visit->patient();
        $visit->approved=3;
        $visit->approved_time= date('Y-m-d H:i:s');
        $saved= $visit->save();
        if(!$saved){
            return redirect()->back()->with('error','A server error happened during approving visit, <br> Please try again later');
        }

        $patient= $visit->patient();
        $otherApprovedVisits=$patient->appointments()->where('appointments.approved',3)->where('appointments.deleted',0)->where('appointments.id',"!=",$id)->get();
        if ($otherApprovedVisits->count()>0) {
            foreach ($otherApprovedVisits as $v) {
            $v->approved=1;
            $v->save();
            }
        }

        return $visit;
    }

    //cancel appointment
    public function cancel($id)
    {
        $visit = Appointment::findOrFail($id);
        $visit->approved=0;
        $visit->approved_time= date('Y-m-d H:i:s');
        $saved= $visit->save();
        if(!$saved){
            return redirect()->back()->with('error','A server error happened during cancelling visit, <br> Please try again later');
        }
        return $visit;
    }

    //end an appointment 
    public function finish($id)
    {
        $visit = Appointment::findOrFail($id);
        $visit->approved=1;
        $visit->approved_time= date('Y-m-d H:i:s');
        $saved= $visit->save();
        if(!$saved){
            return redirect()->back()->with('error','A server error happened during approving visit, <br> Please try again later');
        }
        return $visit;
    }
}
