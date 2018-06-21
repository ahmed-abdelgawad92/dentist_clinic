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
        //
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
        //
        echo "Inserted Correctly";
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Diagnose  $diagnose
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Diagnose  $diagnose
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
