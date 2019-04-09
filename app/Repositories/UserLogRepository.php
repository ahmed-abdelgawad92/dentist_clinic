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
}