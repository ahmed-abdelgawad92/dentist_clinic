<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OralRadiology extends Model
{
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


    /***
     * Query scopes
     */

    // not deleted 
    public function scopeNotDeleted($q)
    {   
        return $q->where('oral_radiologies.deleted',0);
    }
    // scope a query that get only the deleted records
    public function scopeIsDeleted($query)
    {
        return $query->where('oral_radiologies.deleted', 1)->orderBy('updated_at','DESC');
    }
}
