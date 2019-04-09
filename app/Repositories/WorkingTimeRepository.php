<?php
namespace App\Repositories;

use App\WorkingTime;

class WorkingTimeRepository
{  
    //get working time on a secific day
    public function onDay($day)
    {
        return WorkingTime::onDay($day)->orderBy('time_from','ASC')->get();
    }

    //all work time
    public function all()
    {
        return WorkingTime::orderBy('day',"asc")->orderBy('time_from',"asc")->get();
    }

    //check if work time already exists in database
    public function exists($day, $from, $to)
    {
        return WorkingTime::onDay($request->day)->where(function($q) use($request){
          $q->whereTime('time_from','<=',$request->time_from)
            ->whereTime('time_to','>=',$request->time_from)->orWhere(function($query) use($request){
              $query->whereTime('time_from','<=',$request->time_to)
              ->whereTime('time_to',">=",$request->time_to)->orWhere(function($q)use($request){
                $q->whereTime('time_from','>=',$request->time_from)
                ->whereTime('time_to','<=',$request->time_to);
              });
            });
        })->get();
    }
}