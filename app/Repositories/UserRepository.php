<?php
namespace App\Repositories;

use Auth;
use App\User;
use Illuminate\Support\Facades\Storage;

class UserRepository
{  

    //get by id
    public function get($id)
    {
        return User::findOrFail($id);
    }
    //paginate all 
    public function paginate($limit)
    {
        return User::orderBy("name","ASC")->paginate(20);
    }

    //search users 
    public function search($search)
    {
        return User::search($search)->orderBy("name","ASC")->get();
    }

    //create user 
    public function create($request)
    {
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
        return $user;
    }

    //update user 
    public function update($id, $request, $role)
    {
        $user = User::findOrFail($id);
        $user->name= mb_strtolower($request->name);
        $user->phone=$request->phone;
        $user->role = $role!=null ? $role : $user->role;
        $saved=$user->save();
        if(!$saved){
          return redirect()->back()->withInput()->with("error","A server error happened during creating a new user <br /> please try again later");
        }
    }

    //delete user 
    public function delete($id)
    {
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
    }
    //change profile photo 
    public function changePhoto($id)
    {
        $user = User::findOrFail($id);
        if ($user->photo != null) {
            Storage::delete($user->photo);
        }
        $user->photo = $request->photo->store("patient_profile");
        $saved=$user->save();
        if (!$saved) {
            return redirect()->back()->withInput()->with("error","A server error happened during uploading a patient profile picture <br /> please try again later");
        }
    }
    //get all logs on users table
    public function getLogs($id, $table, $limit = 5)
    {
        return User::findOrFail($id)->user_logs()->affectedTable($table)->take($limit)->get();
    }
    //get all logs on users table
    public function getLogsPaginate($id, $table, $limit = 30)
    {
        return User::findOrFail($id)->user_logs()->affectedTable($table)->paginate($limit);
    }

    //all user logs 
    public function allLogs($id, $limit = 30)
    {
        return User::findOrFail($id)->user_logs()->orderBy("created_at","DESC")->paginate($limit);
    }
    //change password 
    public function changePassword($id, $password)
    {
        $user = User::findOrFail($id);
        $user->password = bcrypt($request->new_password);
        $saved = $user->save();
        if(!$saved){
        return redirect()->back()->with("error","A server error happened during changing password, please try again later");
        }
    }
    //get all deleted records 
    public function allDeleted()
    {
        return User::withoutGlobalScopes()->isDeleted()->get();
    }
    //recover deleted user
    public function recover($id)
    {
        $user = User::findOrFail($id);
        $logs=$user->user_logs;
        try{
            DB::beginTransaction();
            $user->deleted=0;
            foreach ($logs as $l) {
            $l->deleted=0;
            $l->save();
            }
            $user->save();
            DB::commit();
        }catch (\PDOException $e){
            DB::rollBack();
            return redirect()->back()->with('error',"A server error happened during recovering a user<br>Please try again later");
        }
        return $user;
    }

    //permanently deleting record
    public function permanentDelete($id)
    {
        $user = User::findOrFail($id);
        $logs=$user->user_logs;
        try{
            DB::beginTransaction();
            foreach ($logs as $l) {
                $l->delete();
            }
            $user->delete();
            DB::commit();
        }catch (\PDOException $e){
            DB::rollBack();
            return redirect()->back()->with('error',"A server error happened during deleteing a user<br>Please try again later");
        }
        return $user;
    }
}