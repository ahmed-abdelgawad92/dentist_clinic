<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeethTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teeth', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('diagnose_id');
            $table->string('teeth_name');
            $table->string('diagnose_type');
            $table->double('price');
            $table->string('description',1000)->nullable();
            $table->string('color',7);
            $table->tinyInteger('deleted', 1)->default(0);
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
        Schema::dropIfExists('teeth');
    }
}
