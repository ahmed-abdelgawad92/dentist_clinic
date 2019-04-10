<?php
namespace App\Repositories;

use App\UserLog;
use Auth;
class UserLogRepository
{  
    public function create($data)
    {
        $log = new UserLog;
        $log->affected_table = $data['table'];
        $log->affected_row = $data['id'];
        $log->process_type = $data['action'];
        $log->description = $data['description'];
        $log->user_id = Auth::user()->id;
        $log->save();
    }

    //get log of a specific table 
    public function getTableLogs($table, $limit = 30)
    {
        return UserLog::affectedTable($table)->paginate($limit);
    }
    //get log of a all tables
    public function getAllLogs($limit = 30)
    {
        return UserLog::orderBy("created_at","DESC")->paginate($limit);
    }
}