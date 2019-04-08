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

    /**
     * Query scopes
     */

     // not deleted 
     public function scopeNotDeleted($q)
     {
        return $q->where('drugs.deleted',0);
     }
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
