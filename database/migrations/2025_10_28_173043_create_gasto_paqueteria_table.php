<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGastoPaqueteriaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('gasto_paqueteria', function (Blueprint $table) {
            $table->id();
            $table->string('mes');
            $table->decimal('desempeno', 5, 2);
            $table->decimal('area_cumplimiento', 5, 2);
            $table->timestamps();
            
            // Índice único para evitar duplicados por mes
            $table->unique(['mes']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gasto_paqueteria');
    }
}
