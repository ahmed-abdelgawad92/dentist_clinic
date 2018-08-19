<?php

namespace App\Http\Controllers;

use Auth;
use App\UserLog;
use Illuminate\Http\Request;

class UserLogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::user()->role==1||Auth::user()->role==2){
          $logs = UserLog::where("deleted",0)->orderBy("created_at","DESC")->paginate(30);
          return view("user_log.all",['logs'=>$logs]);
        }else {
          return view("errors.404");
        }
    }
    /**
     * Display a listing of the resource. for specific table
     *
     * @return \Illuminate\Http\Response
     */
    public function indexTable($table)
    {
      if(Auth::user()->role==1||Auth::user()->role==2){
        switch ($table) {
          case 'users':
            $logs = UserLog::where("deleted",0)->where("affected_table",$table)->orderBy("created_at","DESC")->paginate(30);
            break;
          case 'patients':
            $logs = UserLog::where("deleted",0)->where("affected_table",$table)->orderBy("created_at","DESC")->paginate(30);
            break;
          case 'diagnoses':
            $logs = UserLog::where("deleted",0)->where("affected_table",$table)->orderBy("created_at","DESC")->paginate(30);
            $table="diagnosis";
            break;
          case 'drugs':
            $logs = UserLog::where("deleted",0)->where("affected_table",$table)->orderBy("created_at","DESC")->paginate(30);
            $table="medication";
            break;
          case 'oral_radiologies':
            $logs = UserLog::where("deleted",0)->where("affected_table",$table)->orderBy("created_at","DESC")->paginate(30);
            $table="x-rays";
            break;
          case 'appointments':
            $logs = UserLog::where("deleted",0)->where("affected_table",$table)->orderBy("created_at","DESC")->paginate(30);
            $table="visits";
            break;
          case 'working_times':
            $logs = UserLog::where("deleted",0)->where("affected_table",$table)->orderBy("created_at","DESC")->paginate(30);
            $table="Working Times";
            break;

          default:
            return view("errors.404");
            break;
        }

        return view("user_log.all",['logs'=>$logs,'table'=>$table]);
      }else {
        return view("errors.404");
      }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\UserLog  $userLog
     * @return \Illuminate\Http\Response
     */
    public function show(UserLog $userLog)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\UserLog  $userLog
     * @return \Illuminate\Http\Response
     */
    public function edit(UserLog $userLog)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\UserLog  $userLog
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserLog $userLog)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\UserLog  $userLog
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserLog $userLog)
    {
        //
    }
}
