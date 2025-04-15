<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKpiTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cobranza_ci', function (Blueprint $table) {
            $table->id();
            $table->string('semana', 50);
            $table->float('en_tiempo');
            $table->float('rango1');
            $table->float('rango2');
            $table->float('rango3');
            $table->float('rango4');
            $table->string('year', 4)->default('2025');
            $table->timestamps();

        });

        // Tabla para Cobranza GIC
        Schema::create('cobranza_gic', function (Blueprint $table) {
            $table->id();
            $table->string('semana', 50);
            $table->float('en_tiempo');
            $table->float('rango1');
            $table->float('rango2');
            $table->float('rango3');
            $table->float('rango4');
            $table->string('year', 4)->default('2025');
            $table->timestamps();
        });

        // Tabla para Evaluación de Desempeño de Proveedores CI
        Schema::create('evaluacion_proveedores_ci', function (Blueprint $table) {
            $table->id();
            $table->string('mes', 50);
            $table->float('desempeno');
            $table->float('area_cumplimiento');
            $table->timestamps();
        });

        // Tabla para Cumplimiento de Compras a Tiempo IC
        Schema::create('cumplimiento_compras_ic', function (Blueprint $table) {
            $table->id();
            $table->string('mes', 50);
            $table->float('desempeno');
            $table->float('area_cumplimiento');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cumplimiento_compras_ic');
        Schema::dropIfExists('evaluacion_proveedores_ci');
        Schema::dropIfExists('cobranza_gic');
        Schema::dropIfExists('cobranza_ci');
    }
};
