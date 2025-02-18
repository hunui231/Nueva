<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScrapProduccionTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scrap_donaldson', function (Blueprint $table) {
            $table->id();
            $table->string('mes', 50);
            $table->float('desempeno');
            $table->float('area_cumplimiento');
            $table->timestamps();
        });

        Schema::create('scrap_taller', function (Blueprint $table) {
            $table->id();
            $table->string('mes', 50);
            $table->float('desempeno');
            $table->float('area_cumplimiento');
            $table->timestamps();
        });

        Schema::create('scrap_forjas', function (Blueprint $table) {
            $table->id();
            $table->string('mes', 50);
            $table->float('desempeno');
            $table->float('area_cumplimiento');
            $table->timestamps();
        });

        Schema::create('produccion_taller', function (Blueprint $table) {
            $table->id();
            $table->string('mes', 50);
            $table->float('desempeno');
            $table->float('area_cumplimiento');
            $table->timestamps();
        });

        Schema::create('produccion_forjas', function (Blueprint $table) {
            $table->id();
            $table->string('mes', 50);
            $table->float('desempeno');
            $table->float('area_cumplimiento');
            $table->timestamps();
        });
        
        Schema::create('evaluacion_proveedores_gic', function (Blueprint $table) {
            $table->id();
            $table->string('mes', 50);
            $table->float('desempeno');
            $table->float('area_cumplimiento');
            $table->timestamps();
        });
        
        Schema::create('cumplimiento_compras', function (Blueprint $table) {
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
        Schema::dropIfExists('scrap_donaldson');
        Schema::dropIfExists('scrap_taller');
        Schema::dropIfExists('scrap_forjas');
        Schema::dropIfExists('produccion_taller');
        Schema::dropIfExists('produccion_forjas');
        Schema::dropIfExists('evaluacion_proveedores_gic');
        Schema::dropIfExists('cumplimiento_compras');
    }
}
