<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\NotDeletedScope;

class OralRadiology extends Model
{
    //Apply the NotDeleteScope on this Model
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new NotDeletedScope);
    }
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

    // scope a query that get only the deleted records
    public function scopeIsDeleted($query)
    {
        return $query->where('oral_radiologies.deleted', 1)->orderBy('updated_at','DESC');
    }
}
