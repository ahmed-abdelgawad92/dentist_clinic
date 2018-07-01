<?php

namespace App\Http\Controllers;

use App\OralRadiology;
use Illuminate\Http\Request;
use Validator;

class OralRadiologyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        $rules= [
          'xray'=>'required|image|mimes:jpeg,png,jpg,gif',
          'xray_description'=>'string'
        ];
        $error_messages= [
          'xray.required'=>'You can\'t save an empty dental X-ray',
          'xray.mimes'=>'The Dental X-ray must be one of these types: JPEG, JPG, PNG or GIF'
        ];
        $validator = Validator::make($request->all(),$rules,$error_messages);
        if($validator->fails()){
          return redirect()->back()->withErrors($validator);
        }
        $xray = new OralRadiology;
        $xray->description = $request->xray_description;
        $xray->photo = $request->xray->store("xray");
        $xray->diagnose_id=$id;
        $saved=$xray->save();
        if(!$saved){
          return redirect()->back()->with("error","An error happenend during storing the X-ray <br /> Please try agin later");
        }
        return redirect()->route("showDiagnose",['id'=>$id])->with("success","The Dental X-ray is successfully stored ");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\OralRadiology  $oralRadiology
     * @return \Illuminate\Http\Response
     */
    public function show(OralRadiology $oralRadiology)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\OralRadiology  $oralRadiology
     * @return \Illuminate\Http\Response
     */
    public function edit(OralRadiology $oralRadiology)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\OralRadiology  $oralRadiology
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OralRadiology $oralRadiology)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\OralRadiology  $oralRadiology
     * @return \Illuminate\Http\Response
     */
    public function destroy(OralRadiology $oralRadiology)
    {
        //
    }
}
