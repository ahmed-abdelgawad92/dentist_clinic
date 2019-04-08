<?php

namespace App\Http\Controllers;

use Validator;
use Auth;

use App\CasesPhoto;
use App\Diagnose;
use App\UserLog;
use App\Patient;
use App\Tooth;
use App\Drug;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreDiagnose;
use App\Http\Requests\EditDiagnose;
use App\Http\Requests\StoreTeeth;
use App\Http\Requests\StoreCasePhoto;
use App\Http\Requests\StorePayment;
use App\Http\Requests\AddDiscount;

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
        $diagnoses= $patient->diagnoses()->notDone()->notDeleted()->with('teeth')->paginate(15);
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
      $diagnoses= $patient->diagnoses()->notDone()->notDeleted()->with('teeth')->paginate(15);
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
    public function store(StoreDiagnose $request,$id)
    {
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
          //save log
          $log =new UserLog;
          $log->affected_table='diagnoses';
          $log->affected_row=$diagnose->id;
          $log->process_type='create';
          $log->description='has created a new diagnosis';
          $log->user_id=Auth::user()->id;
          $log->save();
          //store teeth
          $teeth_names=$request->teeth_name;
          $teeth_colors=$request->teeth_color;
          $diagnose_types=$request->diagnose_type;
          $descriptions=$request->description;
          $prices=$request->price;
          for($i=0; $i< count($teeth_names);$i++) {
            $tooth = new Tooth;
            $tooth->teeth_name=$teeth_names[$i];
            $tooth->color=$teeth_colors[$i];
            $tooth->diagnose_type=$diagnose_types[$i];
            $tooth->description=$descriptions[$i];
            $tooth->price=$prices[$i];
            $tooth->diagnose_id=$diagnose->id;
            $tooth->save();
          }
          DB::commit();
        }
        catch(\PDOException $e){
          //return redirect()->back()->with("error","A server erro happened during storing the Diagnosis in the database,<br> Please try again later");
          DB::rollBack();
          // return redirect()->back()->with(['state'=>'error','error'=>'A server error happened during storing the Diagnosis in the database,<br> Please try again later','code'=>422]);
          return json_encode(['state'=>'error','error'=>'A server error happened during storing the Diagnosis in the database,<br> Please try again later','code'=>422]);
        }
        // return redirect()->route("showDiagnose",["id"=>$diagnose->id]);
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
        $diagnose = Diagnose::id($id)->notDeleted()->firstOrFail();
        $appointments = $diagnose->appointments()->notDeleted()->where('approved','!=',0)->order()->take(3)->get();
        $drugs = $diagnose->drugs()->notDeleted()->orderBy("created_at","desc")->get();
        $oral_radiologies = $diagnose->oral_radiologies()->notDeleted()->orderBy("created_at","desc")->take(5)->get();
        $patient = $diagnose->patient;
        $teeth = $diagnose->teeth()->notDeleted()->get();
        $svg = $this->svgCreate($teeth);
        $allDrugs= Drug::notDeleted()->get();
        if ($diagnose->discount!=null && $diagnose->discount!=0) {
          if($diagnose->discount_type==0){
            $total_price = $diagnose->teeth()->notDeleted()->sum('price');
            $discount = $total_price * ($diagnose->discount/100);
            $total_price -= $discount;
          }else {
            $total_price = $diagnose->teeth()->notDeleted()->sum('price') - $diagnose->discount;
          }
        }else{
          $total_price =$diagnose->teeth()->notDeleted()->sum('price');
        }
        $data = [
          "diagnose"=>$diagnose,
          "appointments"=>$appointments,
          "drugs"=>$drugs,
          "oral_radiologies"=>$oral_radiologies,
          "patient"=>$patient,
          "teeth"=>$teeth,
          "allDrugs"=>$allDrugs,
          "svg"=>$svg,
          "total_price"=>$total_price
        ];
        return view("diagnose.show",$data);
    }

    /**
     * Display the all case photos of diagnosis.
     *
     * @param  \App\Diagnose  $diagnose
     * @return \Illuminate\Http\Response
     */
     public function getCasePhotos($id)
     {
       $diagnose= Diagnose::id($id)->notDeleted()->firstOrFail();
       $cases_photos=$diagnose->cases_photos()->notDeleted()->get();
       $data=[
         'diagnose'=>$diagnose,
         'cases_photos'=>$cases_photos
       ];
       return view('case_photo.all',$data);
     }

    /**
     * Add Case Photo to a specific Diagnosis
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Diagnose  $diagnose
     * @return \Illuminate\Http\Response
     */
     public function addCasePhoto(StoreCasePhoto $request, $id)
     {
       //store case photo
       $diagnose= Diagnose::findOrFail($id);
       $case_photo = new CasesPhoto;
       $case_photo->before_after=$request->before_after;
       $case_photo->photo= $request->case_photo->store("case_photo");
       $case_photo->diagnose_id=$diagnose->id;
       if(Storage::disk('local')->exists($case_photo->photo)){
         $saved=$case_photo->save();
         if (!$saved) {
           return redirect()->back()->with('error',"A server error is happened during uploading case photo<br>Please try again later");
         }
         $log = new UserLog;
         $log->affected_table="cases_photos";
         $log->affected_row=$case_photo->id;
         $log->process_type="create";
         $log->description="has created a case photo within <a href='".route('showDiagnose',['id'=>$id])."'>Diagnosis Nr. $id</a>";
         $log->user_id= Auth::user()->id;
         $log->save();
         return redirect()->back()->with('success',"The case photo is successfully uploaded");
       }
       return redirect()->back()->with('error','something wrong happened during uploading the case photo');
     }

    /**
     * Add payment to a specific Diagnosis
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Diagnose  $diagnose
     * @return \Illuminate\Http\Response
     */
     public function addPayment(StorePayment $request, $id)
     {
       $diagnose = Diagnose::findOrFail($id);
       if ($diagnose->discount!=null || $diagnose->discount!=0) {
         if($diagnose->discount_type==0){
           $total_price = $diagnose->teeth()->notDeleted()->sum('price');
           $discount = $total_price * ($diagnose->discount/100);
           $total_price -= $discount;
         }else {
           $total_price = $diagnose->teeth()->notDeleted()->sum('price') - $diagnose->discount;
         }
       }else{
         $total_price =$diagnose->teeth()->notDeleted()->sum('price');
       }

       $maxPayment = $total_price - $diagnose->total_paid;
       if($request->payment > $maxPayment){
         return redirect()->back()->with("error","The maximum payment should not be more than $maxPayment, The total price is ".$total_price." EGP and the patient already paid ".$diagnose->total_paid);
       }
       $diagnose->total_paid += $request->payment;
       $saved = $diagnose->save();
       if(!$saved){
         return redirect()->back()->with("error","A server erro happened during adding payment to the Diagnosis in the database,<br> Please try again later");
       }
       $log= new UserLog;
       $log->affected_table="diagnoses";
       $log->affected_row=$diagnose->id;
       $log->process_type="update";
       $log->description="has added ".$request->payment." payment in the diagnosis";
       $log->user_id=Auth::user()->id;
       $log->save();
       return redirect()->back()->with("success","Payment is successfully added ");
     }
    /**
     * Add payment to a specific Diagnosis
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Diagnose  $diagnose
     * @return \Illuminate\Http\Response
     */
     public function addDiscount(AddDiscount $request, $id)
     {
       $diagnose = Diagnose::findOrFail($id);
       $discount_type_old =($diagnose->discount_type ==1)? "EGP":"%";
       $discount_type_new =($request->discount_type ==1)? "EGP":"%";
       if (!empty($diagnose->discount)) {
         $state="changed discount value from ".$diagnose->discount." ".$discount_type_old." to ".$request->discount." ".$discount_type_new;
       }else {
         $state="added discount value ".$request->discount." ".$discount_type_new;
       }
       $diagnose->discount = $request->discount;
       $diagnose->discount_type = $request->discount_type;
       $saved = $diagnose->save();
       if(!$saved){
         return redirect()->back()->with("error","A server erro happened during adding discount to the Diagnosis in the database,<br> Please try again later");
       }
       $log= new Userlog;
       $log->affected_table="diagnoses";
       $log->affected_row=$diagnose->id;
       $log->process_type="update";
       $log->description="has ".$state;
       $log->user_id=Auth::user()->id;
       $log->save();
       return redirect()->back()->with("success","Discount is successfully added ");
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
       $total_price=$diagnose->teeth()->notDeleted()->sum('price');
       if($diagnose->discount!=null||$diagnose->discount!=0){
         if ($diagnose->discount_type) {
           $total_price-=$diagnose->discount;
         }else {
           $total_price-=($total_price*($diagnose->discount/100));
         }
       }
       if ($total_price!=$diagnose->total_paid) {
         $successMsg.=" <br> Take into account that this Diagnosis isn't fully paid, the patient paid only ";
         if($diagnose->total_paid==0){
           $successMsg.="0";
         }else {
           $successMsg.=$diagnose->total_paid;
         }
         $successMsg.=" EGP from ".$total_price." EGP<br>";
         $successMsg.='<button class="btn btn-success action"  data-action="#add_payment" data-url="/patient/diagnosis/'.$diagnose->id.'/add/payment">Add Payment Now</button>';
       }
       $log= new UserLog;
       $log->affected_table="diagnoses";
       $log->affected_row=$id;
       $log->process_type="update";
       $log->description="has finished this Diagnosis";
       $log->user_id=Auth::user()->id;
       $log->save();
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
        $diagnose = Diagnose::id($id)->notDeleted()->firstOrFail();
        $teeth = $diagnose->teeth()->notDeleted()->get();
        $svg= $this->svgCreate($teeth);
        $data=[
          "diagnose"=>$diagnose,
          "teeth"=>$teeth,
          "svg"=>$svg
        ];
        return view("diagnose.edit",$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Diagnose  $diagnose
     * @return \Illuminate\Http\Response
     */
    public function update(EditDiagnose $request, $id)
    {
      //store updates of diagnosis data
      $diagnose= Diagnose::id($id)->notDeleted()->firstOrFail();
      try{
        //store diagnosis data
        DB::beginTransaction();
        //store teeth
        $teeth_ids=$request->teeth_id;
        $teeth_colors=$request->teeth_color;
        $diagnose_types=$request->diagnose_type;
        $descriptions=$request->description;
        $prices=$request->price;
        $checkAll=0;
        for($i=0; $i< count($teeth_ids);$i++) {
          $tooth = Tooth::id($teeth_ids[$i])->notDeleted()->firstOrFail();
          $desc="User made some changes to the tooth ".$tooth->teeth_name.",";
          $check=0;
          if(strtolower($tooth->color)!=strtolower($teeth_colors[$i])){
            $check=1;
            $desc.="has changed diagnosis type from ".$tooth->diagnose_type." to ".$diagnose_types[$i].", ";
            $tooth->color=$teeth_colors[$i];
            $tooth->diagnose_type=$diagnose_types[$i];
          }
          if(strtolower($tooth->description)!=strtolower($descriptions[$i])){
            $check=1;
            $desc.="has changed diagnosis description oh the tooth from ".$tooth->description." to ".$descriptions[$i].", ";
            $tooth->description=$descriptions[$i];
          }
          if(strtolower($tooth->price)!=strtolower($prices[$i])){
            $check=1;
            $desc.="has changed tooth price from ".$tooth->price." to ".$prices[$i].'.';
            $tooth->price=$prices[$i];
          }
          if($check==1){
            $checkAll=1;
            $tooth->save();
            $log=new UserLog;
            $log->affected_table="diagnoses";
            $log->affected_row=$diagnose->id;
            $log->process_type="update";
            $log->description=$desc;
            $log->user_id=Auth::user()->id;
            $log->save();
          }
        }
        DB::commit();
      }catch(\PDOException $e){
        DB::rollBack();
        return redirect()->back()->with("error","A server erro happened during storing the Diagnosis in the database,<br> Please try again later");
      }
      if ($checkAll==1) {
        return redirect()->route("showDiagnose",["id"=>$id])->with('success','The Diagnosis is successfully updated');
      }else {
        return redirect()->back()->with('warning','There is no changes to change');
      }
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
        $teeth=$diagnose->teeth;
        $visits=$diagnose->appointments;
        $drugs=$diagnose->diagnose_drug;
        $xrays=$diagnose->oral_radiologies;
        $case_photos=$diagnose->cases_photos;
        try{
          DB::beginTransaction();
          $diagnose->deleted=1;
          foreach ($xrays as $x) {
            Storage::delete($x->photo);
            $x->delete();
          }
          foreach ($case_photos as $c) {
            Storage::delete($c->photo);
            $c->delete();
          }
          foreach ($teeth as $t) {
            $t->deleted=1;
            $t->save();
          }
          foreach ($drugs as $dr) {
            $dr->deleted=1;
            $dr->save();
          }
          foreach ($visits as $v) {
            $v->deleted=1;
            $v->save();
          }
          $diagnose->save();
          DB::commit();
        }catch(\PDOException $e){
          DB::rollBack();
          return redirect()->back()->with("error","An error happened during deleting diagnosis".$e->getMessage());
        }
        $log= new UserLog;
        $log->affected_table="diagnoses";
        $log->affected_row=$id;
        $log->process_type="delete";
        $log->description="has deleted this Diagnosis";
        $log->user_id=Auth::user()->id;
        $log->save();
        return redirect()->route("profilePatient",['id'=>$patient->id])->with('success','Diagnosis and all its related data are deleted successfully');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Diagnose  $diagnose
     * @return \Illuminate\Http\Response
     */
    public function deleteCasePhoto($id)
    {
        //DELETE A DIAGNOSIS WITH ALL ITS DATA
        $case_photo = CasesPhoto::findOrFail($id);
        $case_photo->deleted=1;
        $saved=$case_photo->save();
        if(!$saved){
          return redirect()->back()->with("error","An error happened during deleting patient");
        }
        $log= new UserLog;
        $log->affected_table="cases_photos";
        $log->affected_row=$id;
        $log->process_type="delete";
        $log->description="has deleted this case photo";
        $log->user_id=Auth::user()->id;
        $log->save();
        return redirect()->back()->with('success','Case Photo is deleted successfully');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function addTeeth($id)
    {
      $diagnose = Diagnose::id($id)->notDeleted()->notDone()->firstOrFail();
      $svg = $this->svgCreate($diagnose->teeth()->notDeleted()->get());
      $data = [
        'diagnose'=>$diagnose,
        'svg'=>$svg
      ];
      return view('diagnose.add_teeth',$data);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function storeTeeth(StoreTeeth $request,$id)
    {
      //store updates of diagnosis data
      $diagnose= Diagnose::id($id)->notDeleted()->firstOrFail();
      try{
        //store diagnosis data
        DB::beginTransaction();
        //store teeth
        $teeth_names=$request->teeth_name;
        $teeth_colors=$request->teeth_color;
        $diagnose_types=$request->diagnose_type;
        $descriptions=$request->description;
        $prices=$request->price;
        for($i=0; $i< count($teeth_names);$i++) {
          $tooth = new Tooth;
          $tooth->teeth_name=$teeth_names[$i];
          $tooth->color=$teeth_colors[$i];
          $tooth->diagnose_type=$diagnose_types[$i];
          $tooth->description=$descriptions[$i];
          $tooth->price=$prices[$i];
          $tooth->diagnose_id=$diagnose->id;
          $tooth->save();
          $log=new UserLog;
          $log->affected_table="diagnoses";
          $log->affected_row=$diagnose->id;
          $log->process_type="create";
          $log->description='has added a tooth to a diagnosis "Name" '.$tooth->teeth_name.' , "Diagnosis Type" '.$tooth->diagnose_type.' , "Description" '.$tooth->description.' , "Price" '.$tooth->price.' EGP';
          $log->user_id=Auth::user()->id;
          $log->save();
        }
        DB::commit();
      }catch(\PDOException $e){
        DB::rollBack();
        return redirect()->back()->with("error","A server erro happened during storing the Diagnosis in the database,<br> Please try again later");
      }
      return redirect()->route("showDiagnose",["id"=>$id])->with('success','The Teeth are successfully added');
    }
    //create teeth svg
    public function svgCreate($teeth)
    {
      $svg = "";
      foreach ($teeth as $tooth) {
        if(strpos($tooth->teeth_name,"{{1}}")!==false){
          $svg.='<circle cx="92" cy="324" r="25" stroke="black" stroke-width="3" data-teeth-id="teeth_1" fill="'.$tooth->color.'" opacity="0.7"/>';
        }
        elseif(strpos($tooth->teeth_name,"{{2}}")!==false){
          $svg.='<circle cx="95" cy="274" r="26" stroke="black" stroke-width="3" data-teeth-id="teeth_2" fill="'.$tooth->color.'" opacity="0.7"/>';
        }
        elseif(strpos($tooth->teeth_name,"{{3}}")!==false){
          $svg.='<circle cx="102" cy="227" r="26" stroke="black" stroke-width="3" data-teeth-id="teeth_3" fill="'.$tooth->color.'" opacity="0.7"/>';
        }
        elseif(strpos($tooth->teeth_name,"{{4}}")!==false){
          $svg.='<circle cx="115" cy="180" r="25" stroke="black" stroke-width="3" data-teeth-id="teeth_4" fill="'.$tooth->color.'" opacity="0.7"/>';
        }
        elseif(strpos($tooth->teeth_name,"{{5}}")!==false){
          $svg.='<circle cx="136" cy="138" r="24" stroke="black" stroke-width="3" data-teeth-id="teeth_5" fill="'.$tooth->color.'" opacity="0.7"/>';
        }
        elseif(strpos($tooth->teeth_name,"{{6}}")!==false){
          $svg.='<circle cx="162" cy="104" r="20" stroke="black" stroke-width="3" data-teeth-id="teeth_6" fill="'.$tooth->color.'" opacity="0.7"/>';
        }
        elseif(strpos($tooth->teeth_name,"{{7}}")!==false){
          $svg.='<circle cx="187" cy="76" r="20" stroke="black" stroke-width="3" data-teeth-id="teeth_7" fill="'.$tooth->color.'" opacity="0.7"/>';
        }
        elseif(strpos($tooth->teeth_name,"{{8}}")!==false){
          $svg.='<circle cx="226" cy="57" r="24" stroke="black" stroke-width="3" data-teeth-id="teeth_8" fill="'.$tooth->color.'" opacity="0.7"/>';
        }
        elseif(strpos($tooth->teeth_name,"{{9}}")!==false){
          $svg.='<circle cx="271" cy="57" r="23" stroke="black" stroke-width="3" data-teeth-id="teeth_9" fill="'.$tooth->color.'" opacity="0.7"/>';
        }
        elseif(strpos($tooth->teeth_name,"{{10}}")!==false){
          $svg.='<circle cx="311" cy="75" r="24" stroke="black" stroke-width="3" data-teeth-id="teeth_10" fill="'.$tooth->color.'" opacity="0.7"/>';
        }
        elseif(strpos($tooth->teeth_name,"{{11}}")!==false){
          $svg.='<circle cx="337" cy="104" r="21" stroke="black" stroke-width="3" data-teeth-id="teeth_11" fill="'.$tooth->color.'" opacity="0.7"/>';
        }
        elseif(strpos($tooth->teeth_name,"{{12}}")!==false){
          $svg.='<circle cx="361" cy="137" r="23" stroke="black" stroke-width="3" data-teeth-id="teeth_12" fill="'.$tooth->color.'" opacity="0.7"/>';
        }
        elseif(strpos($tooth->teeth_name,"{{13}}")!==false){
          $svg.='<circle cx="382" cy="181" r="25" stroke="black" stroke-width="3" data-teeth-id="teeth_13" fill="'.$tooth->color.'" opacity="0.7"/>';
        }
        elseif(strpos($tooth->teeth_name,"{{14}}")!==false){
          $svg.='<circle cx="395" cy="226" r="24" stroke="black" stroke-width="3" data-teeth-id="teeth_14" fill="'.$tooth->color.'" opacity="0.7"/>';
        }
        elseif(strpos($tooth->teeth_name,"{{15}}")!==false){
          $svg.='<circle cx="402" cy="275" r="24" stroke="black" stroke-width="3" data-teeth-id="teeth_15" fill="'.$tooth->color.'" opacity="0.7"/>';
        }
        elseif(strpos($tooth->teeth_name,"{{16}}")!==false){
          $svg.='<circle cx="404" cy="323" r="25" stroke="black" stroke-width="3" data-teeth-id="teeth_16" fill="'.$tooth->color.'" opacity="0.7"/>';
        }
        elseif(strpos($tooth->teeth_name,"{{17}}")!==false){
          $svg.='<circle cx="401" cy="397" r="28" stroke="black" stroke-width="3" data-teeth-id="teeth_17" fill="'.$tooth->color.'" opacity="0.7"/>';
        }
        elseif(strpos($tooth->teeth_name,"{{18}}")!==false){
          $svg.='<circle cx="398" cy="451" r="26" stroke="black" stroke-width="3" data-teeth-id="teeth_18" fill="'.$tooth->color.'" opacity="0.7"/>';
        }
        elseif(strpos($tooth->teeth_name,"{{19}}")!==false){
          $svg.='<circle cx="388" cy="502" r="27" stroke="black" stroke-width="3" data-teeth-id="teeth_19" fill="'.$tooth->color.'" opacity="0.7"/>';
        }
        elseif(strpos($tooth->teeth_name,"{{20}}")!==false){
          $svg.='<circle cx="370" cy="553" r="27" stroke="black" stroke-width="3" data-teeth-id="teeth_20" fill="'.$tooth->color.'" opacity="0.7"/>';
        }
        elseif(strpos($tooth->teeth_name,"{{21}}")!==false){
          $svg.='<circle cx="345" cy="594" r="25" stroke="black" stroke-width="3" data-teeth-id="teeth_21" fill="'.$tooth->color.'" opacity="0.7"/>';
        }
        elseif(strpos($tooth->teeth_name,"{{22}}")!==false){
          $svg.='<circle cx="318" cy="625" r="17" stroke="black" stroke-width="3" data-teeth-id="teeth_22" fill="'.$tooth->color.'" opacity="0.7"/>';
        }
        elseif(strpos($tooth->teeth_name,"{{23}}")!==false){
          $svg.='<circle cx="293" cy="642" r="14" stroke="black" stroke-width="3" data-teeth-id="teeth_23" fill="'.$tooth->color.'" opacity="0.7"/>';
        }
        elseif(strpos($tooth->teeth_name,"{{24}}")!==false){
          $svg.='<circle cx="263" cy="649" r="16" stroke="black" stroke-width="3" data-teeth-id="teeth_24" fill="'.$tooth->color.'" opacity="0.7"/>';
        }
        elseif(strpos($tooth->teeth_name,"{{25}}")!==false){
          $svg.='<circle cx="233" cy="648" r="15" stroke="black" stroke-width="3" data-teeth-id="teeth_25" fill="'.$tooth->color.'" opacity="0.7"/>';
        }
        elseif(strpos($tooth->teeth_name,"{{26}}")!==false){
          $svg.='<circle cx="202" cy="641" r="17" stroke="black" stroke-width="3" data-teeth-id="teeth_26" fill="'.$tooth->color.'" opacity="0.7"/>';
        }
        elseif(strpos($tooth->teeth_name,"{{27}}")!==false){
          $svg.='<circle cx="179" cy="625" r="19" stroke="black" stroke-width="3" data-teeth-id="teeth_27" fill="'.$tooth->color.'" opacity="0.7"/>';
        }
        elseif(strpos($tooth->teeth_name,"{{28}}")!==false){
          $svg.='<circle cx="153" cy="594" r="23" stroke="black" stroke-width="3" data-teeth-id="teeth_28" fill="'.$tooth->color.'" opacity="0.7"/>';
        }
        elseif(strpos($tooth->teeth_name,"{{29}}")!==false){
          $svg.='<circle cx="127" cy="553" r="27" stroke="black" stroke-width="3" data-teeth-id="teeth_29" fill="'.$tooth->color.'" opacity="0.7"/>';
        }
        elseif(strpos($tooth->teeth_name,"{{30}}")!==false){
          $svg.='<circle cx="108" cy="503" r="27" stroke="black" stroke-width="3" data-teeth-id="teeth_30" fill="'.$tooth->color.'" opacity="0.7"/>';
        }
        elseif(strpos($tooth->teeth_name,"{{31}}")!==false){
          $svg.='<circle cx="99" cy="451" r="27" stroke="black" stroke-width="3" data-teeth-id="teeth_31" fill="'.$tooth->color.'" opacity="0.7"/>';
        }
        elseif(strpos($tooth->teeth_name,"{{32}}")!==false){
          $svg.='<circle cx="94" cy="397" r="28" stroke="black" stroke-width="3" data-teeth-id="teeth_32" fill="'.$tooth->color.'" opacity="0.7"/>';
        }
        elseif(stripos($tooth->teeth_name,"{{A}}")!==false){
          $svg.='<circle cx="170" cy="226" r="18" stroke="black" stroke-width="3" data-teeth-id="teeth_A" fill="'.$tooth->color.'" opacity="0.7"/>';
        }
        elseif(stripos($tooth->teeth_name,"{{B}}")!==false){
          $svg.='<circle cx="178" cy="193" r="16" stroke="black" stroke-width="3" data-teeth-id="teeth_B" fill="'.$tooth->color.'" opacity="0.7"/>';
        }
        elseif(stripos($tooth->teeth_name,"{{C}}")!==false){
          $svg.='<circle cx="193" cy="169" r="16" stroke="black" stroke-width="3" data-teeth-id="teeth_C" fill="'.$tooth->color.'" opacity="0.7"/>';
        }
        elseif(stripos($tooth->teeth_name,"{{D}}")!==false){
          $svg.='<circle cx="209" cy="147" r="13" stroke="black" stroke-width="3" data-teeth-id="teeth_D" fill="'.$tooth->color.'" opacity="0.7"/>';
        }
        elseif(stripos($tooth->teeth_name,"{{E}}")!==false){
          $svg.='<circle cx="232" cy="132" r="15" stroke="black" stroke-width="3" data-teeth-id="teeth_E" fill="'.$tooth->color.'" opacity="0.7"/>';
        }
        elseif(stripos($tooth->teeth_name,"{{F}}")!==false){
          $svg.='<circle cx="263" cy="133" r="16" stroke="black" stroke-width="3" data-teeth-id="teeth_F" fill="'.$tooth->color.'" opacity="0.7"/>';
        }
        elseif(stripos($tooth->teeth_name,"{{G}}")!==false){
          $svg.='<circle cx="287" cy="147" r="15" stroke="black" stroke-width="3" data-teeth-id="teeth_G" fill="'.$tooth->color.'" opacity="0.7"/>';
        }
        elseif(stripos($tooth->teeth_name,"{{H}}")!==false){
          $svg.='<circle cx="303" cy="170" r="13" stroke="black" stroke-width="3" data-teeth-id="teeth_H" fill="'.$tooth->color.'" opacity="0.7"/>';
        }
        elseif(stripos($tooth->teeth_name,"{{I}}")!==false){
          $svg.='<circle cx="318" cy="194" r="17" stroke="black" stroke-width="3" data-teeth-id="teeth_I" fill="'.$tooth->color.'" opacity="0.7"/>';
        }
        elseif(stripos($tooth->teeth_name,"{{J}}")!==false){
          $svg.='<circle cx="326" cy="227" r="18" stroke="black" stroke-width="3" data-teeth-id="teeth_J" fill="'.$tooth->color.'" opacity="0.7"/>';
        }
        elseif(stripos($tooth->teeth_name,"{{K}}")!==false){
          $svg.='<circle cx="329" cy="480" r="20" stroke="black" stroke-width="3" data-teeth-id="teeth_K" fill="'.$tooth->color.'" opacity="0.7"/>';
        }
        elseif(stripos($tooth->teeth_name,"{{L}}")!==false){
          $svg.='<circle cx="317" cy="515" r="19" stroke="black" stroke-width="3" data-teeth-id="teeth_L" fill="'.$tooth->color.'" opacity="0.7"/>';
        }
        elseif(stripos($tooth->teeth_name,"{{M}}")!==false){
          $svg.='<circle cx="303" cy="549" r="15" stroke="black" stroke-width="3" data-teeth-id="teeth_M" fill="'.$tooth->color.'" opacity="0.7"/>';
        }
        elseif(stripos($tooth->teeth_name,"{{N}}")!==false){
          $svg.='<circle cx="283" cy="569" r="14" stroke="black" stroke-width="3" data-teeth-id="teeth_N" fill="'.$tooth->color.'" opacity="0.7"/>';
        }
        elseif(stripos($tooth->teeth_name,"{{O}}")!==false){
          $svg.='<circle cx="260" cy="577" r="14" stroke="black" stroke-width="3" data-teeth-id="teeth_O" fill="'.$tooth->color.'" opacity="0.7"/>';
        }
        elseif(stripos($tooth->teeth_name,"{{P}}")!==false){
          $svg.='<circle cx="236" cy="577" r="15" stroke="black" stroke-width="3" data-teeth-id="teeth_P" fill="'.$tooth->color.'" opacity="0.7"/>';
        }
        elseif(stripos($tooth->teeth_name,"{{Q}}")!==false){
          $svg.='<circle cx="211" cy="570" r="13" stroke="black" stroke-width="3" data-teeth-id="teeth_Q" fill="'.$tooth->color.'" opacity="0.7"/>';
        }
        elseif(stripos($tooth->teeth_name,"{{R}}")!==false){
          $svg.='<circle cx="193" cy="548" r="16" stroke="black" stroke-width="3" data-teeth-id="teeth_R" fill="'.$tooth->color.'" opacity="0.7"/>';
        }
        elseif(stripos($tooth->teeth_name,"{{S}}")!==false){
          $svg.='<circle cx="179" cy="517" r="20" stroke="black" stroke-width="3" data-teeth-id="teeth_S" fill="'.$tooth->color.'" opacity="0.7"/>';
        }
        elseif(stripos($tooth->teeth_name,"{{T}}")!==false){
          $svg.='<circle cx="168" cy="481" r="21" stroke="black" stroke-width="3" data-teeth-id="teeth_T" fill="'.$tooth->color.'" opacity="0.7"/>';
        }
      }
      return $svg;
    }
}
