<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DiagnoseDrug extends Model
{
    //
    protected $table = 'diagnose_drug';

    public function diagnose()
    {
      return $this->belongsTo("App\Diagnose");
    }
    public function drug()
    {
      return $this->belongsTo("App\Drug");
    }

    /***
     * Query scopes
     */
    //get only records that has the same updated_at value
    public function scopeSameDate($query, $updated_at)
    {
        return $query->whereDate('appointments.updated_at',date('Y-m-d',strtotime($updated_at)));
    }
}
