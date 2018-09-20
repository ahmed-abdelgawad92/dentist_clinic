<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use Validator;
use App\UserLog;
use App\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::user()->role==1||Auth::user()->role==2){
          $users = User::where("deleted",0)->orderBy("name","ASC")->paginate(20);
          $data = [
            'users'=>$users
          ];
          return view("user.all", $data);
        }else {
          return view("errors.404");
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        if(Auth::user()->role==1||Auth::user()->role==2){
          $users = User::where("deleted",0)->where("role","!=",2)->where(function($query) use($request){
                     $query->where('name', "like" ,"%".mb_strtolower($request->search_user)."%")
                     ->orWhere("uname", "like", "%".mb_strtolower($request->search_user)."%")
                     ->orWhere("phone", "like", "%".mb_strtolower($request->search_user)."%");
                   })->orderBy("name","ASC")->get();
          $data = [
            'state'=>"OK",
            'users'=>$users,
            'search_user'=>$request->search_user
          ];
          if($users->count()>0){
            return json_encode($data);
          }
          return json_encode(["state"=>"NOK","error"=>'"'.$request->search_user.'" is not found',"code"=>422]);
        }else {
          return view("errors.404");
        }
    }
    /**
     * Check available uname.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkUname(Request $request)
    {
        if (Auth::user()->role==1||Auth::user()->role==2) {
          // code...
          $rules=[
            'uname'=>["bail","required","regex:/^([a-zA-Z]+([\._@\-]?[0-9a-zA-Z]+)*){3,}$/","unique:users,uname","max:255"]
          ];
          $error_messages=[
            'uname.required'=>'Please enter Username',
            'uname.regex'=>'"Please enter a valid Username that contains only alphabets, numbers, . , @ , _ or -, and not less than 3 alphabets, and starts with at least one alphabet"',
            'uname.unique'=>'This Username is already taken, please enter another one',
            'uname.max'=>'Username must not be more than 255 characters'
          ];
          $validator= Validator::make($request->all(), $rules, $error_messages);
          if($validator->fails()){
            return json_encode(["state"=>"NOK","error"=>$validator->errors()->getMessages(),"code"=>422]);
          }
          return json_encode(["state"=>"OK"]);
        }else {
          return redirect()->route("home");
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Auth::user()->role==1||Auth::user()->role==2){
          return view("user.add");
        }else{
          return view("errors.404");
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      if(Auth::user()->role==1||Auth::user()->role==2){
        $rules=[
          'name'=>["required","regex:/^[a-zA-Z\s_]+$/"],
          'uname'=>["bail","required","regex:/^([a-zA-Z]+([\._@\-]?[0-9a-zA-Z]+)*){3,}$/","unique:users,uname","max:255"],
          'password'=>['required','min:8','regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/'],
          'confirm_password'=>'required|min:8|same:password',
          'phone'=>["required","regex:/^(\+)?[0-9]{8,15}$/"],
          'role'=>["required","regex:/^(0|1)+$/"],
          'photo'=>'image|mimes:jpeg,png,jpg,gif'
        ];
        $error_messages=[
          'name.required'=>'Please enter User\'s Full Name',
          'name.regex'=>'Please enter a valid Name that contains only alphabets , spaces and _',
          'uname.required'=>'Please enter Username',
          'uname.regex'=>"Please enter a valid Username that contains only alphabets, numbers, . , @ , _ or -, and not less than 3 alphabets, and starts with at least one alphabet",
          'uname.unique'=>'This Username is already taken, please enter another one',
          'uname.max'=>'Username must not be more than 255 characters',
          'password.required'=>'Please enter a password',
          'password.min'=>'Password must be at least 8 characters',
          'password.regex'=>'Password must contain at least 8 characters, one Uppercase letter, one Lowercase letter, a number, and a special character (#,?,!,@,$,%,^,&,* or -)',
          'confirm_password.required'=>'Please re-type the password',
          'confirm_password.min'=>'Password must be at least 8 characters',
          'confirm_password.same'=>'Password Confirmation must be exactly the same as Password',
          'phone.required'=>'Please enter Phone No.',
          'phone.regex'=>'Please enter a valid Phone No. that contains only numbers and can start with a (+)',
          'role.required'=>'Please select a role',
          'role.regex'=>'Please select a valid role',
          'photo.image'=>'Please upload a valid photo that has png, jpg, jpeg or gif extensions',
          'photo.mimes'=>'Please upload a valid photo that has png, jpg, jpeg or gif extensions'
        ];
        $validator =Validator::make($request->all(), $rules, $error_messages);
        if($validator->fails()){
          return json_encode(["state"=>"NOK","error"=>$validator->errors()->getMessages(),"code"=>422]);
        }
        $user = new User;
        $user->name= mb_strtolower($request->name);
        $user->uname=mb_strtolower($request->uname);
        $user->password=bcrypt($request->password);
        $user->phone=$request->phone;
        $user->role=$request->role;
        if ($request->hasFile("photo")) {
          $user->photo=$request->photo->store("user_profile");
        }
        $saved=$user->save();
        if(!$saved){
          return json_encode(["state"=>"NOK","error"=>$validator->errors()->getMessages(),"code"=>422]);
        }

        $user_log = new UserLog;
        $user_log->affected_table="users";
        $user_log->affected_row= $user->id;
        $user_log->process_type= "create";
        $user_log->user_id= Auth::user()->id;
        $user_log->description = "has created a new User called ".$user->uname;
        $user_log->save();

        $data=[
          "state"=>"OK",
          "route"=> route("showUser",['id'=>$user->id]),
          "success"=>"User Created Successfully"
        ];
        return json_encode($data);
      }else{
        return view("errors.404");
      }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Patient  $patient
     * @return \Illuminate\Http\Response
     */
     public function uploadProfilePhoto(Request $request,$id)
     {
       if(Auth::user()->role==1 ||Auth::user()->role==2 || $id == Auth::user()->id){
         $rules=['photo'=>'required|image|mimes:jpeg,png,jpg,gif'];
         $error_messages=[
           'photo.required'=>'Please choose a photo to upload as a profile picture',
           "photo.mime"=>"Please upload a valid photo that has png, jpg, jpeg or gif extensions"
         ];
         $validator=Validator::make($request->all(),$rules,$error_messages);
         if($validator->fails()){
           return redirect()->back()->with('error','Please upload a valid photo that has png, jpg, jpeg or gif extensions');
         }

         $user= User::findOrFail($id);
         if ($user->photo != null) {
           Storage::delete($user->photo);
         }
         $user->photo=$request->photo->store("patient_profile");
         $saved=$user->save();
         if (!$saved) {
           return redirect()->back()->withInput()->with("error","A server error happened during uploading a patient profile picture <br /> please try again later");
         }
         $log = new UserLog;
         $log->affected_table="users";
         $log->affected_row=$user->id;
         $log->process_type="update";
         if($id==Auth::user()->id){
           $log->description="has changed his own profile picture";
         }else {
           $log->description="has changed the profile picture of ".$user->uname;
         }
         $log->user_id=Auth::user()->id;
         $log->save();
         return redirect()->back()->with("success","Profile picture uploaded successfully");
       }
     }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      if(Auth::user()->id==$id || Auth::user()->role==1 ||Auth::user()->role==2){
        $user = User::findOrFail($id);
        if (Auth::user()->role==1 && $user->role==2) {
          return view('errors.404');
        }
        $user_logs = $user->user_logs()->where("affected_table","users")->orderBy("created_at","DESC")->take(5)->get();
        $patient_logs = $user->user_logs()->where("affected_table","patients")->orderBy("created_at","DESC")->take(5)->get();
        $diagnose_logs = $user->user_logs()->where("affected_table","diagnoses")->orderBy("created_at","DESC")->take(5)->get();
        $drug_logs = $user->user_logs()->where("affected_table","drugs")->orderBy("created_at","DESC")->take(5)->get();
        $visit_logs = $user->user_logs()->where("affected_table","appointments")->orderBy("created_at","DESC")->take(5)->get();
        $xray_logs = $user->user_logs()->where("affected_table","oral_radiologies")->orderBy("created_at","DESC")->take(5)->get();
        $working_times_logs = $user->user_logs()->where("affected_table","working_times")->orderBy("created_at","DESC")->take(5)->get();
        $data=[
          'user_logs'=>$user_logs,
          'patient_logs'=>$patient_logs,
          'diagnose_logs'=>$diagnose_logs,
          'drug_logs'=>$drug_logs,
          'visit_logs'=>$visit_logs,
          'xray_logs'=>$xray_logs,
          'working_times_logs'=>$working_times_logs,
          'user'=>$user
        ];
        return view("user.show",$data);
      }else{
        return view("errors.404");
      }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getAllUserLogs($id, $table)
    {
      if(Auth::user()->role==1||Auth::user()->role==2){
        $user = User::findOrFail($id);
        if($user->role==2 && $user->id!=Auth::user()->id){
          return view('errors.404');
        }
        switch ($table) {
          case 'users':
            $logs = $user->user_logs()->where("affected_table","users")->orderBy("created_at","DESC")->paginate(30);
            break;
          case 'patients':
            $logs = $user->user_logs()->where("affected_table","patients")->orderBy("created_at","DESC")->paginate(30);
            break;
          case 'diagnoses':
            $table="Diagnosis";
            $logs = $user->user_logs()->where("affected_table","diagnoses")->orderBy("created_at","DESC")->paginate(30);
            break;
          case 'drugs':
            $table="Medications";
            $logs = $user->user_logs()->where("affected_table","drugs")->orderBy("created_at","DESC")->paginate(30);
            break;
          case 'oral_radiologies':
            $table="X-rays";
            $logs = $user->user_logs()->where("affected_table","oral_radiologies")->orderBy("created_at","DESC")->paginate(30);
            break;
          case 'appointments':
            $table="Visits";
            $logs = $user->user_logs()->where("affected_table","appointments")->orderBy("created_at","DESC")->paginate(30);
            break;
          case 'working_times':
            $table="Working Times";
            $logs = $user->user_logs()->where("affected_table","working_times")->orderBy("created_at","DESC")->paginate(30);
            break;

          default:
            return view("error.404");
            break;
        }
        $data=[
          'logs'=>$logs,
          'table'=>$table,
          'user'=>$user
        ];
        return view("user.allUserLog",$data);
      }else{
        return view("errors.404");
      }
    }

    /**
     * Show all logs of a user without specifying a table
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function allUserLogs($id)
    {
      if(Auth::user()->role==1||Auth::user()->role==2){
        $user = User::findOrFail($id);
        if($user->role==2 && $user->id!=Auth::user()->id){
          return view('errors.404');
        }
        $logs = $user->user_logs()->orderBy("created_at","DESC")->paginate(30);

        $data=[
          'logs'=>$logs,
          'table'=>'All Data',
          'user'=>$user
        ];
        return view("user.allUserLog",$data);
      }else{
        return view("errors.404");
      }
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
     public function editPassword()
     {
       return view("user.change_password");
     }
    /**
     * Update Password
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
     public function updatePassword(Request $request)
     {
        $rules = [
          'old_password'=>['required'],
          'new_password'=>['required','regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/'],
          'confirm_new_password'=>['required','same:new_password']
        ];
        $error_messages = [
          "old_password.required"=>"Please enter your old password",
          "new_password.required"=>"Please enter your new password",
          "new_password.regex"=>"Password must contain at least 8 characters, one Uppercase letter, one Lowercase letter, a number, and a special character (#,?,!,@,$,%,^,&,* or -)",
          "confirm_new_password.required"=>"Please Re-type password",
          "confirm_new_password.same"=>"Passwords don't match"
        ];
        $validator = Validator::make($request->all(), $rules, $error_messages);
        if ($validator->fails()) {
          return redirect()->back()->withInput()->withErrors($validator);
        }
        $user = User::findOrFail(Auth::user()->id);
        if (Hash::check($request->get('old_password'), Auth::user()->password)) {
          $user->password = bcrypt($request->new_password);
          $saved = $user->save();
          if(!$saved){
            return redirect()->back()->with("error","A server error happened during changing password, please try again later");
          }
          $user_log = new UserLog;
          $user_log->affected_table="users";
          $user_log->affected_row=Auth::user()->id;
          $user_log->process_type="update";
          $user_log->description="has changed his own password";
          $user_log->user_id=Auth::user()->id;
          $user_log->save();
          return redirect()->route("showUser",['id'=>$user->id])->with("success","Password changed successfully");
        }else{
          return redirect()->back()->withInput()->with("error","You have entered a wrong password");
        }
     }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      if(Auth::user()->role==1||Auth::user()->role==2){
        $user= User::findOrFail($id);
        $data=[
          "user"=>$user
        ];
        return view("user.edit",$data);
      }else{
        return view("user.change_password");
      }
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
      if(Auth::user()->role==1||Auth::user()->role==2){
        $rules=[
          'name'=>["required","regex:/^[a-zA-Z\s_]+$/"],
          'phone'=>["required","regex:/^(\+)?[0-9]{8,15}$/"],
          'role'=>["required","regex:/^(0|1)+$/"]
        ];
        $error_messages=[
          'name.required'=>'Please enter User\'s Full Name',
          'name.regex'=>'Please enter a valid Name that contains only alphabets , spaces and _',
          'phone.required'=>'Please enter Phone No.',
          'phone.regex'=>'Please enter a valid Phone No. that contains only numbers and can start with a (+)',
          'role.required'=>'Please select a role',
          'role.regex'=>'Please select a valid role'
        ];
        $validator =Validator::make($request->all(), $rules, $error_messages);
        if($validator->fails()){
          return redirect()->back()->withInput()->withErrors($validator);
        }
        $user = User::findOrFail($id);
        $description_array= array();
        if($user->name!=mb_strtolower($request->name)){
          array_push($description_array,"user's name from ".$user->name." to ".mb_strtolower($request->name));
        }
        if($user->role!=$request->role){
          if($user->role==0){
            array_push($description_array,"user's role from normal user to admin");
          }else{
            array_push($description_array,"user's role from admin to normal user");
          }
          if(auth()->user()->id!=$id){
            $user->role=$request->role;
          }else {
            array_pop($description_array);
          }
        }
        if($user->phone!=$request->phone){
          array_push($description_array,"user's phone from ".$user->phone." to ".mb_strtolower($request->phone));
        }
        $user->name= mb_strtolower($request->name);
        $user->phone=$request->phone;

        $saved=$user->save();
        if(!$saved){
          return redirect()->back()->withInput()->with("error","A server error happened during creating a new user <br /> please try again later");
        }
        if(count($description_array)>0){
          if(auth()->user()->id==$id){
            $description="has changed his own";
          }
          else{
            $description="has changed";
          }
          for ($i=0; $i < count($description_array); $i++) {
            if($i==0){
              $description.=" ".$description_array[$i];
              continue;
            }
            $description.=" and ".$description_array[$i];
          }
          $user_log= new UserLog;
          $user_log->affected_table="users";
          $user_log->affected_row=$id;
          $user_log->process_type="update";
          $user_log->description=$description;
          $user_log->user_id=Auth::user()->id;
          $user_log->save();
        }

        return redirect()->route("showUser",['id'=>$user->id])->with("success","User '$user->uname' edited Successfully");
      }else{
        return view("errors.404");
      }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      if(Auth::user()->role==1||Auth::user()->role==2){
        $user = User::findOrFail($id);
        if ($id==Auth::user()->id) {
          return redirect()->back()->with("error","You can't delete yourself , Ask the Admin to do this for you");
        }
        if($user->role==2 && $user->id!=Auth::user()->id){
          return view('errors.404');
        }
        $logs=$user->user_logs;
        try{
          DB::beginTransaction();
          $user->deleted=1;
          foreach ($logs as $l) {
            $l->deleted=1;
            $l->save();
          }
          $user->save();
          DB::commit();
        }catch (\PDOException $e){
          DB::rollBack();
        }
        return redirect()->back()->with("success","User ".$user->uname." is successfully deleted");
      }else{
        return view("errors.404");
      }
    }
}
