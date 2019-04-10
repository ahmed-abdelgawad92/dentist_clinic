<?php
namespace App\Repositories;

use Illuminate\Support\Facades\Storage;
use App\Diagnose;

class DiagnoseRepository
{  
    //get a specific diagnosis with id
    public function get($id)
    {
        return Diagnose::findOrFail($id);
    }
    //get a specific diagnosis with id
    public function getUndone($id)
    {
        return Diagnose::id($id)->notDone()->firstOrFail();
    }

    //create a diagnosis
    public function create($data)
    {
        $diagnose= new Diagnose;
        $diagnose->patient_id=$data['id'];
        $diagnose->done = 0;
        if(!empty($data['discount'])){
            $diagnose->discount=$data['discount'];
            if ($data['discount_type']==0 || $data['discount_type']==1) {
                $diagnose->discount_type=$data['discount_type'];
            }
        }
        $diagnose->save();
        return $diagnose;
    }

    //delete diagnose with all its related data 
    public function delete($id)
    {
        $diagnose = Diagnose::findOrFail($id);
        $patient = $diagnose->patient;
        $teeth=$diagnose->teeth;
        $visits=$diagnose->appointments;
        $drugs=$diagnose->diagnose_drug;
        $xrays=$diagnose->oral_radiologies;
        $case_photos=$diagnose->cases_photos;
        try{
          DB::beginTransaction();
          $diagnose->deleted=1;
          foreach ($xrays as $x) {
            Storage::delete($x->photo);
            $x->delete();
          }
          foreach ($case_photos as $c) {
            Storage::delete($c->photo);
            $c->delete();
          }
          foreach ($teeth as $t) {
            $t->deleted=1;
            $t->save();
          }
          foreach ($drugs as $dr) {
            $dr->deleted=1;
            $dr->save();
          }
          foreach ($visits as $v) {
            $v->deleted=1;
            $v->save();
          }
          $diagnose->save();
          DB::commit();
        }catch(\PDOException $e){
          DB::rollBack();
          return redirect()->back()->with("error","An error happened during deleting diagnosis".$e->getMessage());
        }
        return $patient;
    }

    //get all diagnoses with their teeth
    public function allWithTeeth()
    {
        return Diagnose::orderBy('created_at','DESC')->with('teeth')->get();
    }
    //get patient  of a specific diagnose
    public function getPatient($id)
    {
        return Diagnose::findOrFail($id)->patient;
    }

    //get all drugs  of a specific diagnose
    public function getAllDrugs($id, $take = null)
    {
        $diagnose = Diagnose::findOrFail($id);
        return $take ? $diagnose->drugs()->orderBy("created_at","desc")->take($take)->get() : $diagnose->drugs()->orderBy("created_at","desc")->get();
    }

    //get all oral_radiologies of a specific diagnose
    public function getAllXrays($id, $take = null)
    {
        $diagnose = Diagnose::findOrFail($id);
        return $take ? $diagnose->oral_radiologies()->orderBy("created_at","desc")->take($take)->get() : $diagnose->oral_radiologies()->orderBy("created_at","desc")->get();
    }
    
    //get all teeth of a specific diagnose
    public function getAllTeeth($id, $take = null)
    {
        $diagnose = Diagnose::findOrFail($id);
        return $take ? $diagnose->teeth()->take($take)->get() : $diagnose->teeth()->get();
    }

    public function getAllCasePhotos($id)
    {
        return Diagnose::findOrFail($id)->cases_photos()->get();
    }

    // get total price of all teeth within a diagnose
    public function totalPrice($id)
    {
        return Diagnose::findOrFail($id)->teeth()->sum('price');
    }

    // add payment to the diagnose 
    public function addPayment($id, $payment)
    {
        $diagnose = Diagnose::findOrFail($id);
        $diagnose->total_paid += $payment;
        $saved = $diagnose->save();
        if(!$saved){
            return redirect()->back()->with("error","A server erro happened during adding payment to the Diagnosis in the database,<br> Please try again later");
        }
    }

    //add discount
    public function addDiscount($id, $data)
    {
        $diagnose= Diagnose::findOrFail($id);
        $diagnose->discount = $data['discount'];
        $diagnose->discount_type = $data['discount_type'];
        $saved = $diagnose->save();
        if(!$saved){
            return redirect()->back()->with("error","A server erro happened during adding discount to the Diagnosis in the database,<br> Please try again later");
        }
    }

    //finish diagnose 
    public function finish($id)
    {
        $diagnose = Diagnose::findOrFail($id);
        $diagnose->done = 1;
        $saved= $diagnose->save();
        if(!$saved){
            return redirect()->back()->with("error","A server erro happened during ending this Diagnosis in the database,<br> Please try again later");
        }
        return $diagnose;
    }

    //get all deleted records 
    public function allDeleted()
    {
        return Diagnose::withoutGlobalScopes()->isDeleted()->get();
    }

    //recover a deleted diagnose with all its related data that are deleted at the same day
    public function recover($id)
    {
        $diagnose=Diagnose::findOrFail($id);
        if($diagnose->patient->deleted==1){
            return redirect()->back()->with('error',"Sorry but this diagnosis belongs to a deleted Patient, recover this patient first if you want to proceed <a class='btn btn-success' href='".route('recoverPatient',['id'=>$diagnose->patient_id])."'>recover now!</a>");
        }
        $teeth=$diagnose->teeth()->sameDate($diagnose->updated_at)->get();
        $visits=$diagnose->appointments()->sameDate($diagnose->updated_at)->get();
        $diagnose_drug=$diagnose->diagnose_drug()->sameDate($diagnose->updated_at)->get();
        try{
            DB::beginTransaction();
            $diagnose->deleted=0;
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
            $diagnose->save();
            DB::commit();
        }catch (\PDOException $e){
            DB::rollBack();
            return redirect()->back()->with("error","An error happened during recovering diagnosis");
        }
        return $diagnose;
    }

    //permanently deleting record
    public function permanentDelete($id)
    {
        $diagnose= Diagnose::findOrFail($id);
        $teeth = $diagnose->teeth;
        $xrays = $diagnose->oral_radiologies;
        $appointments = $diagnose->appointments;
        $diagnose_drug = $diagnose->diagnose_drug;
        $case_photos = $diagnose->cases_photos;
        try{
            DB::beginTransaction();
            $teeth->delete();
            $xrays->delete();
            $appointments->delete();
            $diagnose_drug->delete();
            $case_photos->delete();
            $diagnose->delete();
            DB::commit();
        }catch(\PDOException $e){
            DB::rollBack();
            return redirect()->back()->with("error","A server error happened during deleting diagnosis<br>Please try again later");
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
    }
}