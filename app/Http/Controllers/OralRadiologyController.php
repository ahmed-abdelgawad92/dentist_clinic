<?php

namespace App\Http\Controllers;

use Auth;
use App\UserLog;
use App\Diagnose;
use App\OralRadiology;
use Illuminate\Http\Request;
use Validator;

use App\Http\Requests\StoreOralRadiology;

use App\Repositories\UserLogRepository;
use App\Repositories\DiagnoseRepository;
use App\Repositories\OralRadiologyRepository;

class OralRadiologyController extends Controller
{
    protected $userlog;
    protected $xray;
    protected $diagnose;

    public function __construct(
      UserLogRepository $userlog,
      OralRadiologyRepository $xray,
      DiagnoseRepository $diagnose
    )
    {
        $this->userlog = $userlog;
        $this->xray = $xray;
        $this->diagnose = $diagnose;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
      $diagnose= $this->diagnose->get($id);
      $xrays = $this->diagnose->getAllXrays($id);
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
        $xray = $this->xray->create([
          'description' => $request->xray_description,
          'photo' => $request->xray->store("xray"),
          'diagnose_id'=> $id
        ]);
        $log['table']="oral_radiologies";
        $log['id']=$xray->id;
        $log['action']="create";
        $log['description']="has created a new X-ray";
        $this->userlog->create($log);
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
        $xray = $this->xray->get($id);
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
      $xray = $this->xray->get($id);
      if($xray->description!=$request->description){
          $old_description= $xray->description;
          $this->xray->update($id, $request->description);
          $log['table']="oral_radiologies";
          $log['id']=$xray->id;
          $log['action']="update";
          $log['description']="has changed description from ".$old_description." to ".$request->description;
          $this->userlog->create($log);
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
      $xray= $this->xray->delete($id);
      $log['affected_table']="oral_radiologies";
      $log['affected_row']=$xray->id;
      $log['process_type']="delete";
      $log['description']="has deleted a X-ray, you still can access it from Recycle Bin";
      $this->userlog->create($log);
      return redirect()->back()->with('success','The X-ray is deleted successfully');
    }
}
