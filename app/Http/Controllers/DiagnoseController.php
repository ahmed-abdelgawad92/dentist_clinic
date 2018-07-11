<?php

namespace App\Http\Controllers;

use Validator;
use Auth;

use App\Diagnose;
use App\UserLog;
use App\Patient;
use App\Tooth;
use App\Drug;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $diagnoses= $patient->diagnoses()->with('teeth')->paginate(15);
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
      $diagnoses= $patient->diagnoses()->where('done', 0)->with('teeth')->paginate(15);
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
          "description.*"=>"required|string",
          "diagnose_type.*"=>"required|string",
          "teeth_name.*"=>"required|string",
          "price.*"=>"required|numeric",
          "discount"=>"nullable|numeric",
          "discount_type"=>"required_with:discount"
        ];
        //error messages
        $error_messages=[
          "description.*.required"=>"You can't create a Diagnosis with empty description",
          "diagnose_type.*.required"=>"You must enter the diagnosis type",
          "teeth_name.*.required"=>"Please don't try to missuse the dynamic creation process , it's only there to help you",
          "price.*.required"=>"You must enter the price of this case",
          "price.*.numeric"=>"The price must be a valid number",
          "discount.numeric"=>"Please Enter a valid Discount value (only numbers allowed)",
          "discount_type.required_with"=>"Please choose the discount type whether precentage or amount of money"
        ];
        $validator = Validator::make($request->all(),$rules,$error_messages);
        if($validator->fails()){
          // return redirect()->back()->withInput()->withErrors($validator);
          return json_encode(['state'=>'error','error'=>'Please fill the form with valid inputs','code'=>422]);
        }
        try{
          //store diagnosis data
          DB::beginTransaction();
          $diagnose= new Diagnose;
          $diagnose->patient_id=$id;
          $diagnose->done = 0;
          if(!empty($request->discount)){
            $diagnose->discount=$request->discount;
            if ($request->discount_type==0 || $request->discount_type==1) {
              $diagnose->discount_type=$request->discount_type;
            }
          }
          $diagnose->save();
          $teeth_names=$request->teeth_name;
          $diagnose_types=$request->diagnose_type;
          $descriptions=$request->description;
          $prices=$request->price;
          return json_encode(['state'=>'error','error'=>var_dump($prices),'code'=>422]);
          //store teeth
          foreach ($teeth_names as $teeth) {
            // $tooth = new Tooth;
            // $tooth->teeth_name=$teeth_names[$i];
            // $tooth->diagnose_type=$diagnose_types[$i];
            // $tooth->description=$descriptions[$i];
            // $tooth->price=$prices[$i];
            // $tooth->diagnose_id=$diagnose->id;
            // $tooth->save();
            return json_encode(['state'=>'error','error'=>'Im in','code'=>422]);
          }
          DB::commit();
        }
        catch(\PDOException $e){
          //return redirect()->back()->with("error","A server erro happened during storing the Diagnosis in the database,<br> Please try again later");
          DB::rollBack();
          return json_encode(['state'=>'error','error'=>'A server error happened during storing the Diagnosis in the database,<br> Please try again later','code'=>422]);
        }
        //return redirect()->route("showDiagnose",["id"=>$diagnose->id]);
        return json_encode(['state'=>'OK','id'=>$diagnose->id,'success'=>'The Diagnosis is successfully created','code'=>422]);
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
        $diagnose = Diagnose::where('id',$id)->where('deleted',0)->firstOrFail();
        $appointments = $diagnose->appointments()->where('deleted',0)->orderBy("date","desc")->take(3)->get();
        $drugs = $diagnose->drugs()->where('diagnose_drug.deleted',0)->orderBy("created_at","desc")->get();
        $oral_radiologies = $diagnose->oral_radiologies()->where('deleted',0)->orderBy("created_at","desc")->take(5)->get();
        $patient = $diagnose->patient;
        $diagnoseArray = explode("**",substr($diagnose->diagnose,2));
        $svg = $this->svgCreate($diagnose->diagnose);
        $allDrugs= Drug::distinct('name')->orderBy('name')->select('name')->get();
        $data = [
          "diagnose"=>$diagnose,
          "appointments"=>$appointments,
          "drugs"=>$drugs,
          "oral_radiologies"=>$oral_radiologies,
          "patient"=>$patient,
          "diagnoseArray"=>$diagnoseArray,
          "allDrugs"=>$allDrugs,
          "svg"=>$svg
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
       $validator = Validator::make($request->all(),$rules,$error_messages);
       if($validator->fails()){
         return redirect()->back()->withInput()->withErrors($validator);
       }
       $diagnose = Diagnose::findOrFail($id);
       $maxPayment = $diagnose->total_price - $diagnose->already_payed;
       if($request->payment>$maxPayment){
         return redirect()->back()->with("error","The maximum payment should not be more than $maxPayment, The total price is ".$diagnose->total_price." EGP and the patient already paid ".$diagnose->already_payed);
       }
       $diagnose->already_payed += $request->payment;
       $saved = $diagnose->save();
       if(!$saved){
         return redirect()->back()->with("error","A server erro happened during adding payment to the Diagnosis in the database,<br> Please try again later");
       }
       return redirect()->back()->with("success","Payment is successfully added ");
     }
    /**
     * Add payment to a specific Diagnosis
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Diagnose  $diagnose
     * @return \Illuminate\Http\Response
     */
     public function addTotalPrice(Request $request, $id)
     {
       $rules = ["total_price"=>"required|numeric"];
       $error_messages = ["total_price.required"=>"Please enter Total Price of this diagnosis","total_price.numeric"=>"Please enter a valid price (ONLY Numbers are allowed)"];
       $validator = Validator::make($request->all(),$rules,$error_messages);
       if($validator->fails()){
         return redirect()->back()->withInput()->withErrors($validator);
       }
       $diagnose = Diagnose::findOrFail($id);
       $diagnose->total_price = $request->total_price;
       $saved = $diagnose->save();
       if(!$saved){
         return redirect()->back()->with("error","A server erro happened during adding payment to the Diagnosis in the database,<br> Please try again later");
       }
       return redirect()->back()->with("success","Total price is successfully added ");
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
         $successMsg.=" <br> Take into account that this Diagnosis isn't fully paid the patient paid only ".$diagnose->already_payed;
         $successMsg.=" EGP from ".$diagnose->total_price."<br>";
         $successMsg.='<button class="btn btn-success action"  data-action="#add_payment" data-url="/patient/diagnosis/'.$diagnose->id.'/add/payment">Add Payment Now</button>';
       }
       return redirect()->back()->with("success",$successMsg);
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
        return redirect()->back()->with('success','Diagnosis deleted successfully');
    }

    public function svgCreate($diagnose)
    {
      $svg = "";
      if(strpos($diagnose,"{{1}}")!==false){
        $svg.='<circle cx="92" cy="324" r="25" stroke="black" stroke-width="3" fill="red" opacity="0.6"/>';
      }
      if(strpos($diagnose,"{{2}}")!==false){
        $svg.='<circle cx="95" cy="274" r="26" stroke="black" stroke-width="3" fill="red" opacity="0.6"/>';
      }
      if(strpos($diagnose,"{{3}}")!==false){
        $svg.='<circle cx="102" cy="227" r="26" stroke="black" stroke-width="3" fill="red" opacity="0.6"/>';
      }
      if(strpos($diagnose,"{{4}}")!==false){
        $svg.='<circle cx="115" cy="180" r="25" stroke="black" stroke-width="3" fill="red" opacity="0.6"/>';
      }
      if(strpos($diagnose,"{{5}}")!==false){
        $svg.='<circle cx="136" cy="138" r="24" stroke="black" stroke-width="3" fill="red" opacity="0.6"/>';
      }
      if(strpos($diagnose,"{{6}}")!==false){
        $svg.='<circle cx="162" cy="104" r="20" stroke="black" stroke-width="3" fill="red" opacity="0.6"/>';
      }
      if(strpos($diagnose,"{{7}}")!==false){
        $svg.='<circle cx="187" cy="76" r="20" stroke="black" stroke-width="3" fill="red" opacity="0.6"/>';
      }
      if(strpos($diagnose,"{{8}}")!==false){
        $svg.='<circle cx="226" cy="57" r="24" stroke="black" stroke-width="3" fill="red" opacity="0.6"/>';
      }
      if(strpos($diagnose,"{{9}}")!==false){
        $svg.='<circle cx="271" cy="57" r="23" stroke="black" stroke-width="3" fill="red" opacity="0.6"/>';
      }
      if(strpos($diagnose,"{{10}}")!==false){
        $svg.='<circle cx="311" cy="75" r="24" stroke="black" stroke-width="3" fill="red" opacity="0.6"/>';
      }
      if(strpos($diagnose,"{{11}}")!==false){
        $svg.='<circle cx="337" cy="104" r="21" stroke="black" stroke-width="3" fill="red" opacity="0.6"/>';
      }
      if(strpos($diagnose,"{{12}}")!==false){
        $svg.='<circle cx="361" cy="137" r="23" stroke="black" stroke-width="3" fill="red" opacity="0.6"/>';
      }
      if(strpos($diagnose,"{{13}}")!==false){
        $svg.='<circle cx="382" cy="181" r="25" stroke="black" stroke-width="3" fill="red" opacity="0.6"/>';
      }
      if(strpos($diagnose,"{{14}}")!==false){
        $svg.='<circle cx="395" cy="226" r="24" stroke="black" stroke-width="3" fill="red" opacity="0.6"/>';
      }
      if(strpos($diagnose,"{{15}}")!==false){
        $svg.='<circle cx="402" cy="275" r="24" stroke="black" stroke-width="3" fill="red" opacity="0.6"/>';
      }
      if(strpos($diagnose,"{{16}}")!==false){
        $svg.='<circle cx="404" cy="323" r="25" stroke="black" stroke-width="3" fill="red" opacity="0.6"/>';
      }
      if(strpos($diagnose,"{{17}}")!==false){
        $svg.='<circle cx="401" cy="397" r="28" stroke="black" stroke-width="3" fill="red" opacity="0.6"/>';
      }
      if(strpos($diagnose,"{{18}}")!==false){
        $svg.='<circle cx="398" cy="451" r="26" stroke="black" stroke-width="3" fill="red" opacity="0.6"/>';
      }
      if(strpos($diagnose,"{{19}}")!==false){
        $svg.='<circle cx="388" cy="502" r="27" stroke="black" stroke-width="3" fill="red" opacity="0.6"/>';
      }
      if(strpos($diagnose,"{{20}}")!==false){
        $svg.='<circle cx="370" cy="553" r="27" stroke="black" stroke-width="3" fill="red" opacity="0.6"/>';
      }
      if(strpos($diagnose,"{{21}}")!==false){
        $svg.='<circle cx="345" cy="594" r="25" stroke="black" stroke-width="3" fill="red" opacity="0.6"/>';
      }
      if(strpos($diagnose,"{{22}}")!==false){
        $svg.='<circle cx="318" cy="625" r="17" stroke="black" stroke-width="3" fill="red" opacity="0.6"/>';
      }
      if(strpos($diagnose,"{{23}}")!==false){
        $svg.='<circle cx="293" cy="642" r="14" stroke="black" stroke-width="3" fill="red" opacity="0.6"/>';
      }
      if(strpos($diagnose,"{{24}}")!==false){
        $svg.='<circle cx="263" cy="649" r="16" stroke="black" stroke-width="3" fill="red" opacity="0.6"/>';
      }
      if(strpos($diagnose,"{{25}}")!==false){
        $svg.='<circle cx="233" cy="648" r="15" stroke="black" stroke-width="3" fill="red" opacity="0.6"/>';
      }
      if(strpos($diagnose,"{{26}}")!==false){
        $svg.='<circle cx="202" cy="641" r="17" stroke="black" stroke-width="3" fill="red" opacity="0.6"/>';
      }
      if(strpos($diagnose,"{{27}}")!==false){
        $svg.='<circle cx="179" cy="625" r="19" stroke="black" stroke-width="3" fill="red" opacity="0.6"/>';
      }
      if(strpos($diagnose,"{{28}}")!==false){
        $svg.='<circle cx="153" cy="594" r="23" stroke="black" stroke-width="3" fill="red" opacity="0.6"/>';
      }
      if(strpos($diagnose,"{{29}}")!==false){
        $svg.='<circle cx="127" cy="553" r="27" stroke="black" stroke-width="3" fill="red" opacity="0.6"/>';
      }
      if(strpos($diagnose,"{{30}}")!==false){
        $svg.='<circle cx="108" cy="503" r="27" stroke="black" stroke-width="3" fill="red" opacity="0.6"/>';
      }
      if(strpos($diagnose,"{{31}}")!==false){
        $svg.='<circle cx="99" cy="451" r="27" stroke="black" stroke-width="3" fill="red" opacity="0.6"/>';
      }
      if(strpos($diagnose,"{{32}}")!==false){
        $svg.='<circle cx="94" cy="397" r="28" stroke="black" stroke-width="3" fill="red" opacity="0.6"/>';
      }
      if(stripos($diagnose,"{{A}}")!==false){
        $svg.='<circle cx="170" cy="226" r="18" stroke="black" stroke-width="3" fill="red" opacity="0.6"/>';
      }
      if(stripos($diagnose,"{{B}}")!==false){
        $svg.='<circle cx="178" cy="193" r="16" stroke="black" stroke-width="3" fill="red" opacity="0.6"/>';
      }
      if(stripos($diagnose,"{{C}}")!==false){
        $svg.='<circle cx="193" cy="169" r="16" stroke="black" stroke-width="3" fill="red" opacity="0.6"/>';
      }
      if(stripos($diagnose,"{{D}}")!==false){
        $svg.='<circle cx="209" cy="147" r="13" stroke="black" stroke-width="3" fill="red" opacity="0.6"/>';
      }
      if(stripos($diagnose,"{{E}}")!==false){
        $svg.='<circle cx="232" cy="132" r="15" stroke="black" stroke-width="3" fill="red" opacity="0.6"/>';
      }
      if(stripos($diagnose,"{{F}}")!==false){
        $svg.='<circle cx="263" cy="133" r="16" stroke="black" stroke-width="3" fill="red" opacity="0.6"/>';
      }
      if(stripos($diagnose,"{{G}}")!==false){
        $svg.='<circle cx="287" cy="147" r="15" stroke="black" stroke-width="3" fill="red" opacity="0.6"/>';
      }
      if(stripos($diagnose,"{{H}}")!==false){
        $svg.='<circle cx="303" cy="170" r="13" stroke="black" stroke-width="3" fill="red" opacity="0.6"/>';
      }
      if(stripos($diagnose,"{{I}}")!==false){
        $svg.='<circle cx="318" cy="194" r="17" stroke="black" stroke-width="3" fill="red" opacity="0.6"/>';
      }
      if(stripos($diagnose,"{{J}}")!==false){
        $svg.='<circle cx="326" cy="227" r="18" stroke="black" stroke-width="3" fill="red" opacity="0.6"/>';
      }
      if(stripos($diagnose,"{{K}}")!==false){
        $svg.='<circle cx="329" cy="480" r="20" stroke="black" stroke-width="3" fill="red" opacity="0.6"/>';
      }
      if(stripos($diagnose,"{{L}}")!==false){
        $svg.='<circle cx="317" cy="515" r="19" stroke="black" stroke-width="3" fill="red" opacity="0.6"/>';
      }
      if(stripos($diagnose,"{{M}}")!==false){
        $svg.='<circle cx="303" cy="549" r="15" stroke="black" stroke-width="3" fill="red" opacity="0.6"/>';
      }
      if(stripos($diagnose,"{{N}}")!==false){
        $svg.='<circle cx="283" cy="569" r="14" stroke="black" stroke-width="3" fill="red" opacity="0.6"/>';
      }
      if(stripos($diagnose,"{{O}}")!==false){
        $svg.='<circle cx="260" cy="577" r="14" stroke="black" stroke-width="3" fill="red" opacity="0.6"/>';
      }
      if(stripos($diagnose,"{{P}}")!==false){
        $svg.='<circle cx="236" cy="577" r="15" stroke="black" stroke-width="3" fill="red" opacity="0.6"/>';
      }
      if(stripos($diagnose,"{{Q}}")!==false){
        $svg.='<circle cx="211" cy="570" r="13" stroke="black" stroke-width="3" fill="red" opacity="0.6"/>';
      }
      if(stripos($diagnose,"{{R}}")!==false){
        $svg.='<circle cx="193" cy="548" r="16" stroke="black" stroke-width="3" fill="red" opacity="0.6"/>';
      }
      if(stripos($diagnose,"{{S}}")!==false){
        $svg.='<circle cx="179" cy="517" r="20" stroke="black" stroke-width="3" fill="red" opacity="0.6"/>';
      }
      if(stripos($diagnose,"{{T}}")!==false){
        $svg.='<circle cx="168" cy="481" r="21" stroke="black" stroke-width="3" fill="red" opacity="0.6"/>';
      }
      return $svg;
    }
}
