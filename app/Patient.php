<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\NotDeletedScope;

class Patient extends Model
{
    //Apply the NotDeleteScope on this Model
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new NotDeletedScope);
    }
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
    public function scopeSearch($query, $search)
    {
        $query->where("pname","like","%".$search."%")->orWhere("dob",$search)->orWhere("id",$search);
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
