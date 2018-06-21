<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Relationships extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //relationship 1 diagnose has many appointments
        Schema::table('appointments',function(Blueprint $table){
            $table->foreign('diagnose_id')->references('id')->on('diagnoses')->onDelete("cascade");
        });
        //relationship 1 patient has many diagnoses
        Schema::table('diagnoses',function(Blueprint $table){
            $table->foreign('patient_id')->references('id')->on('patients')->onDelete("cascade");
        });
        //relationship 1 diagnose has many oral_radiologies
        Schema::table('oral_radiologies',function(Blueprint $table){
            $table->foreign('diagnose_id')->references('id')->on('diagnoses')->onDelete("cascade");
        });
        //relationship 1 diagnose has many drugs
        Schema::table('drugs',function(Blueprint $table){
            $table->foreign('diagnose_id')->references('id')->on('diagnoses')->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
