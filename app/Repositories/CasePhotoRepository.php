<?php
namespace App\Repositories;

use App\CasesPhoto;
class CasePhotoRepository{

    //create Case photo
    public function create($data)
    {
        $case_photo = new CasesPhoto;
        $case_photo->before_after = $data['before_after'];
        $case_photo->photo = $data['case_photo']->store("case_photo");
        $case_photo->diagnose_id = $data['id'];
        if(Storage::disk('local')->exists($case_photo->photo)){
            $saved = $case_photo->save();
            if (!$saved) {
                return redirect()->back()->with('error',"A server error is happened during uploading case photo<br>Please try again later");
            }
        }else{
            return redirect()->back()->with('error','something wrong happened during uploading the case photo');
        }
        return $case_photo;
    }

    //delete a case Photo
    public function delete($id)
    {
        $case_photo = CasesPhoto::findOrFail($id);
        $case_photo->deleted=1;
        $saved=$case_photo->save();
        if(!$saved){
          return redirect()->back()->with("error","An error happened during deleting patient");
        }
        return $case_photo;
    }
}