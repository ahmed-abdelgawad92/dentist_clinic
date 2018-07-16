<?php

namespace App\Http\Controllers;

use App\UserLog;
use App\Appointment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($date=today())
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     * $id of Diagnose
     * @return \Illuminate\Http\Response
     */
    public function getAvailableTime(Request $request)
    {
      $rules=['date'=>'required|date'];
      $error_messages=[
        'date.required'=>"Please enter the date of the visit",
        'date.date'=>"Please enter a valid date"
      ];
      $validator= Validator::make($request->all(),$rules,$error_messages);
      if($validator->fails()){
        return json_encode(['state'=>'NOK','error'=>$validator->errors()->getMessages()]);
      }
      $availableTimes = Appointment::where('date', $date)->get();
      
    }
    /**
     * Show the form for creating a new resource.
     * $id of Diagnose
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     * $id of Diagnose
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,$id)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
