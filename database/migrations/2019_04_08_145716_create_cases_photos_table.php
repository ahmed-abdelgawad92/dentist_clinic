<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCasesPhotosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cases_photos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('diagnose_id');
            $table->string('photo');
            $table->tinyInteger('before_after', 1);
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
        Schema::dropIfExists('cases_photos');
    }
}
