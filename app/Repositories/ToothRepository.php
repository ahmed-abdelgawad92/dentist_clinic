<?php
namespace App\Repositories;

use App\Tooth;

class ToothRepository
{  
    //get tooth by id
    public function get($id)
    {
        return Tooth::findOrFail($id);
    }
    //store many teeth at once
    public function storeMany($teeth, $id)
    {
        $teeth_names = $teeth['teeth_names'];
        $teeth_colors = $teeth['teeth_colors'];
        $diagnose_types = $teeth['diagnose_types'];
        $descriptions = $teeth['descriptions'];
        $prices = $teeth['prices'];

        for($i=0; $i< count($teeth_names);$i++) {
            $tooth = new Tooth;
            $tooth->teeth_name=$teeth_names[$i];
            $tooth->color=$teeth_colors[$i];
            $tooth->diagnose_type=$diagnose_types[$i];
            $tooth->description=$descriptions[$i];
            $tooth->price=$prices[$i];
            $tooth->diagnose_id=$id;
            $tooth->save();
        }
    }

    //update many 
    public function updateMany($data)
    {
        $teeth_ids=$data['teeth_id'];
        $teeth_colors=$data['teeth_color'];
        $diagnose_types=$data['diagnose_types'];
        $descriptions=$data['description'];
        $prices=$data['price'];
        $checkAll=0;
        for($i=0; $i< count($teeth_ids);$i++) {
          $tooth = Tooth::findOrFail($teeth_ids[$i]);
          $desc="User made some changes to the tooth ".$tooth->teeth_name.",";
          $check=0;
          if(strtolower($tooth->color)!=strtolower($teeth_colors[$i])){
            $check=1;
            $desc.="has changed diagnosis type from ".$tooth->diagnose_type." to ".$diagnose_types[$i].", ";
            $tooth->color=$teeth_colors[$i];
            $tooth->diagnose_type=$diagnose_types[$i];
          }
          if(strtolower($tooth->description)!=strtolower($descriptions[$i])){
            $check=1;
            $desc.="has changed diagnosis description oh the tooth from ".$tooth->description." to ".$descriptions[$i].", ";
            $tooth->description=$descriptions[$i];
          }
          if(strtolower($tooth->price)!=strtolower($prices[$i])){
            $check=1;
            $desc.="has changed tooth price from ".$tooth->price." to ".$prices[$i].'.';
            $tooth->price=$prices[$i];
          }
          if($check==1){
            $checkAll=1;
            $tooth->save();
            $log=new UserLog;
            $log->affected_table="diagnoses";
            $log->affected_row=$diagnose->id;
            $log->process_type="update";
            $log->description=$desc;
            $log->user_id=Auth::user()->id;
            $log->save();
          }
        }
        return $checkAll;
    }


    //delete 
    public function delete($id)
    {
        $tooth = Tooth::findOrFail($id);
        $tooth->deleted=1;
        $saved=$tooth->save();
        if (!$saved) {
          return redirect()->back()->with('error','A server error happened during deleting tooth '.ucwords($tooth->teeth_name).' from Diagnosis');
        }
        return $tooth;
    }
}