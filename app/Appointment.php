<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    //belongs to diagnose
    /**
     * Get the diagnose that owns the model.
     */
    public function diagnose()
    {
        return $this->belongsTo('App\Diagnose');
    }

    //get the patient#
    public function patient()
    {
        return $this->diagnose->patient;
    }
}
