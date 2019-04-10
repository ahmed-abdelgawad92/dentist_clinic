<?php
namespace App\Repositories;

use App\Patient;

class PatientRepository
{  
    //get patient by id
    public function get($id)
    {
        return Patient::findOrFail($id);
    }

    //get all undone diagnosis with their teeth
    public function allUndoneWithTeeth($id)
    {   
        $patient = Patient::findOrFail($id);
        return $patient->diagnoses()->notDone()->with('teeth')->paginate(15);
    } 

    //search patient 
    public function search($search)
    {
        return Patient::search($search)->paginate(15);
    }

    //paginate 
    public function paginate($limit)
    {
        return Patient::paginate($limit);
    }

    //create patient
    public function create($request)
    {
        $patient = new Patient;
        $patient->pname = mb_strtolower($request->pname);
        $patient->gender = mb_strtolower($request->gender);
        $patient->dob = date("Y-m-d",strtotime("-".$request->dob." year",time()));
        $patient->address = mb_strtolower($request->address);
        $patient->phone = mb_strtolower($request->phone);
        $patient->diabetes = mb_strtolower($request->diabetes);
        $patient->blood_pressure = mb_strtolower($request->blood_pressure);
        $patient->medical_compromise = mb_strtolower($request->medical_compromise);
        if($request->hasFile("photo")){
          $patient->photo=$request->photo->store("patient_profile");
        }
        $saved = $patient->save();
        if(!$saved){
          return redirect()->back()->withInput()->with("insert_error","A server error happened during creating a new patient <br /> please try again later");
        }
        return $patient;
    }

    //update patient 
    public function update($id, $request)
    {
        $patient = Patient::findOrFail($id);
        $patient->pname = mb_strtolower($request->pname);
        $patient->gender = mb_strtolower($request->gender);
        $patient->dob = date("Y-m-d",strtotime("-".$request->dob." year",time()));
        $patient->address = mb_strtolower($request->address);
        $patient->phone = mb_strtolower($request->phone);
        $patient->diabetes = mb_strtolower($request->diabetes);
        $patient->blood_pressure = mb_strtolower($request->blood_pressure);
        $patient->medical_compromise = mb_strtolower($request->medical_compromise);

        $saved = $patient->save();
        if(!$saved){
            return redirect()->back()->withInput()->with("insert_error","A server error happened during updating \"".$patient->pname."\" <br /> please try again later");
        }
    }

    //delete patient
    public function delete($id)
    {
        $patient = Patient::findOrFail($id);
        $diagnoses=$patient->diagnoses;
        $teeth=$patient->teeth;
        $visits=$patient->appointments;
        $diagnose_drug=$patient->diagnose_drug;
        $xrays=$patient->oral_radiologies;
        $case_photos=$patient->cases_photos;
        try{
          DB::beginTransaction();
          $patient->deleted=1;
          foreach ($xrays as $x) {
            $x->deleted=1;
            $x->save();
          }
          foreach ($case_photos as $c) {
            $c->deleted=1;
            $c->save();
          }
          foreach ($diagnoses as $d) {
            $d->deleted=1;
            $d->save();
          }
          foreach ($teeth as $t) {
            $t->deleted=1;
            $t->save();
          }
          foreach ($diagnose_drug as $dr) {
            $dr->deleted=1;
            $dr->save();
          }
          foreach ($visits as $v) {
            $v->deleted=1;
            $v->save();
          }
          $patient->save();
          DB::commit();
          if($patient->photo!=null){
            Storage::delete($patient->photo);
            $patient->photo=null;
            $patient->save();
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
        }catch (\PDOException $e){
          DB::rollBack();
          return redirect()->back()->with("error","An error happened during deleting patient".$e->getMessage());
        }
        return $patient;
    }

    //get current diagnosis
    public function getCurrentDiagnose($id)
    {
        return Patient::findOrFail($id)->diagnoses()->notDone()->get()->last();
    }

    //number of all diagnosis
    public function numOfAllDiagnoses($id)
    {
        return Patient::findOrFail($id)->diagnoses()->count();
    }
    
    //number of undone diagnosis
    public function numOfUndoneDiagnoses($id)
    {
        return Patient::findOrFail($id)->diagnoses()->notDone()->count();
    }

    //get last visit of a patient 
    public function getLastVisit($id)
    {
        return Patient::findOrFail()->appointments()->finished()->orderBy('date','ASC')->get()->last();
    } 

    //get next visit of a patient 
    public function getNextVisit($id)
    {
        return Patient::findOrFail()->appointments()->notApproved()->orderBy('date','ASC')->get()->first();
    }

    //get total paid amount of all the diagnoses that related to the patient
    public function totalPaidAmount($id)
    {
        return Patient::findOrFail($id)->diagnoses()->sum('total_paid');
    }

    //get all Diagnosis
    public function getAllDiagnoses($id)
    {
        return Patient::findOrFail($id)->diagnoses()->get();
    }
    //get all Diagnosis with teeth
    public function getAllDiagnosesWithTeeth($id)
    {
        return Patient::findOrFail($id)->diagnoses()->with('teeth')->get();
    }

    //change photo 
    public function changePhoto($id, $url)
    {
        $patient = Patient::findOrFail($id);
        $patient->photo = $url;
        $saved=$patient->save();
        if (!$saved) {
          return redirect()->back()->withInput()->with("error","A server error happened during uploading a patient profile picture <br /> please try again later");
        }
    }

    //get all deleted records
    public function allDeleted()
    {
        return Patient::withoutGlobalScopes()->isDeleted()->get();
    }

    //recover a deleted patient with all related data which deleted at the same time 
    public function recover($id)
    {
      $patient=Patient::findOrFail($id);
      $diagnoses=$patient->diagnoses()->sameDate($patient->updated_at)->get();
      $teeth=$patient->teeth()->sameDate($patient->updated_at)->get();
      $visits=$patient->appointments()->sameDate($patient->updated_at)->get();
      $diagnose_drug=$patient->diagnose_drug()->sameDate($patient->updated_at)->get();
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
          if($dr->drug->deleted==0){
            $dr->deleted=0;
            $dr->save();
          }
        }
        foreach ($visits as $v) {
          $v->deleted=0;
          $v->save();
        }
        $patient->save();
        DB::commit();
      }catch (\PDOException $e){
        DB::rollBack();
        return redirect()->back()->with("error","An error happened during recovering patient");
      }
      return $patient;
    }

    //permanently deleting a record with all its related data 
    public function permanentDelete($id)
    {
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
      return $patient;
    }
}