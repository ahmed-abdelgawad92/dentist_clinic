<?php
namespace App\Repositories;

use App\AppointmentStates;

class AppointmentStateRepository
{  
    public function update($date)
    {
        $stateVisit = AppointmentStates::find(1);
        if ($stateVisit->value>=10000000) {
            $stateVisit->value=0;
        } else {
            $stateVisit->value+=1;
        }
        $stateVisit->date=$date;
        $stateVisit->save();
    }
    public function get()
    {
        return AppointmentStates::find(1);
    }
}