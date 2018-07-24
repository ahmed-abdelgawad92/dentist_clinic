<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Auth;
use Validator;

use App\Drug;
use App\UserLog;

class DrugController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $drugs = Drug::where('deleted',0)->paginate(30);
        $data =[
          'drugs'=>$drugs
        ];
        return view("drug.all",$data);
    }
    /**
     * Search for a drug
     *
     * @return \Illuminate\Http\Response
     */
    public function searchDrug(Request $request)
    {
      if(empty($request->search_drug)){
        return json_encode(['state'=>"NOK",'error'=>"Please enter a medicine name to search for it","code"=>422]);
      }
      $drugs = Drug::where('deleted',0)->where('name','like','%'.$request->search_drug.'%')->get();
      if($drugs->count()>0){
        return json_encode(['state'=>'OK','drugs'=>$drugs,"code"=>422]);
      }
      return json_encode(['state'=>"NOK",'error'=>'There is no medicine called "'.$request->search_drug.'"',"code"=>422]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("drug.systemAdd");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(count($request->drug)>0){
          $empty=true;
          foreach ($request->drug as $value) {
            if(!empty($value)){
              $empty=false;
            }
          }
          if($empty){
            return redirect()->back()->with('error',"You can't create an empty medicine<br> Please Enter Medicines' names before you click on create");
          }
          $rules = [
            'drug.*'=>'nullable|distinct|unique:drugs,name'
          ];
          $error_messages=[
            'drug.*.distinct'=>"Please don't repeat medicine's names",
            'drug.*.unique'=>"some medicines you already have on the database"
          ];
          $validator= Validator::make($request->all(),$rules,$error_messages);
          if($validator->fails()){
            return redirect()->back()->withErrors($validator);
          }
          try{
            $saved=true;
            DB::beginTransaction();
            foreach ($request->drug as $d) {
              if(!empty($d)){
                $drug= new Drug;
                $drug->name=$d;
                $drug->save();
                $log= new UserLog;
                $log->affected_table="drugs";
                $log->affected_row=$drug->id;
                $log->process_type="create";
                $log->description= "has created a new medicine in the system";
                $log->user_id=Auth::user()->id;
                $log->save();
              }
            }
            DB::commit();
          }catch(\PDOException $e){
            DB::rollBack();
            $saved=false;
          }
          if(!$saved){
            return redirect()->back()->with('error','A server error happened during creating medicines <br> Please try again later');
          }
          return redirect()->route("showAllSystemDrugs")->with("success","The Medicines are successfully created");
        }
        return redirect()->back()->with('error','you entered no medication to store !');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $drug = Drug::findOrFail($id);
        $drugs=Drug::all();
        $data=['drug'=>$drug];
        return view('drug.systemEdit',$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
      $rules=['drug'=>'required|unique:drugs,name'];
      $error_messages=['drug.required'=>"Please enter a medicine name to edit", 'drug.unique'=>"This Medicine's name already existed in database"];
      $validator=Validator::make($request->all(),$rules,$error_messages);
      if($validator->fails()){
        return redirect()->back()->withInput()->withErrors($validator);
      }
      $drug= Drug::findOrFail($id);
      if($drug->name!=$request->drug){
        $old_name=$drug->name;
        $drug->name= $request->drug;
        $saved = $drug->save();
        if(!$saved){
          return redirect()->back()->with('error',"A server error happened during editing the medicine's name");
        }
        $log= new UserLog;
        $log->affected_table="drugs";
        $log->affected_row=$drug->id;
        $log->process_type="update";
        $log->description="has changed medicine's name from ".$old_name." to ".$request->drug;
        $log->user_id=Auth::user()->id;
        $log->save();
        return redirect()->back()->with('success','Medicine '.$drug->name.' is successfully edited');
      }
      return redirect()->back()->with('success','There is no change to edit');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      $drug = Drug::findOrFail($id);
      $drug->deleted=1;
      $deleted=$drug->save();
      if(!$deleted){
        return redirect()->back()->with('error',"A Server error happened during deleting a medicine <br> Please try again later");
      }
      $log = new UserLog;
      $log->affected_table="drugs";
      $log->affected_row=$id;
      $log->process_type="delete";
      $log->description="has deleted a medicine called ".$drug->name." from database ";
      $log->user_id=Auth::user()->id;
      $log->save();
      return redirect()->back()->with('success',"The Medicine is successfully deleted");
    }
}
