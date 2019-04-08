<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDiagnoseDrugTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('diagnose_drug', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('diagnose_id');
            $table->unsignedInteger('drug_id');
            $table->string('dose');
            $table->tinyInteger('deleted',1)->default(0);
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
        Schema::dropIfExists('diagnose_drug');
    }
}
