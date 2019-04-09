<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\NotDeletedScope;

class Drug extends Model
{
    //Apply the NotDeleteScope on this Model
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new NotDeletedScope);
    }
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

    /**
     * Query scopes
     */

     //search by name
     public function scopeByName($query, $drug)
     {
       return $query->where('name','like','%'.$drug.'%');
     }
     // scope a query that get only the deleted records
    public function scopeIsDeleted($query)
    {
        return $query->where('drugs.deleted', 1)->orderBy('updated_at','DESC');
    }
}
