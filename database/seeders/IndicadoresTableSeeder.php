<?php

namespace Database\Seeders;

use App\Models\Indicador;
use Illuminate\Database\Seeder;

class IndicadoresTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $indicadores = [
            [
                'seccion' => 'Administración',
                'total' => 5,
                'cumplen' => 4,
                'no_cumplen' => 1,
                'porcentaje' => 80,
            ],
            [
                'seccion' => 'Ventas',
                'total' => 6,
                'cumplen' => 1,
                'no_cumplen' => 5,
                'porcentaje' => 17,
            ],
            [
                'seccion' => 'Producción',
                'total' => 4,
                'cumplen' => 3,
                'no_cumplen' => 1,
                'porcentaje' => 75,
            ],
            [
                'seccion' => 'RRHH',
                'total' => 7,
                'cumplen' => 6,
                'no_cumplen' => 1,
                'porcentaje' => 86,
            ],
            [
                'seccion' => 'OperacionMM',
                'total' => 5,
                'cumplen' => 5,
                'no_cumplen' => 0,
                'porcentaje' => 100,
            ],
            [
                'seccion' => 'AdministracionGIC',
                'total' => 2,
                'cumplen' => 2,
                'no_cumplen' => 0,
                'porcentaje' => 100,
            ],
            [
                'seccion' => 'ProduccionLog',
                'total' => 2,
                'cumplen' => 2,
                'no_cumplen' => 1,
                'porcentaje' => 86,
            ],
            [
                'seccion' => 'RRHHGIC',
                'total' => 2,
                'cumplen' => 2,
                'no_cumplen' => 0,
                'porcentaje' => 100,
            ],
        ];

        foreach ($indicadores as $indicador) {
            Indicador::create($indicador);
        }
    }
}
