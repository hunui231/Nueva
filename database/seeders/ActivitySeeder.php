<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    \App\Models\Activity::create(['description' => 'Usuario Admin inicio Sesion.']);
    \App\Models\Activity::create(['description' => 'Usuario Daniela Macias inició sesión.']);
    \App\Models\Activity::create(['description' => 'Usuario Diana Hernandez inicio sesion.']);
    \App\Models\Activity::create(['description' => 'Usuario Lonel Jose cerró sesión.']);
    }
}
