<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMovimientosTest extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('sqlsrv')->create('NPI_movimientos_test', function (Blueprint $table) {
            $table->id();
            $table->string('proyecto');
            $table->integer('cantidad');
            $table->string('tipo');
            $table->string('comentario');
            $table->datetime('fecha_registro');
            $table->integer('id_parte');
            $table->string('ubicacion');
            $table->string('palet');
            $table->string('numero_de_parte');
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
        Schema::dropIfExists('movimientos_test');
    }
}
