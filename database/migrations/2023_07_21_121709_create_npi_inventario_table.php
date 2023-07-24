<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNpiInventarioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('NPI_inventario', function (Blueprint $table) {
            $table->id();
            $table->string('numero_de_parte');
            $table->float('cantidad');
            $table->string('ubicacion');
            $table->string('palet');
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
        Schema::dropIfExists('npi_inventario');
    }
}
