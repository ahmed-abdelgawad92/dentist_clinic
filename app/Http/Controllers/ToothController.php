<?php

namespace App\Http\Controllers;

use Auth;
use App\Tooth;
use App\UserLog;
use Illuminate\Http\Request;
use App\Repositories\UserLogRepository;
use App\Repositories\ToothRepository;

class ToothController extends Controller
{

    protected $userlog;
    protected $tooth;

    public function __construct(
      UserLogRepository $userlog,
      ToothRepository $tooth
    )
    {
        $this->userlog = $userlog;
        $this->tooth = $tooth;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Tooth  $tooth
     * @return \Illuminate\Http\Response
     */
    public function show(Tooth $tooth)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Tooth  $tooth
     * @return \Illuminate\Http\Response
     */
    public function edit(Tooth $tooth)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Tooth  $tooth
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tooth $tooth)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Tooth  $tooth
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tooth = $this->tooth->delete($id);
        $log['id']= $tooth->diagnose_id;
        $log['table']="diagnoses";
        $log['action']="delete";
        $log['description']='has deleted a tooth from this diagnosis details of this tooth "Name" '.$tooth->teeth_name.' "Diagnosis Type" '.$tooth->diagnose_type;
        $log['description'].=' "Price" '.$tooth->price.' "Description" '.$tooth->description;
        $this->userlog->create($log);
        return redirect()->back()->with('success','The tooth successfully deleted');
    }
}
