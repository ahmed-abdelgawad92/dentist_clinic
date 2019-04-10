<?php

namespace App\Http\Controllers;

use Auth;
use Validator;
use Illuminate\Http\Request;
use App\Http\Requests\StoreWorkingTime;

use App\Repositories\UserLogRepository;
use App\Repositories\WorkingTimeRepository;
class WorkingTimeController extends Controller
{

    protected $userlog;
    protected $workTime;

    public function __construct(
      UserLogRepository $userlog,
      WorkingTimeRepository $workTime
    )
    {
        $this->userlog = $userlog;
        $this->workTime = $workTime;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $working_times = $this->workTime->all();
      return view('working_time.all',['working_times'=>$working_times]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      if (Auth::user()->role==1||Auth::user()->role==2) {
        return view('working_time.add');
      }else {
        return view('errors.404');
      }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreWorkingTime $request)
    {
      if(strtotime($request->time_from)>=strtotime($request->time_to)){
        return redirect()->back()->withInput()->with('error',"' Time to ' must be greater than ' Time from '");
      }
      //check if it's already in the database
      $check_inDB = $this->workTime->exists($request->day, $request->time_from, $request->time_to);
      if ($check_inDB->count()>0) {
        $msg='This working time already exists';
        foreach ($check_inDB as $timeDB) {
          $msg.="<br>".$timeDB->getDayName()." from ".date("h:i a",strtotime($timeDB->time_from))." to ".date("h:i a",strtotime($timeDB->time_to));
        }
        $msg.="<br> it would be better when you edit one of these working time";
        return redirect()->back()->withInput()->with('error',$msg);
      }
      $data['day']=$request->day;
      $data['time_from']=$request->time_from;
      $data['time_to']=$request->time_to;
      $time = $this->workTime->create($data);
      
      $log['table']="working_times";
      $log['id']=$time->id;
      $log['action']="create";
      $log['description']="has created a new working time";
      $this->userlog->create($log);
      return redirect()->back()->with("success","Working time is successfully created");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\WorkingTime  $workingTime
     * @return \Illuminate\Http\Response
     */
    public function show(WorkingTime $workingTime)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\WorkingTime  $workingTime
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      if (Auth::user()->role==1) {
        $time = $this->workTime($id);
        return view('working_time.edit',['time'=>$time]);
      }else {
        return view('errors.404');
      }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\WorkingTime  $workingTime
     * @return \Illuminate\Http\Response
     */
    public function update(StoreWorkingTime $request, $id)
    {
      $time = $this->workTime->get($id);
      //check if it's already in the database
      $data = [
        'day' => $request->day,
        'time_from' => $request->time_from,
        'time_to' => $request->time_to
      ];
      $check_inDB= $this->workTime->existsButItself($id, $data);
      if ($check_inDB->count()>0) {
        $msg='This working time already exists';
        foreach ($check_inDB as $timeDB) {
          $msg.="<br>".$timeDB->getDayName()." from ".date("h:i a",strtotime($timeDB->time_from))." to ".date("h:i a",strtotime($timeDB->time_to));
        }
        $msg.="<br> it would be better when you edit one of these working time";
        return redirect()->back()->withInput()->with('error',$msg);
      }
      $description="has changed working time from ".$time->getDayName()." ".date("h:i a",strtotime($time->time_from))." till ".date("h:i a",strtotime($time->time_to));
      if ($time->day==$request->day && $time->time_from ==$request->time_from && $time->time_to ==$request->time_to ) {
        return redirect()->back()->with('warning','You made no change on the working time');
      }
      $time = $this->workTime->update($id, $data);
      $description.=" to ".$time->getDayName()." ".date("h:i a",strtotime($time->time_from))." till ".date("h:i a",strtotime($time->time_to));

      $log['affected_table']="working_times";
      $log['affected_row']=$id;
      $log['process_type']="update";
      $log['description']=$description;
      $this->userlog->create($log);
      return redirect()->back()->with('success','Working time is edited successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\WorkingTime  $workingTime
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      if(Auth::user()->role==1||Auth::user()->role==2){
        $time = $this->workTime->delete($id);
        $log['affected_table']="working_times";
        $log['affected_row']=$id;
        $log['process_type']="delete";
        $log['description']="has deleted the working time ".$time->toString();
        $this->userlog->create($log);
      }else{
        return view('errors.404');
      }
    }

}
