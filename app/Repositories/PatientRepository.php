<?php
namespace App\Repositories;

use App\Patient;

class PatientRepository
{  
    //get patient by id
    public function get($id)
    {
        return Patient::findOrFail($id);
    }

    //get all undone diagnosis with their teeth
    public function allUndoneWithTeeth($id)
    {   
        $patient = Patient::findOrFail($id);
        return $patient->diagnoses()->notDone()->with('teeth')->paginate(15);
    }
}