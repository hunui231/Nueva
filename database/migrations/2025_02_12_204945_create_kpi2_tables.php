<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKpi2Tables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //clientes nuevos por mes
        Schema::create('clientes_nuevos_mes', function (Blueprint $table) {
            $table->id();
            $table->string('mes', 50);
            $table->float('desempeno');
            $table->float('area_cumplimiento');
            $table->timestamps();
        });

        //porcentaje de ventas
        Schema::create('porcentaje_ventas', function (Blueprint $table) {
            $table->id();
            $table->string('mes', 50);
            $table->float('desempeno');
            $table->float('area_cumplimiento');
            $table->timestamps();
        });

        //produccion scrap ci
        Schema::create('producción_scrap_ci', function (Blueprint $table) {
            $table->id();
            $table->string('mes', 50);
            $table->float('desempeno');
            $table->float('area_cumplimiento');
            $table->timestamps();
        });

        //rendimiento operacional
        Schema::create('rendimiento_Operacional_ci', function (Blueprint $table) {
            $table->id();
            $table->string('mes', 50);
            $table->float('desempeno');
            $table->float('area_cumplimiento');
            $table->timestamps();
        });

        //cumplimiento plan de produccion
        Schema::create('cumplimiento_plan_producción', function (Blueprint $table) {
            $table->id();
            $table->string('mes', 50);
            $table->float('desempeno');
            $table->float('area_cumplimiento');
            $table->timestamps();
        });
        
        Schema::create('rotacion_personal_ci', function (Blueprint $table) {
            $table->id();
            $table->string('mes', 50);
            $table->float('desempeno');
            $table->float('area_cumplimiento');
            $table->timestamps();
        });

        Schema::create('permanencia_personal_reclutado_ci', function (Blueprint $table) {
            $table->id();
            $table->string('mes', 50);
            $table->float('desempeno');
            $table->float('area_cumplimiento');
            $table->timestamps();
        });

        Schema::create('rotacion_personal_gic', function (Blueprint $table) {
            $table->id();
            $table->string('mes', 50);
            $table->float('desempeno');
            $table->float('area_cumplimiento');
            $table->timestamps();
        });

        Schema::create('permanencia_personal_reclutado_gic', function (Blueprint $table) {
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
        Schema::dropIfExists('clientes_nuevos_mes');
        Schema::dropIfExists('porcentaje_ventas');
        Schema::dropIfExists('producción_scrap_ci');
        Schema::dropIfExists('rendimiento_Operacional_ci');
        Schema::dropIfExists('cumplimiento_plan_producción');
        Schema::dropIfExists('rotacion_personal_ci');
        Schema::dropIfExists('permanencia_personal_reclutado_ci');
        Schema::dropIfExists('rotacion_personal_gic');
        Schema::dropIfExists('permanencia_personal_reclutado_gic');

    }
}
