<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\NotDeletedScope;

class Appointment extends Model
{
    //Apply the NotDeleteScope on this Model
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new NotDeletedScope);
    }
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

    /**
     * Query Scopes
     * 
     */
    
    // scope a query that get only the deleted records
    public function scopeIsDeleted($query)
    {
        return $query->where('appointments.deleted', 1)->orderBy('updated_at','DESC');
    }
    // scope a query that get only appointments on a specific date
    public function scopeOnDate($query, $date)
    {
        return $query->where('date',$date);
    }
    // scope query that order appointments
    public function scopeOrder($query)
    {
        return $query->orderBy('approved','DESC')->orderBy('approved_time','ASC')->orderBy('time','ASC');
    }

    //scope a query that get approved appointments
    public function scopeApproved($query)
    {
        return $query->where('approved',3)->orderBy('approved_time','DESC');
    }
    //scope a query that get not approved appointments
    public function scopeNotApproved($query)
    {
        return $query->where('approved',2)->orderBy('time','DESC');
    }
    //scope a query that get finished appointments
    public function scopeFinished($query)
    {
        return $query->where('approved',1)->orderBy('approved_time','ASC')->orderBy('time','ASC');
    }

    //get only records that has the same updated_at value
    public function scopeSameDate($query, $updated_at)
    {
        return $query->whereDate('appointments.updated_at',date('Y-m-d',strtotime($updated_at)));
    }
}
