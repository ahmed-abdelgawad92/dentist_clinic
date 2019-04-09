<?php
namespace App\Repositories;

use App\Drug;

class DrugRepository
{  
    //get by id 
    public function get($id)
    {
        return Drug::findOrFail($id);
    }
    //get all drugs in the system
    public function all($paginate = null)
    {
        return $paginate ? Drug::paginate($paginate) : Drug::all();
    }

    //search by name
    public function getByName($name)
    {
        return Drug::byName($request->search_drug)->get();
    }

    // create Drug 
    public function create($name)
    {
        $drug= new Drug;
        $drug->name=$name;
        $drug->save();
        return $drug;
    }

    //update drug
    public function update($id, $name)
    {
        $drug->name= $name;
        $saved = $drug->save();
        if(!$saved){
          return redirect()->back()->with('error',"A server error happened during editing the medicine's name");
        }
    }

    //delete drug 
    public function delete($id)
    {
        $drug = Drug::findOrFail($id);
        $drug->deleted=1;
        $deleted=$drug->save();
        if(!$deleted){
            return redirect()->back()->with('error',"A Server error happened during deleting a medicine <br> Please try again later");
        }
        return $drug;
    }
}