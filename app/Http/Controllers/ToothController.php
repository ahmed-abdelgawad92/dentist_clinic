<?php

namespace App\Http\Controllers;

use Auth;
use App\Tooth;
use App\UserLog;
use Illuminate\Http\Request;

class ToothController extends Controller
{
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
        $tooth = Tooth::findOrFail($id);
        $tooth->deleted=1;
        $saved=$tooth->save();
        if (!$saved) {
          return redirect()->back()->with('error','A server error happened during deleting tooth '.ucwords($tooth->teeth_name).' from Diagnosis');
        }
        $log = new UserLog;
        $log->affected_row= $tooth->diagnose_id;
        $log->affected_table="diagnoses";
        $log->process_type="delete";
        $log->description='has deleted a tooth from this diagnosis details of this tooth "Name" '.$tooth->teeth_name.' "Diagnosis Type" '.$tooth->diagnose_type;
        $log->description.=' "Price" '.$tooth->price.' "Description" '.$tooth->description;
        $log->user_id=Auth::user()->id;
        $log->save();
        return redirect()->back()->with('success','The tooth successfully deleted');
    }
}
