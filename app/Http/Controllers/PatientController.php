<?php

namespace App\Http\Controllers;

use Validator;
use App\UserLog;
use App\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PatientController extends Controller
{
    public function search(Request $request)
    {
      //validate input
      $rules=[
        "patient"=>["required","regex:/^([a-zA-Z\s_]+|[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}|[0-9]+)$/"]
      ];
      $error_messages=[
        "patient.required"=>"You can't search a patient with an empty input",
        "patient.regex"=>"You can search a patient only with Patient's File Number, Name or date of birth (in this format YYYY-MM-DD)"
      ];
      $validator= Validator::make($request->all(),$rules,$error_messages);
      if($validator->fails()){
        return redirect()->back()->withErrors($validator)->withInput();
      }
      $search=$request->patient;
      //search for a patient
      $patients = Patient::where("pname","like","%".$search)->orWhere("pname","like",$search."%")->orWhere("dob",$search)->orWhere("id",$search)->paginate(15);
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
        $patients = Patient::paginate(15);
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
    public function store(Request $request)
    {
        // defining the validation rules
        $rules=[
          "pname"=>["required","regex:/^[a-zA-Z\s_]+$/"],
          "gender"=>["required","regex:/^(0|1)$/"],
          "dob"=>"required|date",
          "address"=>"required",
          "phone"=>["required","regex:/^(\+)?[0-9]{8,15}$/"],
          "diabetes"=>["required","regex:/^(0|1)$/"],
          "blood_pressure"=>["required","regex:/^(low|normal|high)$/"],
          "photo"=>"image|mimes:jpeg,png,jpg,gif"
        ];
        // defining the error $error_messages
        $error_messages=[
          "pname.required"=>"Please enter a patient name",
          "pname.regex"=>"Please enter a valid patient name that contains only alphabets , spaces and _",
          "gender.required"=>"please select a gender",
          "gender.regex"=>"please select a valid gender",
          "dob.required"=>"Please enter a date of birth",
          "dob.date"=>"Please enter a valid date in this format: yyyy-mm-dd",
          "address.required"=>"Please enter an address",
          "phone.required"=>"Please enter a phone no.",
          "phone.regex"=>"Please enter a valid phone no. that contains only numbers and can start with a +",
          "diabetes.required"=>"Please select the diabetes state",
          "diabetes.regex"=>"Please select a valid diabetes state",
          "blood_pressure.required"=>"Please select the blood pressure state",
          "blood_pressure.regex"=>"Please select a valid blood pressure state",
          "photo.mime"=>"Please upload a valid photo that has png, jpg, jpeg or gif extensions"
        ];
        //validate
        $validator = Validator::make($request->all(),$rules,$error_messages);
        if ($validator->fails()) {
          return redirect()->back()->withErrors($validator)->withInput();
        }
        // save patient
        $patient = new Patient;
        $patient->pname = mb_strtolower($request->pname);
        $patient->gender = mb_strtolower($request->gender);
        $patient->dob = mb_strtolower($request->dob);
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
        $currentDiagnose = $patient->diagnoses()->where("done",0)->get()->last();
        $numOfUndoneDiagnose = $patient->diagnoses()->where("done",0)->get()->count();
        $numOfDiagnose = $patient->diagnoses->count();
        $data = [
          "patient"=>$patient,
          "diagnose"=>$currentDiagnose,
          "numOfUndoneDiagnose"=>$numOfUndoneDiagnose,
          "numOfDiagnose"=>$numOfDiagnose
        ];
        return view("patient.show",$data);
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
    public function update(Request $request, $id)
    {
      // defining the validation rules
      $rules=[
        "pname"=>["required","regex:/^[a-zA-Z\s_]+$/"],
        "gender"=>["required","regex:/^(0|1)$/"],
        "dob"=>"required|date",
        "address"=>"required",
        "phone"=>["required","regex:/^(\+)?[0-9]{8,15}$/"],
        "diabetes"=>["required","regex:/^(0|1)$/"],
        "blood_pressure"=>["required","regex:/^(low|normal|high)$/"]
      ];
      // defining the error $error_messages
      $error_messages=[
        "pname.required"=>"Please enter a patient name",
        "pname.regex"=>"Please enter a valid patient name that contains only alphabets , spaces and _",
        "gender.required"=>"please select a gender",
        "gender.regex"=>"please select a valid gender",
        "dob.required"=>"Please enter a date of birth",
        "dob.date"=>"Please enter a valid date in this format: yyyy-mm-dd",
        "address.required"=>"Please enter an address",
        "phone.required"=>"Please enter a phone no.",
        "phone.regex"=>"Please enter a valid phone no. that contains only numbers and can start with a +",
        "diabetes.required"=>"Please select the diabetes state",
        "diabetes.regex"=>"Please select a valid diabetes state",
        "blood_pressure.required"=>"Please select the blood pressure state",
        "blood_pressure.regex"=>"Please select a valid blood pressure state"
      ];
      //validate
      $validator = Validator::make($request->all(),$rules,$error_messages);
      if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
      }
      // update patient
      $patient = Patient::findOrFail($id);
      $patient->pname = mb_strtolower($request->pname);
      $patient->gender = mb_strtolower($request->gender);
      $patient->dob = mb_strtolower($request->dob);
      $patient->address = mb_strtolower($request->address);
      $patient->phone = mb_strtolower($request->phone);
      $patient->diabetes = mb_strtolower($request->diabetes);
      $patient->blood_pressure = mb_strtolower($request->blood_pressure);
      $patient->medical_compromise = mb_strtolower($request->medical_compromise);

      $saved = $patient->save();
      if(!$saved){
        return redirect()->back()->withInput()->with("insert_error","A server error happened during updating \"".$patient->pname."\" <br /> please try again later");
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
        if($patient->photo!=null){
          Storage::delete($patient->photo);
        }
        $deleted=$patient->delete();
        if(!$deleted){
          return redirect()->back()->with("error","An error happened during deleting patient");
        }
        return redirect()->route('home')->with('success','Patient deleted successfully');
    }
}
