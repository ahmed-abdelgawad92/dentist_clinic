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
        $this->authorize('isAdmin');
        $users = $this->user->paginate(20);
        $data = [
          'users'=>$users
        ];
        return view("user.all", $data);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $this->authorize('isAdmin');
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
    }
    /**
     * Check available uname.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkUname(CheckAvailableUname $request)
    {
        $this->authorize('isAdmin');
        return json_encode(["state"=>"OK"]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('isAdmin');
        return view("user.add");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUser $request)
    {
        $this->authorize('isAdmin');
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
        $this->authorize('isAllowed', $this->user->get($id));
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
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      $user = $this->user->get($id);
      $this->authorize('isAllowed', $user);
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
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getAllUserLogs($id, $table)
    {
      $this->authorize('isAdmin');
      $user = User::findOrFail($id);
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
    }

    /**
     * Show all logs of a user without specifying a table
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function allUserLogs($id)
    {
      $this->authorize('isAdmin');
      $user = $this->user->get($id);
      $logs = $this->user->allLogs($id);

      $data=[
        'logs'=>$logs,
        'table'=>'All Data',
        'user'=>$user
      ];
      return view("user.allUserLog",$data);
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
      $this->authorize('isAdmin');
      $user= $this->user->get($id);
      $data=[
        "user"=>$user
      ];
      return view("user.edit",$data);
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
        $this->authorize('isAdmin');
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
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      $this->authorize('isAdmin');
      $user = $this->user->delete($id);
      return redirect()->back()->with("success","User ".$user->uname." is successfully deleted");
    }
}
