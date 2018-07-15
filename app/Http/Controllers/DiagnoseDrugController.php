<?php

namespace App\Http\Controllers;

use Auth;
use Validator;
use App\Drug;
use App\UserLog;
use App\Diagnose;
use App\DiagnoseDrug;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DiagnoseDrugController extends Controller
{
    /**
     * Display a listing of the resource.
     * $id of diagnose
     * @return \Illuminate\Http\Response
     *
     * SHOW ALL DRUGS OF SPECIFIC DIAGNOSE
     */
    public function index($id)
    {
        $diagnose = Diagnose::findOrFail($id);
        $drugs = $diagnose->drugs()->where('diagnose_drug.deleted',0)->orderBy("name")->get();
        $data=[
          "diagnose"=>$diagnose,
          "drugs"=>$drugs
        ];
        return view("drug.prescription", $data);
    }

    /**
     * Show the form for creating a new resource.
     * $id of diagnose
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     * $id of diagnose
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,$id)
    {
      //create rules
      $rules=[
        "drug.*"=>"nullable|string|unique:drugs,name",
        "drug_list.*"=>"nullable|string",
        "dose.*"=>"string"
      ];
      $error_messages=[
        'drug.*.unique'=>':input already exists in the database, better choose it from the list',
        'dose.*.string'=>'one of the dose is empty'
      ];
      $validator = Validator::make($request->all(),$rules,$error_messages);
      if($validator->fails()){
        return redirect()->back()->withInput()->withErrors($validator);
      }

      //store drug data
      $diagnose = Diagnose::findOrFail($id);
      try {
        DB::beginTransaction();
        for ($i=0; $i < count($request->dose); $i++) {
          $drug_list=$request->drug_list[$i];
          $drug_input=$request->drug[$i];
          $dose=$request->dose[$i];
          if ($drug_list!="") {
            $drug = new DiagnoseDrug;
            $drug->drug_id = $drug_list;
            $drug->diagnose_id=$id;
            $drug->dose = $dose;
            $saved=$drug->save();
            $log = new UserLog;
            $log->affected_table="diagnoses";
            $log->affected_row=$drug->id;
            $log->process_type="create";
            $log->description="has assigned medication to the prescription of a diagnosis";
            $log->user_id=Auth::user()->id;
            $log->save();
          }elseif ($drug_input!="") {
            $drug= new Drug;
            $drug->name = $drug_input;
            $drug->save();
            $log = new UserLog;
            $log->affected_table="drugs";
            $log->affected_row=$drug->id;
            $log->process_type="create";
            $log->description="has created a new medicine in the system";
            $log->user_id=Auth::user()->id;
            $log->save();
            $drug_diagnose=new DiagnoseDrug;
            $drug_diagnose->diagnose_id=$id;
            $drug_diagnose->drug_id=$drug->id;
            $drug_diagnose->dose=$dose;
            $drug_diagnose->save();
            $log2 = new UserLog;
            $log2->affected_table="diagnoses";
            $log2->affected_row=$drug->id;
            $log2->process_type="create";
            $log2->description="has assigned medication to the prescription of a diagnosis";
            $log2->user_id=Auth::user()->id;
            $log2->save();
          }else {
            continue;
          }
        }
        DB::commit();
      }catch (\PDOException $e) {
        DB::rollBack();
        return redirect()->back()->with("error",$e->getMessage()." A server erro happened during storing medications to the Diagnosis in the database,<br> Please try again later");
      }
      return redirect()->back()->with("success","Prescription is created successfully");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Drug  $drug
     * @return \Illuminate\Http\Response
     */
    public function show(Drug $drug)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Drug  $drug
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $drugs = Drug::all();
        $drug = DiagnoseDrug::where('drug_id', $id)->firstOrFail();
        $data = [
          'drug'=>$drug,
          "drugs"=>$drugs
        ];
        return view("drug.edit",$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Drug  $drug
     * @return \Illuminate\Http\Response
     */
    // public function update(Request $request, $id)
    // {
    //   //create rules
    //   $rules=[
    //     "drug"=>"string",
    //     "dose"=>"string"
    //   ];
    //   $validator = Validator::make($request->all(),$rules);
    //   if($validator->fails()){
    //     return redirect()->back()->withInput()->withErrors($validator);
    //   }
    //   //store drug data
    //   $drug = DiagnoseDrug::findOrFail($id);
    //   $drug->drug=$request->drug;
    //   $drug->dose =$request->dose;
    //   $saved=$drug->save();
    //   //check if updated correctly
    //   if(!$saved){
    //     return redirect()->back()->with("error","A server erro happened during storing changes to the Diagnosis in the database,<br> Please try again later");
    //   }
    //   return redirect()->back()->with("success","Prescription is created successfully");
    // }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Drug  $drug
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $drug = DiagnoseDrug::findOrFail($id);
        $drug->deleted=1;
        $deleted=$drug->save();
        if(!$deleted){
          return redirect()->back()->with("error","An error happened during deleting this drug,<br> Please try again later");
        }
        $log = new UserLog;
        $log->affected_table="diagnoses";
        $log->affected_row= $drug->diagnose->id;
        $log->process_type="delete";
        $log->description= "has removed the medicine ".$drug->drug->name." from prescription";
        $log->user_id=Auth::user()->id;
        $log->save();
        return redirect()->back()->with("success","The drug is deleted successfully");
    }
}
