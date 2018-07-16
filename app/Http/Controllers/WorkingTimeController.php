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
      $working_times=WorkingTime::where('deleted',0)->orderBy('day',"asc")->orderBy('time_from',"asc")->get();
      return view('working_time.all',['working_times'=>$working_times]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      if (Auth::user()->role==1) {
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
    public function store(Request $request)
    {
      $rules=[
        'day'=>"required|in:1,2,3,4,5,6,7",
        "time_from"=>"required|regex:/^[0-9]{2}:[0-9]{2}:[0-9]{2}$/",
        "time_to"=>"required|regex:/^[0-9]{2}:[0-9]{2}:[0-9]{2}$/"
      ];
      $error_messages=[
        "day.in"=>"Please select a day from the list",
        "day.required"=>"Please select a day from the list",
        "time_from.required"=>"Please select when the clinic is opened at this day",
        "time_from.regex"=>"Please select a valid time ",
        "time_to.required"=>"Please select when the clinic is closed at this day",
        "time_to.regex"=>"Please select a valid time ",
      ];
      $validator=Validator::make($request->all(),$rules,$error_messages);
      if ($validator->fails()) {
        return redirect()->back()->withInput()->withErrors($validator);
      }
      //check if it's already in the database
      $check_inDB= WorkingTime::where('deleted',0)->where('day',$request->day)->where(function($q) use($request){
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
    public function edit(WorkingTime $workingTime)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\WorkingTime  $workingTime
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, WorkingTime $workingTime)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\WorkingTime  $workingTime
     * @return \Illuminate\Http\Response
     */
    public function destroy(WorkingTime $workingTime)
    {
        //
    }

}
