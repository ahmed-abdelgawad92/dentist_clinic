<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Auth;
use Validator;

use App\Http\Requests\StoreDrug;
use App\Http\Requests\EditDrug;

use App\Repositories\UserLogRepository;
use App\Repositories\DiagnoseRepository;
use App\Repositories\DrugRepository;
class DrugController extends Controller
{

    protected $userlog;
    protected $drug;
    protected $diagnose;

    public function __construct(
      UserLogRepository $userlog,
      DrugRepository $drug,
      DiagnoseRepository $diagnose
    )
    {
        $this->userlog = $userlog;
        $this->drug = $drug;
        $this->diagnose = $diagnose;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $drugs = $this->drug->all(30);
        $data =[
          'drugs'=>$drugs
        ];
        return view("drug.all",$data);
    }
    /**
     * Search for a drug
     *
     * @return \Illuminate\Http\Response
     */
    public function searchDrug(Request $request)
    {
      if(empty($request->search_drug)){
        return json_encode(['state'=>"NOK",'error'=>"Please enter a medicine name to search for it","code"=>422]);
      }
      $drugs = $this->drug->getByName($request->search_drug);
      if($drugs->count()>0){
        return json_encode(['state'=>'OK','drugs'=>$drugs,"code"=>422]);
      }
      return json_encode(['state'=>"NOK",'error'=>'There is no medicine called "'.htmlspecialchars($request->search_drug).'"',"code"=>422]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("drug.systemAdd");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDrug $request)
    {
        if(count($request->drug)>0){
          $empty=true;
          foreach ($request->drug as $value) {
            if(!empty($value)){
              $empty=false;
            }
          }
          if($empty){
            return redirect()->back()->with('error',"You can't create an empty medicine<br> Please Enter Medicines' names before you click on create");
          }
          try{
            $saved=true;
            DB::beginTransaction();
            foreach ($request->drug as $d) {
              if(!empty($d)){
                $drug = $this->drug->create($d);
                $log['table']="drugs";
                $log['id']=$drug->id;
                $log['action']="create";
                $log['description']= "has created a new medicine in the system";
                $this->userlog->create($log);
              }
            }
            DB::commit();
          }catch(\PDOException $e){
            DB::rollBack();
            $saved=false;
          }
          if(!$saved){
            return redirect()->back()->with('error','A server error happened during creating medicines <br> Please try again later');
          }
          return redirect()->route("showAllSystemDrugs")->with("success","The Medicines are successfully created");
        }
        return redirect()->back()->with('error','you entered no medication to store !');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $drug = $this->drug->get($id);
        $drugs = $this->drug->all();
        $data=['drug'=>$drug];
        return view('drug.systemEdit',$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EditDrug $request, $id)
    {
      $drug= $this->drug->get($id);
      if($drug->name!=$request->drug){
        $old_name=$drug->name;
        $this->drug->update($request->drug);
        $log['table']="drugs";
        $log['id']=$drug->id;
        $log['action']="update";
        $log['description']="has changed medicine's name from ".$old_name." to ".$request->drug;
        $this->userlog->create($log);
        return redirect()->back()->with('success','Medicine '.htmlspecialchars($drug->name).' is successfully edited');
      }
      return redirect()->back()->with('success','There is no change to edit');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      $drug = $this->drug->delete($id);
      $log['affected_table']="drugs";
      $log['affected_row']=$id;
      $log['process_type']="delete";
      $log['description']="has deleted a medicine called ".$drug->name." from database ";
      $this->userlog->create($log);
      return redirect()->back()->with('success',"The Medicine is successfully deleted");
    }
}
