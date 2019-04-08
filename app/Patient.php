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
    //all teeth belongs to this Patient
    public function teeth()
    {
        return $this->hasManyThrough('App\Tooth','App\Diagnose');
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
    //retrieve all case photos that belongs to specific patient
    public function cases_photos()
    {
        return $this->hasManyThrough('App\CasesPhoto','App\Diagnose');
    }
    //all drugs
    public function diagnose_drug()
    {
        return $this->hasManyThrough('App\DiagnoseDrug','App\Diagnose');
    }


    /***
     * 
     * Query Scopes
     * 
     */
    //search for patient
    public function scopeSearch($q, $search)
    {
        $query->where("pname","like","%".$search."%")->orWhere("dob",$search)->orWhere("id",$search);
    }
    public function scopeNotDeleted($q)
    {
        return $q->where('patients.deleted',0);
    }
    // scope a query that get only the deleted records
    public function scopeIsDeleted($query)
    {
        return $query->where('patients.deleted', 1)->orderBy('updated_at','DESC');
    }
    //scope a query that gets a specific patient with the id
    public function scopeId($q, $id)
    {
        return $q->where('id',$id);
    }
}
