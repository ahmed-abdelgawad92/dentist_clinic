<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WorkingTime extends Model
{
  public function getDayName()
  {
    switch ($this->day) {
      case 1:
        return "Saturday";
        break;
      case 2:
        return "Sunday";
        break;
      case 3:
        return "Monday";
        break;
      case 4:
        return "Tuesday";
        break;
      case 5:
        return "Wendesday";
        break;
      case 6:
        return "Thursday";
        break;
      case 7:
        return "Friday";
        break;

      default:
        return false;
        break;
    }
  }
}
