<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WorkingTime extends Model
{

  public function toString()
  {
    return $this->getDayName()." ".date("h:i a",strtotime($this->time_from))." till ".date("h:i a",strtotime($this->time_to));
  }
  public function getDayName()
  {
    switch ($this->day) {
      case 6:
        return "Saturday";
        break;
      case 7:
        return "Sunday";
        break;
      case 1:
        return "Monday";
        break;
      case 2:
        return "Tuesday";
        break;
      case 3:
        return "Wendesday";
        break;
      case 4:
        return "Thursday";
        break;
      case 5:
        return "Friday";
        break;

      default:
        return false;
        break;
    }
  }


  /**
   * Query Scopes
   * 
   */
  //scope working times in a specific day
  public function scopeOnDay($query, $day)
  {
    return $query->where('day', $day);
  }
  //not deleted models
  public function scopeNotDeleted($query)
  {
    return $query->where('working_times.deleted',0);
  } 
  // scope a query that get only the deleted records
  public function scopeIsDeleted($query)
  {
      return $query->where('working_times.deleted', 1)->orderBy('updated_at','DESC');
  }
}
