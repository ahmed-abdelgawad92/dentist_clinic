<?php

namespace App\Http\Controllers;

use Auth;
use App\UserLog;
use App\Diagnose;
use App\OralRadiology;
use Illuminate\Http\Request;
use Validator;

use App\Http\Requests\StoreOralRadiology;

class OralRadiologyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
      $diagnose= Diagnose::id($id)->notDeleted()->firstOrFail();
      $xrays = $diagnose->oral_radiologies()->notDeleted()->orderBy("created_at","DESC")->get();
      $data=[
        'diagnose'=>$diagnose,
        'xrays'=>$xrays
      ];
      return view("xray.all",$data);
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
    public function store(StoreOralRadiology $request, $id)
    {
        $xray = new OralRadiology;
        $xray->description = $request->xray_description;
        $xray->photo = $request->xray->store("xray");
        $xray->diagnose_id=$id;
        $saved=$xray->save();
        if(!$saved){
          return redirect()->back()->with("error","An error happenend during storing the X-ray <br /> Please try agin later");
        }
        $log = new UserLog;
        $log->affected_table="oral_radiologies";
        $log->affected_row=$xray->id;
        $log->process_type="create";
        $log->description="has created a new X-ray";
        $log->user_id=Auth::user()->id;
        $log->save();
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
    public function edit($id)
    {
        $xray = OralRadiology::findOrFail($id);
        return view("xray.edit",['xray'=>$xray]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\OralRadiology  $oralRadiology
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $xray = OralRadiology::findOrFail($id);
        if($xray->description!=$request->description){
          $old_description= $xray->description;
          $xray->description = $request->description;
          $saved=$xray->save();
          if(!$saved){
            return redirect()->back()->with("error","An error happenend during editing the X-ray's description <br /> Please try agin later");
          }
          $log = new UserLog;
          $log->affected_table="oral_radiologies";
          $log->affected_row=$xray->id;
          $log->process_type="update";
          $log->description="has changed description from ".$old_description." to ".$request->description;
          $log->user_id=Auth::user()->id;
          $log->save();
          return redirect()->back()->with("success","The Dental X-ray's description is successfully edited ");
        }else {
          return view('errors.404');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\OralRadiology  $oralRadiology
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      $xray= OralRadiology::findOrFail($id);
      $xray->deleted=1;
      $deleted=$xray->save();
      if(!$deleted){
        return redirect()->back()->with('error','An Error happened during deleting this X-ray<br> Please try again later');
      }
      $log = new UserLog;
      $log->affected_table="oral_radiologies";
      $log->affected_row=$xray->id;
      $log->process_type="delete";
      $log->description="has deleted a X-ray, you still can access it from Recycle Bin";
      $log->user_id=Auth::user()->id;
      $log->save();
      return redirect()->back()->with('success','The X-ray is deleted successfully');
    }
}
