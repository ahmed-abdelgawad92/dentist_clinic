<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CasesPhoto extends Model
{
    //Query scopes
    public function scopeNotDeleted($q)
    {
        return $q->where('cases_photos.deleted',0);
    }
    // scope a query that get only the deleted records
    public function scopeIsDeleted($query)
    {
        return $query->where('cases_photos.deleted', 1)->orderBy('updated_at','DESC');
    }
}
