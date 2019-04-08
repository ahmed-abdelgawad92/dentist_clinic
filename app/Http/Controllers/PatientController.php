<?php

namespace App\Http\Controllers;

use Validator;
use Auth;
use App\UserLog;
use App\Patient;
use App\Diagnose;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Request\StorePatient;
use App\Http\Request\UploadPhoto;
use App\Http\Request\SearchPatient;

class PatientController extends Controller
{
    public function search(SearchPatient $request)
    {
      $search=$request->patient;
      //search for a patient
      $patients = Patient::notDeleted()->search($search)->paginate(15);
      if($patients->count()==0){
        return redirect()->route('searchResults')->with('warning','The patient with these information "'.$search.'" is not found <br> You can search a patient only with Patient\'s File Number, Name or date of birth (in this format YYYY-MM-DD)');
      }
      return view("patient.all",['patients'=>$patients]);
    }
    public function getSearch($value='')
    {
      // code...
      return view("patient.all",['patients'=>null]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //show all patients
        $patients = Patient::notDeleted()->paginate(15);
        return view("patient.all",['patients'=>$patients]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('patient.add');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePatient $request)
    {
        // save patient
        $patient = new Patient;
        $patient->pname = mb_strtolower($request->pname);
        $patient->gender = mb_strtolower($request->gender);
        $patient->dob = date("Y-m-d",strtotime("-".$request->dob." year",time()));
        $patient->address = mb_strtolower($request->address);
        $patient->phone = mb_strtolower($request->phone);
        $patient->diabetes = mb_strtolower($request->diabetes);
        $patient->blood_pressure = mb_strtolower($request->blood_pressure);
        $patient->medical_compromise = mb_strtolower($request->medical_compromise);
        if($request->hasFile("photo")){
          //$photo_path = $request->pname.time()".".$request->photo->extension();
          $patient->photo=$request->photo->store("patient_profile");
        }
        $saved = $patient->save();
        if(!$saved){
          return redirect()->back()->withInput()->with("insert_error","A server error happened during creating a new patient <br /> please try again later");
        }
        $log = new UserLog;
        $log->affected_table="patients";
        $log->affected_row=$patient->id;
        $log->process_type="create";
        $log->description="has created a new patient called ".$patient->pname;
        $log->user_id=Auth::user()->id;
        $log->save();

        return redirect()->route("profilePatient",["id"=>$patient->id])->with("success","Patient created successfully");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //get patient with id then get the current diagnose and see how many undone diagnoses
        $patient = Patient::findOrFail($id);
        $currentDiagnose = $patient->diagnoses()->notDeleted()->notDone()->get()->last();
        $numOfUndoneDiagnose = $patient->diagnoses()->notDeleted()->notDone()->count();
        $numOfDiagnose = $patient->diagnoses()->notDeleted()->count();
        $lastVisit = $patient->appointments()->notDeleted()->finished()->orderBy('date','ASC')->get()->last();
        $nextVisit = $patient->appointments()->notDeleted()->notApproved()->orderBy('date','ASC')->get()->first();
        $total_priceAllDiagnoses= 0;
        $total_paidAllDiagnoses =$patient->diagnoses()->notDeleted()->sum('total_paid');
        $diagnoses=$patient->diagnoses()->notDeleted()->get();
        foreach ($diagnoses as $diagnose) {
          $diagnosePrice=$diagnose->teeth()->notDeleted()->sum('price');
          if ($diagnose->discount!=null && $diagnose->discount!=0) {
            if($diagnose->discount_type==0){
              $discount = $diagnosePrice * ($diagnose->discount/100);
              $diagnosePrice -= $discount;
            }else {
              $diagnosePrice -= $diagnose->discount;
            }
          }
          $total_priceAllDiagnoses+= $diagnosePrice;
        }
        $total_paid=null;
        $total_price=null;
        if(isset($currentDiagnose)&& !empty($currentDiagnose)){
          $total_paid = $currentDiagnose->total_paid;
          $total_price= $currentDiagnose->teeth()->notDeleted()->sum('price');
          if ($currentDiagnose->discount!=null && $currentDiagnose->discount!=0) {
            if($currentDiagnose->discount_type==0){
              $discount = $total_price * ($currentDiagnose->discount/100);
              $total_price -= $discount;
            }else {
              $total_price -= $currentDiagnose->discount;
            }
          }
        }
        $data = [
          "patient"=>$patient,
          "diagnose"=>$currentDiagnose,
          "numOfUndoneDiagnose"=>$numOfUndoneDiagnose,
          "numOfDiagnose"=>$numOfDiagnose,
          "lastVisit"=>$lastVisit,
          "nextVisit"=>$nextVisit,
          "total_price"=>$total_price,
          "total_paid"=>$total_paid,
          "total_paidAllDiagnoses"=>$total_paidAllDiagnoses,
          "total_priceAllDiagnoses"=>$total_priceAllDiagnoses
        ];
        return view("patient.show",$data);
    }

    /**
    * Display the specified resource.
    *
    * @param  \App\Patient  $patient
    * @return \Illuminate\Http\Response
    */
    public function allPayments($id)
    {
      if (Auth::user()->role==1||Auth::user()->role==2) {
        $patient= Patient::findOrFail($id);
        $diagnoses= $patient->diagnoses()->notDeleted()->with('teeth')->get();
        $data = [
          'patient'=>$patient,
          'diagnoses'=>$diagnoses
        ];
        return view('patient.payments',$data);
      }
      return view('errors.404');
    }
    /**
    * Display the specified resource.
    *
    * @param  \App\Patient  $patient
    * @return \Illuminate\Http\Response
    */
    public function allPatientPayments()
    {
      if (Auth::user()->role==1||Auth::user()->role==2) {
        $diagnoses = Diagnose::notDeleted()->orderBy('created_at','DESC')->with('teeth')->get();
        $total_priceAllDiagnoses= 0;
        $total_paidAllDiagnoses = $diagnoses->sum('total_paid');
        foreach ($diagnoses as $diagnose) {
          $diagnosePrice=$diagnose->teeth()->notDeleted()->sum('price');
          if ($diagnose->discount!=null && $diagnose->discount!=0) {
            if($diagnose->discount_type==0){
              $discount = $diagnosePrice * ($diagnose->discount/100);
              $diagnosePrice -= $discount;
            }else {
              $diagnosePrice -= $diagnose->discount;
            }
          }
          $total_priceAllDiagnoses+= $diagnosePrice;
        }
        $data = [
          'total_paidAllDiagnoses'=>$total_paidAllDiagnoses,
          'total_priceAllDiagnoses'=>$total_priceAllDiagnoses,
          'diagnoses'=>$diagnoses
        ];
        return view('patient.allPayments',$data);
      }
      return view('errors.404');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Patient  $patient
     * @return \Illuminate\Http\Response
     */
     public function uploadProfilePhoto(UploadPhoto $request,$id)
     {
        $patient= Patient::findOrFail($id);
        if ($patient->photo != null) {
          Storage::delete($patient->photo);
        }
        $patient->photo=$request->photo->store("patient_profile");
        $saved=$patient->save();
        if (!$saved) {
          return redirect()->back()->withInput()->with("error","A server error happened during uploading a patient profile picture <br /> please try again later");
        }
        $log = new UserLog;
        $log->affected_table="patients";
        $log->affected_row=$patient->id;
        $log->process_type="update";
        $log->description="has changed profile picture of ".$patient->pname;
        $log->user_id=Auth::user()->id;
        $log->save();
        return redirect()->back()->with("success","Profile picture uploaded successfully");
     }

     /**
      * Display the all case photos of diagnosis.
      *
      * @param  \App\Diagnose  $diagnose
      * @return \Illuminate\Http\Response
      */
      public function getCasePhotos($id)
      {
        $patient= Patient::id($id)->notDeleted()->firstOrFail();
        $cases_photos=$patient->cases_photos()->notDeleted()->get();
        $data=[
          'patient'=>$patient,
          'cases_photos'=>$cases_photos
        ];
        return view('case_photo.all',$data);
      }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //show edit view
        $patient = Patient::findOrFail($id);
        return view("patient.edit",['patient'=>$patient]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function update(StorePatient $request, $id)
    {
      $description_array= array();
      // update patient
      $patient = Patient::findOrFail($id);
      if($patient->pname!=$request->pname){
        array_push($description_array,"name from ".$patient->pname." to ".$request->pname);
      }
      if($patient->gender!=$request->gender){
        if ($patient->gender==1) {
          array_push($description_array,"gender from male to female");
        }else {
          array_push($description_array,"gender from female to male");
        }
      }
      if(round((time()-strtotime($patient->dob))/(3600*24*365.25))!=$request->dob){
        array_push($description_array,"age from ".$patient->dob." to ".$request->dob);
      }
      if($patient->address!=$request->address){
        array_push($description_array,"address from ".$patient->address." to ".$request->address);
      }
      if($patient->phone!=$request->phone){
        array_push($description_array,"phone from ".$patient->phone." to ".$request->phone);
      }
      if($patient->diabetes!=$request->diabetes){
        if($patient->diabetes==1){
          array_push($description_array,"diabetes from yes to no");
        }else {
          array_push($description_array,"diabetes from no to yes");
        }
      }
      if($patient->blood_pressure!=$request->blood_pressure){
        array_push($description_array,"blood pressure from ".$patient->blood_pressure." to ".$request->blood_pressure);
      }
      if($patient->medical_compromise!=$request->medical_compromise){
        array_push($description_array,"medical compromise from '".$patient->medical_compromise."' to '".$request->medical_compromise."'");
      }

      $patient->pname = mb_strtolower($request->pname);
      $patient->gender = mb_strtolower($request->gender);
      $patient->dob = date("Y-m-d",strtotime("-".$request->dob." year",time()));
      $patient->address = mb_strtolower($request->address);
      $patient->phone = mb_strtolower($request->phone);
      $patient->diabetes = mb_strtolower($request->diabetes);
      $patient->blood_pressure = mb_strtolower($request->blood_pressure);
      $patient->medical_compromise = mb_strtolower($request->medical_compromise);

      $saved = $patient->save();
      if(!$saved){
        return redirect()->back()->withInput()->with("insert_error","A server error happened during updating \"".$patient->pname."\" <br /> please try again later");
      }
      if (count($description_array)>0) {
        $log = new UserLog;
        $log->affected_table="patients";
        $log->affected_row=$patient->id;
        $log->process_type="update";
        $description="has changed ".array_pop($description_array);
        for ($i=0; $i < count($description_array); $i++) {
          $description.=" and ".$description_array[$i];
        }
        $log->description=$description;
        $log->user_id=Auth::user()->id;
        $log->save();
      }

      return redirect()->route("profilePatient",["id"=>$patient->id])->with("success","Patient updated successfully");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //delete patient and his photo file
        $patient = Patient::findOrFail($id);
        $diagnoses=$patient->diagnoses;
        $teeth=$patient->teeth;
        $visits=$patient->appointments;
        $diagnose_drug=$patient->diagnose_drug;
        $xrays=$patient->oral_radiologies;
        $case_photos=$patient->cases_photos;
        try{
          DB::beginTransaction();
          $patient->deleted=1;
          foreach ($xrays as $x) {
            $x->deleted=1;
            $x->save();
          }
          foreach ($case_photos as $c) {
            $c->deleted=1;
            $c->save();
          }
          foreach ($diagnoses as $d) {
            $d->deleted=1;
            $d->save();
          }
          foreach ($teeth as $t) {
            $t->deleted=1;
            $t->save();
          }
          foreach ($diagnose_drug as $dr) {
            $dr->deleted=1;
            $dr->save();
          }
          foreach ($visits as $v) {
            $v->deleted=1;
            $v->save();
          }
          $patient->save();

          $log = new UserLog;
          $log->affected_table="patients";
          $log->affected_row=$patient->id;
          $log->process_type="delete";
          $log->description="has deleted patient ".$patient->pname." and all its diagnosis, visits, xrays, case photos and medication";
          $log->user_id=Auth::user()->id;
          $log->save();
          DB::commit();
          if($patient->photo!=null){
            Storage::delete($patient->photo);
            $patient->photo=null;
            $patient->save();
          }
          foreach ($case_photos as $c) {
            if($c->photo!=null){
              Storage::delete($c->photo);
            }
          }
          foreach ($xrays as $x) {
            if($x->photo!=null){
              Storage::delete($x->photo);
            }
          }
          return redirect()->route('home')->with('success','Patient deleted successfully');
        }catch (\PDOException $e){
          DB::rollBack();
          return redirect()->back()->with("error","An error happened during deleting patient".$e->getMessage());
        }
    }
}
