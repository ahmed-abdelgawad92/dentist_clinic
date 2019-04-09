<?php
namespace App\Repositories;

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
        $diagnose->patient_id=$id;
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
}