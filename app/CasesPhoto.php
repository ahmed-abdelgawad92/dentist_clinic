<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\NotDeletedScope;

class CasesPhoto extends Model
{
    //Apply the NotDeleteScope on this Model
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new NotDeletedScope);
    }
    // scope a query that get only the deleted records
    public function scopeIsDeleted($query)
    {
        return $query->where('cases_photos.deleted', 1)->orderBy('updated_at','DESC');
    }
}
