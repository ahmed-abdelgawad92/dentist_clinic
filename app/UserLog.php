<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Patient;
use App\Diagnose;
use App\Appointment;
use App\OralRadiology;
use App\WorkingTime;
use App\Drug;

class UserLog extends Model
{
    public function user()
    {
       return $this->belongsTo('App\User');
    }
    public function userName()
    {
      $user = User::findOrFail($this->affected_row);
      return $user->uname;
    }
    public function patient()
    {
      $patient = Patient::findOrFail($this->affected_row);
      return $patient->pname;
    }
    public function diagnose()
    {
      $diagnose = Diagnose::findOrFail($this->affected_row);
      return $diagnose->id;
    }
    public function appointment()
    {
      $appointment = Appointment::findOrFail($this->affected_row);
      return $appointment->id;
    }
    public function xray()
    {
      $xray = OralRadiology::findOrFail($this->affected_row);
      return $xray->photo;
    }
    public function drug()
    {
      $drug = Drug::findOrFail($this->affected_row);
      return $drug->name;
    }
    public function working_time()
    {
      $working_time = WorkingTime::findOrFail($this->affected_row);
      return $working_time->getDayName()." ".date('h:i a',strtotime($working_time->time_from))." to ".date('h:i a',strtotime($working_time->time_to));
    }
}
