<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePatientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->increments('id');
            $table->string('pname');
            $table->enum('gender',[0,1]);
            $table->date('dob');
            $table->text('address');
            $table->string('phone');
            $table->enum('diabetes',[0,1]);
            $table->enum('blood_pressure',['low','normal','high']);
            $table->longText('medical_compromise');
            $table->string('photo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('patients');
    }
}
