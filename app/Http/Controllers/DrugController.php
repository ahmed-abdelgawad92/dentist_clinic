<?php

namespace App\Http\Controllers;

use Validator;
use App\Drug;
use App\Diagnose;
use Illuminate\Http\Request;

class DrugController extends Controller
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
        $drugs = $diagnose->drugs;
        $data=[
          "diagnose"=>$diagnose,
          "drugs"=>$drugs
        ];
        return view("drug.all", $data);
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
        "drug.*"=>"nullable|string",
        "drug_list.*"=>"nullable|string",
        "dose.*"=>"string"
      ];
      $validator = Validator::make($request->all(),$rules);
      if($validator->fails()){
        return redirect()->back()->withInput()->withErrors($validator);
      }

      //store drug data
      $diagnose = Diagnose::findOrFail($id);
      for ($i=0; $i < count($request->dose); $i++) {
        $drug = new Drug;
        $drug_input=$request->drug[$i];
        $drug_list=$request->drug_list[$i];
        $dose=$request->dose[$i];
        $drug->diagnose_id = $id;
        if ($drug_input!="") {
          $drug->drug = $drug_input;
        }elseif ($drug_list!="") {
          $drug->drug = $drug_list;
        }else {
          continue;
        }
        $drug->dose = $dose;
        $saved=$drug->save();
        //check if updated correctly
        if(!$saved){
          return redirect()->back()->with("error","A server erro happened during storing changes to the Diagnosis in the database,<br> Please try again later");
        }
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
        $drug = Drug::findOrFail($id);
        return view("drug.edit",['drug'=>$drug]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Drug  $drug
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
      //create rules
      $rules=[
        "drug"=>"string",
        "dose"=>"string"
      ];
      $validator = Validator::make($request->all(),$rules);
      if($validator->fails()){
        return redirect()->back()->withInput()->withErrors($validator);
      }
      //store drug data
      $drug = new Drug;
      $drug_input=$request->drug;
      $drug->dose =$request->dose;
      $saved=$drug->save();
      //check if updated correctly
      if(!$saved){
        return redirect()->back()->with("error","A server erro happened during storing changes to the Diagnosis in the database,<br> Please try again later");
      }
      return redirect()->back()->with("success","Prescription is created successfully");
    }

    /** 
     * Remove the specified resource from storage.
     *
     * @param  \App\Drug  $drug
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $drug = Drug::findOrFail($id);
        $deleted=$drug->delete();
        if(!$deleted){
          return redirect()->back()->with("error","An error happened during deleting this drug,<br> Please try again later");
        }
        return redirect()->back()->with("success","The drug is deleted successfully");
    }
}
