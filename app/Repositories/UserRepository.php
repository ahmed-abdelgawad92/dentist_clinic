<?php
namespace App\Repositories;

use App\User;

class UserRepository
{  
    




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