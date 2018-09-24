<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Diagnose extends Model
{
    //retrieve all teeth that belongs to specific diagnose
    public function teeth()
    {
        return $this->hasMany('App\Tooth');
    }
    //retrieve all drugs that belongs to specific diagnose
    public function drugs()
    {
        return $this->belongsToMany('App\Drug')->withPivot('id','dose', 'deleted')->withTimestamps();
    }
    //retrieve all drugs that belongs to specific diagnose
    public function diagnose_drug()
    {
        return $this->hasMany('App\DiagnoseDrug');
    }
    //retrieve all drugs that belongs to specific diagnose
    public function oral_radiologies()
    {
        return $this->hasMany('App\OralRadiology');
    }
    //retrieve all drugs that belongs to specific diagnose
    public function appointments()
    {
        return $this->hasMany('App\Appointment');
    }
    //retrieve all case photos that belongs to specific diagnose
    public function cases_photos()
    {
        return $this->hasMany('App\CasesPhoto');
    }
    //retrieve the patient who has this diagnose
    public function patient()
    {
        return $this->belongsTo('App\Patient');
    }

}
