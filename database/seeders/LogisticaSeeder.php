<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Logistica;

class LogisticaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Logistica::create([
            'fecha' => '2024-08-29',
            'dato' => 95,
            'meta' => 90,
            'calif' => 'aprobada',
        ]);
    }
}
