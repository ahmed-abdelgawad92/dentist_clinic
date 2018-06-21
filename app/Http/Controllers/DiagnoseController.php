<?php

namespace App\Http\Controllers;

use Validator;
use App\Diagnose;
use App\Patient;
use Illuminate\Http\Request;

class DiagnoseController extends Controller
{
    /**
     * Display all diagnoses of a specific patient.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        //show all diagnosis of a certain patient
        $patient = Patient::findOrFail($id);
        $diagnoses= $patient->diagnoses;
        $data=[
          "patient"=>$patient,
          "diagnoses"=>$diagnoses,
          "card_title"=>"Patient Diagnosis History"
        ];
        return view("diagnose.all",$data);
    }

    /**
    * Display all undone diagnoses of a specific patient.
    *
    * @return \Illuminate\Http\Response
    */
    public function undoneDiagnosis($id)
    {
      //show all undone diagnosis of a certain patient
      $patient = Patient::findOrFail($id);
      $diagnoses= $patient->diagnoses()->where('done', 0);
      $data=[
        "patient"=>$patient,
        "diagnoses"=>$diagnoses,
        "card_title"=>"Patient Undone Diagnosis"
      ];
      return view("diagnose.all",$data);
    }
    /**
     * Show the form for creating a new resource.
     * $id of the patient
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        //return view
        $patient = Patient::findOrFail($id);
        return view('diagnose.add',["patient"=>$patient]);
    }

    /**
     * Store a newly created resource in storage.
     * $id of the patient
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,$id)
    {
        //create rules
        $rules=[
          "diagnose"=>"required|string",
          "total_price"=>"numeric|nullable"
        ];
        //error messages
        $error_messages=[
          "diagnose.required"=>"You can't create an empty Diagnosis",
          "diagnose.string"=>"You can't create an empty Diagnosis",
          "total_price.numeric"=>"Please Enter a valid price number"
        ];
        $validator = Validator::make($request->all(),$rules,$error_messages);
        if($validator->fails()){
          return redirect()->back()->withInput()->withErrors($validator);
        }

        //store diagnosis data
        $diagnose= new Diagnose;
        $diagnose->patient_id=$id;
        $diagnose->diagnose = $request->diagnose;
        $diagnose->total_price = $request->total_price;
        $diagnose->done = 0;
        $saved=$diagnose->save();
        //check if stored correctly
        if(!$saved){
          return redirect()->back()->with("error","A server erro happened during storing the Diagnosis in the database,<br> Please try again later");
        }
        return redirect()->route("showDiagnose",["id"=>$diagnose->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Diagnose  $diagnose
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //GET THE DIAGNOSIS WITH ALL ITS RELATED DATA
        $diagnose = Diagnose::findOrFail($id);
        $appointments = $diagnose->appointments()->orderBy("date","desc")->take(3)->get();
        $drugs = $diagnose->drugs()->orderBy("created_at","desc")->take(3)->get();
        $oral_radiologies = $diagnose->oral_radiologies()->orderBy("created_at","desc")->take(3)->get();
        $patient = $diagnose->patient;
        $diagnoseArray = explode(">>>",$diagnose->diagnose);
        $data = [
          "diagnose"=>$diagnose,
          "appointments"=>$appointments,
          "drugs"=>$drugs,
          "oral_radiologies"=>$oral_radiologies,
          "patient"=>$patient,
          "diagnoseArray"=>$diagnoseArray
        ];
        return view("diagnose.show",$data);
    }

    /**
     * Add payment to a specific Diagnosis
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Diagnose  $diagnose
     * @return \Illuminate\Http\Response
     */
     public function addPayment(Request $request, $id)
     {
       $rules = ["payment"=>"required|numeric"];
       $error_messages = ["payment.required"=>"Please enter amount of payment to be paid","payment.numeric"=>"Please enter a valid payment (ONLY Numbers are allowed)"];
       $validator = Validator::make($request->payment,$rules,$error_messages);
       if($validator->fails()){
         return redirect()->back()->withInput()->withErrors($validator);
       }
       $diagnose = Diagnose::findOrFail($id);
       $maxPayment = $diagnose->total_price - $diagnose->already_payed;
       if($request->payment>$maxPayment){
         return redirect()->back()->with("error","The maximum payment should not be more than $maxPayment, The total price is ".$diagnose->total_price." EGP and the patient already paid ".$diagnose->already_payed);
       }
       $diagnose->already_payed = $request->payment;
       $saved = $diagnose->save();
       if(!$saved){
         return redirect()->back()->with("error","A server erro happened during adding payment to the Diagnosis in the database,<br> Please try again later");
       }
       return redirect()->back()->with("success","Payment is successfully added ");
     }

     /**
     * This diagnosis operation is already finished.
     *
     * @param  \App\Diagnose  $diagnose
     * @return \Illuminate\Http\Response
     */
     public function finishDiagnose($id)
     {
       $diagnose = Diagnose::findOrFail($id);
       $diagnose->done = 1;
       $saved= $diagnose->save();
       if(!$saved){
         return redirect()->back()->with("error","A server erro happened during ending this Diagnosis in the database,<br> Please try again later");
       }
       $successMsg = "Successfully finished this Diagnosis of patient \"".ucwords($diagnose->patient->pname)."\"";
       if ($diagnose->total_price!=$diagnose->already_payed) {
         $successMsg.="<br>Take in account that this Diagnosis isn't fully paid the patient paid only ".$diagnose->already_payed;
         $successMsg.=" EGP from ".$diagnose->total_price;
       }
       return redirect()->back()->with("succes",$successMsg);
     }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Diagnose  $diagnose
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //get the view to edit a Diagnosis
        $diagnose = Diagnose::findOrFail($id);
        return view("diagnose.edit",["diagnose"=>$diagnose]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Diagnose  $diagnose
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
      //create rules
      $rules=[
        "diagnose"=>"required|string",
        "total_price"=>"numeric|nullable"
      ];
      //error messages
      $error_messages=[
        "diagnose.required"=>"You can't create an empty Diagnosis",
        "diagnose.string"=>"You can't create an empty Diagnosis",
        "total_price.numeric"=>"Please Enter a valid price number"
      ];
      $validator = Validator::make($request->all(),$rules,$error_messages);
      if($validator->fails()){
        return redirect()->back()->withInput()->withErrors($validator);
      }

      //store updates of diagnosis data
      $diagnose= Diagnose::findOrFail($id);
      $diagnose->diagnose = $request->diagnose;
      $diagnose->total_price = $request->total_price;
      $saved=$diagnose->save();
      //check if updated correctly
      if(!$saved){
        return redirect()->back()->with("error","A server erro happened during storing changes to the Diagnosis in the database,<br> Please try again later");
      }
      return redirect()->route("showDiagnose",["id"=>$id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Diagnose  $diagnose
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //DELETE A DIAGNOSIS WITH ALL ITS DATA
        $diagnose = Diagnose::findOrFail($id);
        $patient = $diagnose->patient;
        $deleted = $diagnose->delete();
        if(!$deleted){
          return redirect()->back()->with("error","An error happened during deleting patient");
        }
        return redirect()->route('patientProfile',["id"=>$patient->id])->with('success','Patient deleted successfully');
    }
}
