<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    //retrieve all diagnoses that belongs to specific patient
    public function diagnoses()
    {
        return $this->hasMany('App\Diagnose');
    }
    //all oral_radiologies belongs to this Patient
    public function oral_radiologies()
    {
        return $this->hasManyThrough('App\OralRadiology','App\Diagnose');
    }
    //all appointments for this Patient #
    public function appointments()
    {
        return $this->hasManyThrough('App\Appointment','App\Diagnose');
    }

    //all drugs
    public function diagnose_drug()
    {
        return $this->hasManyThrough('App\DiagnoseDrug','App\Diagnose');
    }

}
