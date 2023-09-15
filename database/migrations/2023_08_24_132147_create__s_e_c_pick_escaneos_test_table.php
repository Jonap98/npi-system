<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSECPickEscaneosTestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('SEC_pick_escaneos_test', function (Blueprint $table) {
            $table->id();
            $table->string('seq');
            $table->string('model');
            $table->datetime('fecha_escaneo');
            $table->string('scanner');
            $table->timestamps();
            $table->string('num_serie')->nullable();
            $table->integer('folio');
            $table->string('air_tower', 5)->nullable();
            $table->string('pre_foam_doors_juno', 5)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('_s_e_c_pick_escaneos_test');
    }
}
