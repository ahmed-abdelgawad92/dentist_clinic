<?php
namespace App\Repositories;

use App\WorkingTime;

class WorkingTimeRepository
{  
    //get working time by id
    public function get($id)
    {
        return WorkingTime::findOrFail($id);
    }
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
        return WorkingTime::onDay($day)->where(function($q) use($from, $to){
          $q->whereTime('time_from','<=',$from)
            ->whereTime('time_to','>=',$from)->orWhere(function($query) use($from, $to){
              $query->whereTime('time_from','<=',$to)
              ->whereTime('time_to',">=",$to)->orWhere(function($q)use($from, $to){
                $q->whereTime('time_from','>=',$from)
                ->whereTime('time_to','<=',$to);
              });
            });
        })->get();
    }

    //check if any other work time exists but itself
    public function existsButItself($id, $data)
    {
        return WorkingTime::where('id','!=',$id)->onDay($data['day'])->where(function($q) use($data){
          $q->whereTime('time_from','<=',$data['time_from'])
            ->whereTime('time_to','>=',$data['time_from'])->orWhere(function($query) use($data){
              $query->whereTime('time_from','<=',$data['time_to'])
              ->whereTime('time_to',">=",$data['time_to']);
            });
        })->get();
    }

    //create work time 
    public function create($data)
    {
        $time= new WorkingTime;
        $time->day=$data['day'];
        $time->time_from=$data['time_from'];
        $time->time_to=$data['time_to'];
        $saved=$time->save();
        if(!$saved){
          return redirect()->back()->with("error","A server error happened during saving working time");
        }
        return $time;
    }

    //update work time
    public function update($id, $data)
    {
        $time = WorkingTime::findOrFail($id);
        $time->day=$data['day'];
        $time->time_from=$data['time_from'];
        $time->time_to=$data['time_to'];
        $saved=$time->save();
        if(!$saved){
          return redirect()->back()->with("error","A server error happened during saving working time");
        }
        return $time;
    }

    //delete work time 
    public function delete($id)
    {
        $time = WorkingTime::findOrFail($id);
        $time->deleted=1;
        $saved=$time->save();
        if(!$saved){
          return redirect()->back()->with('error','a server error happened during deleting working time');
        }
        return $time;
    }

    //get all deleted records 
    public function allDeleted()
    {
      return WorkingTime::withoutGlobalScopes()->isDeleted()->get();
    }

    //recover a deleted record 
    public function recover($id)
    {
      $working_time = WorkingTime::findOrFail($id);
      $working_time->deleted=0;
      $saved = $working_time->save();
      if(!$saved){
        return redirect()->back()->with('error',"A server error happened during recovering a working time<br>Please try again later");
      }
      return $working_time;
    }

    //permanently deleting record
    public function permanentDelete($id)
    {
        $working_time= WorkingTime::findOrFail($id);
        $deleted=$working_time->delete();
        if(!$deleted){
          return redirect()->back()->with('error','A server error happended during deleting a working time<br> Please try again later');
        }
        return $working_time;
    }
}