<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Drug extends Model
{
    //retrieve which diagnose belongs to
    public function diagnose()
    {
      return $this->belongsToMany('App\Diagnose')->withPivot('dose', 'deleted')->withTimestamps();
    }
    //retrieve which diagnose belongs to
    public function diagnose_drug()
    {
      return $this->hasMany('App\DiagnoseDrug');
    }
    //get the patient#
    public function patient()
    {
        return $this->diagnose->patient;
    }
}
