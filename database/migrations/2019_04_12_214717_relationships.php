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
        //relationship 1 diagnose has many cases_photos
        Schema::table('cases_photos',function(Blueprint $table){
            $table->foreign('diagnose_id')->references('id')->on('diagnoses')->onDelete("cascade");
        });
        //relationship 1 diagnose has many teeth
        Schema::table('teeth',function(Blueprint $table){
            $table->foreign('diagnose_id')->references('id')->on('diagnoses')->onDelete("cascade");
        });
        //relationship 1 patient has many diagnoses
        Schema::table('diagnoses',function(Blueprint $table){
            $table->foreign('patient_id')->references('id')->on('patients')->onDelete("cascade");
        });
        //relationship 1 user has many logs
        Schema::table('user_logs',function(Blueprint $table){
            $table->foreign('user_id')->references('id')->on('users')->onDelete("cascade");
        });
        //relationship 1 diagnose has many oral_radiologies
        Schema::table('oral_radiologies',function(Blueprint $table){
            $table->foreign('diagnose_id')->references('id')->on('diagnoses')->onDelete("cascade");
        });
        //Bridge between diagnose and drugs M to M
        Schema::table('diagnose_drug',function(Blueprint $table){
            $table->foreign('diagnose_id')->references('id')->on('diagnoses')->onDelete("cascade");
            $table->foreign('drug_id')->references('id')->on('drugs')->onDelete("cascade");
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
