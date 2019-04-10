<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Repositories\UserLogRepository;

class UserLogController extends Controller
{
    protected $userlog;

    public function __construct(UserLogRepository $userlog)
    {
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
          $logs = $this->userlog->getAllLogs();
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
        $logs = $this->userlog->getTableLogs($table);
        switch ($table) {
          case 'users':
            break;
          case 'patients':
            break;
          case 'diagnoses':
            $table="diagnosis";
            break;
          case 'drugs':
            $table="medication";
            break;
          case 'oral_radiologies':
            $table="x-rays";
            break;
          case 'appointments':
            $table="visits";
            break;
          case 'working_times':
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
