<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use Validator;

use App\Repositories\UserLogRepository;
use App\Repositories\UserRepository;

use App\Http\Requests\StoreUser;
use App\Http\Requests\EditUser;
use App\Http\Requests\UploadPhoto;
use App\Http\Requests\ChangePassword;
use App\Http\Requests\CheckAvailableUname;

class UserController extends Controller
{
    protected $user;
    protected $userlog;

    public function __construct(
      UserRepository $user,
      UserLogRepository $userlog
    )
    {
        $this->user = $user;
        $this->userlog = $userlog;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::user()->role==1||Auth::user()->role==2){
          $users = $this->user->paginate(20);
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
          $users = $this->user->search($request->search_user);
          $data = [
            'state'=>"OK",
            'users'=>$users,
            'search_user'=>$request->search_user
          ];
          if($users->count()>0){
            return json_encode($data);
          }
          return json_encode(["state"=>"NOK","error"=>'"'.htmlspecialchars($request->search_user).'" is not found',"code"=>422]);
        }else {
          return view("errors.404");
        }
    }
    /**
     * Check available uname.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkUname(CheckAvailableUname $request)
    {
        if (Auth::user()->role==1||Auth::user()->role==2) {
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
    public function store(StoreUser $request)
    {
      if(Auth::user()->role==1||Auth::user()->role==2){
        $user = $this->user->create($request);
        $log['table']="users";
        $log['id']= $user->id;
        $log['action']= "create";
        $log['description'] = "has created a new User called ".$user->uname;
        $this->userlog->create($log);
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
     public function uploadProfilePhoto(UploadPhoto $request,$id)
     {
       if(Auth::user()->role==1 ||Auth::user()->role==2 || $id == Auth::user()->id){
         $user = $this->user->changePhoto($id, $request);
         $log['table']="users";
         $log['id']=$user->id;
         $log['action']="update";
         if($id==Auth::user()->id){
           $log['description']="has changed his own profile picture";
         }else {
           $log['description']="has changed the profile picture of ".$user->uname;
         }
         $this->userlog->create($log);
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
        $user_logs = $this->user->getLogs($id, "users");
        $patient_logs =  $this->user->getLogs($id, "patients");
        $diagnose_logs =  $this->user->getLogs($id, "diagnoses");
        $drug_logs =  $this->user->getLogs($id, "drugs");
        $visit_logs =  $this->user->getLogs($id, "appointments");
        $xray_logs =  $this->user->getLogs($id, "oral_radiologies");
        $working_times_logs =  $this->user->getLogs($id, "working_times");
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
            $logs =  $this->user->getLogsPaginate($id, "users");
            break;
          case 'patients':
            $logs = $this->user->getLogsPaginate($id, "patients");
            break;
          case 'diagnoses':
            $table="Diagnosis";
            $logs = $this->user->getLogsPaginate($id, "diagnoses");
            break;
          case 'drugs':
            $table="Medications";
            $logs = $this->user->getLogsPaginate($id, "drugs");
            break;
          case 'oral_radiologies':
            $table="X-rays";
            $logs = $this->user->getLogsPaginate($id, "oral_radiologies");
            break;
          case 'appointments':
            $table="Visits";
            $logs = $this->user->getLogsPaginate($id, "appointments");
            break;
          case 'working_times':
            $table="Working Times";
            $logs = $this->user->getLogsPaginate($id, "working_times");
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
        $user = $this->user->get($id);
        if($user->role==2 && $user->id!=Auth::user()->id){
          return view('errors.404');
        }
        $logs = $this->user->allLogs($id);

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
     public function updatePassword(ChangePassword $request)
     {
        $user = $this->user->get(Auth::user()->id);
        if (Hash::check($request->get('old_password'), Auth::user()->password)) {
          $this->user->changePassword($user->id, $request->new_password);
          $log['table']="users";
          $log['id']=Auth::user()->id;
          $log['action']="update";
          $log['description']="has changed his own password";
          $this->userlog->create($log);
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
        $user= $this->user->get($id);
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
    public function update(EditUser $request, $id)
    {
      if(Auth::user()->role==1||Auth::user()->role==2){
        $user = $this->user->get($id);
        $description_array= array();
        if($user->name!=mb_strtolower($request->name)){
          array_push($description_array,"user's name from ".$user->name." to ".mb_strtolower($request->name));
        }
        $role=$request->role;
        if($user->role!=$request->role){
          if($user->role==0){
            array_push($description_array,"user's role from normal user to admin");
          }else{
            array_push($description_array,"user's role from admin to normal user");
          }
          if(auth()->user()->id==$id){
            array_pop($description_array);
            $role=null;
          }
        }
        if($user->phone!=$request->phone){
          array_push($description_array,"user's phone from ".$user->phone." to ".mb_strtolower($request->phone));
        }
        $this->user->update($id, $request, $role);
        if(count($description_array)>0){
          $description= (auth()->user()->id==$id) ? "has changed his own " : "has changed ";
          $description .= implode(" and ", $description_array);
          $log['table']="users";
          $log['id']=$id;
          $log['action']="update";
          $log['description']=$description;
          $this->userlog->create($log);
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
        $user = $this->user->delete($id);
        return redirect()->back()->with("success","User ".$user->uname." is successfully deleted");
      }else{
        return view("errors.404");
      }
    }
}
