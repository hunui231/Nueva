<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LogisticaProduccion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('entrega_materiales', function (Blueprint $table) {
            $table->id();
            $table->string('mes', 50);
            $table->float('desempeno');
            $table->float('area_cumplimiento');
            $table->timestamps();
        });

        Schema::create('informacion_inventarios', function (Blueprint $table) {
            $table->id();
            $table->string('mes', 50);
            $table->float('desempeno');
            $table->float('area_cumplimiento');
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

        Schema::dropIfExists('entrega_materiales');
        Schema::dropIfExists('informacion_inventarios');

    }
}
