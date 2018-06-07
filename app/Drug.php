<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Drug extends Model
{
    //retrieve which diagnose belongs to
    public function diagnose()
    {
      return $this->belongsTo('App\Drug');
    }
    //get the patient#
    public function patient()
    {
        return $this->diagnose->patient;
    }
}
