<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRackRequerimientosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('sqlsrv')->create('RACK_requerimientos', function (Blueprint $table) {
            $table->id();
            $table->string('requerimientoGuid');
            $table->string('folio');
            $table->string('tipo_requerimiento');
            $table->string('parte');
            $table->string('area');
            $table->string('ubicacion_linea');
            $table->string('ruta');
            $table->float('cantidad_solicitada');
            $table->float('cantidad_surtida');
            $table->float('cantidad_recibida');
            $table->string('quien_solicita');
            $table->string('quien_entrega');
            $table->string('quien_recibe');
            $table->string('status');
            $table->timestamps();
            $table->string('ubicacion_almacen');
            $table->string('cantidad_cajas');
            $table->string('descripcion');
            $table->string('en_transito');
            $table->string('folioCreado');
            $table->string('criticoCreado');
            $table->string('enTransitoCreado');
            $table->string('comentarios');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rack_requerimientos');
    }
}
