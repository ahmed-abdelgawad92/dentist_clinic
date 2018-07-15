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
}
