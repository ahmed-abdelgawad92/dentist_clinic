<?php

namespace App\Http\Controllers;

use Auth;
use Validator;
use App\UserLog;
use App\WorkingTime;
use Illuminate\Http\Request;

class WorkingTimeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $working_times=WorkingTime::notDeleted()->orderBy('day',"asc")->orderBy('time_from',"asc")->get();
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
      $check_inDB= WorkingTime::notDeleted()->onDay($request->day)->where(function($q) use($request){
          $q->whereTime('time_from','<=',$request->time_from)
            ->whereTime('time_to','>=',$request->time_from)->orWhere(function($query) use($request){
              $query->whereTime('time_from','<=',$request->time_to)
              ->whereTime('time_to',">=",$request->time_to)->orWhere(function($q)use($request){
                $q->whereTime('time_from','>=',$request->time_from)
                ->whereTime('time_to','<=',$request->time_to);
              });
            });
        })->get();
      if ($check_inDB->count()>0) {
        $msg='This working time already exists';
        foreach ($check_inDB as $timeDB) {
          $msg.="<br>".$timeDB->getDayName()." from ".date("h:i a",strtotime($timeDB->time_from))." to ".date("h:i a",strtotime($timeDB->time_to));
        }
        $msg.="<br> it would be better when you edit one of these working time";
        return redirect()->back()->withInput()->with('error',$msg);
      }
      $time= new WorkingTime;
      $time->day=$request->day;
      $time->time_from=$request->time_from;
      $time->time_to=$request->time_to;
      $saved=$time->save();
      if(!$saved){
        return redirect()->back()->with("error","A server error happened during saving working time");
      }
      $log = new UserLog;
      $log->affected_table="working_times";
      $log->affected_row=$time->id;
      $log->process_type="create";
      $log->description="has created a new working time";
      $log->user_id=Auth::user()->id;
      $log->save();
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
        $time = WorkingTime::findOrFail($id);
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
      $time=WorkingTime::findOrFail($id);
      //check if it's already in the database
      $check_inDB= WorkingTime::notDeleted()->where('id','!=',$id)->onDay($request->day)->where(function($q) use($request){
          $q->whereTime('time_from','<=',$request->time_from)
            ->whereTime('time_to','>=',$request->time_from)->orWhere(function($query) use($request){
              $query->whereTime('time_from','<=',$request->time_to)
              ->whereTime('time_to',">=",$request->time_to);
            });
        })->get();
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
      $time->day=$request->day;
      $time->time_from=$request->time_from;
      $time->time_to=$request->time_to;
      $saved=$time->save();
      if(!$saved){
        return redirect()->back()->with("error","A server error happened during saving working time");
      }
      $description.=" to ".$time->getDayName()." ".date("h:i a",strtotime($time->time_from))." till ".date("h:i a",strtotime($time->time_to));
      $log = new UserLog;
      $log->affected_table="working_times";
      $log->affected_row=$id;
      $log->process_type="update";
      $log->description=$description;
      $log->user_id=Auth::user()->id;
      $log->save();
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
        $time = WorkingTime::findOrFail($id);
        $time->deleted=1;
        $saved=$time->save();
        if(!$saved){
          return redirect()->back()->with('error','a server error happened during deleting working time');
        }
        $log = new UserLog;
        $log->affected_table="working_times";
        $log->affected_row=$id;
        $log->process_type="delete";
        $log->description="has deleted the working time ".$time->toString();
        $log->user_id=Auth::user()->id;
        $log->save();
      }else{
        return view('errors.404');
      }
    }

}
