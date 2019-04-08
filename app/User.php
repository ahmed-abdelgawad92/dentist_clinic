<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uname', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    //retrieve all diagnoses that belongs to specific patient
    public function user_logs()
    {
        return $this->hasMany('App\UserLog');
    }


    /***
     * 
     * Query Scopes
     */
    // scope a query that get only the deleted records
    public function scopeNotDeleted($query)
    {
        return $query->where('users.deleted', 0);
    }
    // scope a query that get only the deleted records
    public function scopeIsDeleted($query)
    {
        return $query->where('users.deleted', 1)->orderBy('updated_at','DESC');
    }
    //search users 
    public function scopeSearch($query, $search){
        return $query->where('name', "like" ,"%".mb_strtolower($search)."%")
                     ->orWhere("uname", "like", "%".mb_strtolower($search)."%")
                     ->orWhere("phone", "like", "%".mb_strtolower($search)."%");
    }
}
