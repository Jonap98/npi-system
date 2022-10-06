<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMovimientosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('sqlsrv')->create('NPI_movimientos', function (Blueprint $table) {
            $table->increments('id');   // Folio
            $table->string('proyecto');
            $table->integer('cantidad');
            $table->string('tipo');
            $table->string('comentario');
            $table->datetime('fecha_registro');
            $table->integer('id_parte')->unsigned();
            $table->foreign('id_parte')->references('id')->on('NPI_partes');
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
        Schema::dropIfExists('movimientos');
    }
}
