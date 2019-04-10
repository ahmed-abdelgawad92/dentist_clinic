<?php

namespace App\Http\Controllers;

use Validator;
use Auth;

use App\Repositories\PatientRepository;
use App\Repositories\DiagnoseRepository;
use App\Repositories\UserLogRepository;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use App\Http\Requests\StorePatient;
use App\Http\Requests\UploadPhoto;
use App\Http\Requests\SearchPatient;

class PatientController extends Controller
{
    protected $patient;
    protected $diagnose;
    protected $userlog;

    public function __construct(
      PatientRepository $patient,
      DiagnoseRepository $diagnose,
      UserLogRepository $userlog
    )
    {
      $this->patient = $patient;
      $this->diagnose = $diagnose;
      $this->userlog = $userlog;
    }

    public function search(SearchPatient $request)
    {
      $search = $request->patient;
      $patients = $this->patient->search($search);
      if($patients->count()==0){
        return redirect()->route('searchResults')->with('warning','The patient with these information "'.htmlspecialchars($search).'" is not found <br> You can search a patient only with Patient\'s File Number, Name or date of birth (in this format YYYY-MM-DD)');
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
        $patients = $this->patient->paginate(15);
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
        $patient = $this->patient->create($request);
        $log['affected_table']="patients";
        $log['affected_row']=$patient->id;
        $log['process_type']="create";
        $log['description']="has created a new patient called ".$patient->pname;
        $this->userlog->create($log);

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
        $patient = $this->patient->get($id);
        $currentDiagnose = $this->patient->getCurrentDiagnose($id);
        $numOfUndoneDiagnose = $this->patient->numOfUndoneDiagnoses($id);
        $numOfDiagnose = $this->patient->numOfAllDiagnoses($id);
        $lastVisit = $this->getLastVisit($id);
        $nextVisit = $this->patient->getNextVisit($id);
        $total_priceAllDiagnoses= 0;
        $total_paidAllDiagnoses =$this->patient->totalPaidAmount($id);
        $diagnoses=$this->patient->getAllDiagnoses($id);

        foreach ($diagnoses as $diagnose) {
          $diagnosePrice=$this->diagnose->totalPrice($diagnose->id);
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
          $total_price= $currentDiagnose->teeth()->sum('price');
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
      $this->authorize('isAdmin', Auth::user());
      $patient= $this->patient->get($id);
      $diagnoses= $this->patient->getAllDiagnosesWithTeeth($id);
      $data = [
        'patient'=>$patient,
        'diagnoses'=>$diagnoses
      ];
      return view('patient.payments',$data);
    }
    /**
    * Display the specified resource.
    *
    * @param  \App\Patient  $patient
    * @return \Illuminate\Http\Response
    */
    public function allPatientPayments()
    {
      $this->authorize('isAdmin', Auth::user());
      $diagnoses = $this->diagnose->allWithTeeth();
      $total_priceAllDiagnoses= 0;
      $total_paidAllDiagnoses = $diagnoses->sum('total_paid');
      foreach ($diagnoses as $diagnose) {
        $diagnosePrice=$this->diagnose->totalPrice($diagnose->id);
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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Patient  $patient
     * @return \Illuminate\Http\Response
     */
     public function uploadProfilePhoto(UploadPhoto $request,$id)
     {
        $patient= $this->patient->get($id);
        if ($patient->photo != null) {
          Storage::delete($patient->photo);
        }
        $this->patient->changePhoto($id, $request->photo->store("patient_profile"));     
        $log['table']="patients";
        $log['id']=$patient->id;
        $log['action']="update";
        $log['description']="has changed profile picture of ".$patient->pname;
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
        $patient= $this->patient->get($id);
        $cases_photos=$patient->cases_photos()->get();
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
        $patient = $this->patient->get($id);
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
      $patient = $this->patient->get($id);
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
      //save changes
      $this->patient->update($id, $request);

      if (count($description_array)>0) {
        $log['table']="patients";
        $log['id']=$patient->id;
        $log['action']="update";
        $log['description']="has changed ".implode(" and ",$description_array);
        $this->userlog->create($log);
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
        $patient = $this->patient->delete($id);
        $log['table']="patients";
        $log['id']=$patient->id;
        $log['action']="delete";
        $log['description']="has deleted patient ".$patient->pname." and all its diagnosis, visits, xrays, case photos and medication";
        $this->userlog->create($log);
        return redirect()->route('home')->with('success','Patient deleted successfully');
    }
}
