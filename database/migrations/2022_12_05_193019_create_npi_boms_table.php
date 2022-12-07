<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNpiBomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('sqlsrv')->create('NPI_boms', function (Blueprint $table) {
            $table->id();
            $table->string('kit_nombre');
            $table->string('num_parte');
            $table->string('kit_descripcion');
            $table->string('um');
            $table->float('cantidad');
            $table->string('nivel');
            $table->string('status');
            $table->boolean('requerido');
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
        Schema::dropIfExists('npi_boms');
    }
}
