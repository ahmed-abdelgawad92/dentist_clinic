<?php
namespace App\Repositories;

use App\OralRadiology;

class OralRadiologyRepository
{  
    //create xray 
    public function create($data)
    {
        $xray = new OralRadiology;
        $xray->description = $data['description'];
        $xray->photo = $data['photo'];
        $xray->diagnose_id = $data['id'];
        $saved=$xray->save();
        if(!$saved){
          return redirect()->back()->with("error","An error happenend during storing the X-ray <br /> Please try agin later");
        }
    }
    // update xray
    public function update($id, $description)
    {
        $xray = OralRadiology::findOrFail($id);
        $xray->description = $description;
        $saved=$xray->save();
        if(!$saved){
            return redirect()->back()->with("error","An error happenend during editing the X-ray's description <br /> Please try agin later");
        }
    }

    //delete xray 
    public function delete($id)
    {
        $xray = OralRadiology::findOrFail($id);
        $xray->deleted=1;
        $deleted=$xray->save();
        if(!$deleted){
            return redirect()->back()->with('error','An Error happened during deleting this X-ray<br> Please try again later');
        }
    }
    //get xray
    public function get($id)
    {
        return OralRadiology::findOrFail($id);
    }
}