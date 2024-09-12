<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Crear roles solo si no existen
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $manager = Role::firstOrCreate(['name' => 'manager']);
        $developer = Role::firstOrCreate(['name' => 'developer']);
        $logistica = Role::firstOrCreate(['name' => 'logistica']);
        $calidad = Role::firstOrCreate(['name' => 'calidad']);
        $cnc = Role::firstOrCreate(['name' => 'cnc']);


        // Crear permisos y asignarlos a los roles
        Permission::firstOrCreate(['name' => 'dashboard'])->syncRoles([$admin, $manager, $developer]);
        Permission::firstOrCreate(['name' => 'users.index'])->syncRoles([$admin, $manager]);
        Permission::firstOrCreate(['name' => 'users.show'])->syncRoles([$admin, $manager]);
        Permission::firstOrCreate(['name' => 'users.create'])->syncRoles([$admin]);
        Permission::firstOrCreate(['name' => 'users.edit'])->syncRoles([$admin]);
        Permission::firstOrCreate(['name' => 'users.destroy'])->syncRoles([$admin]);


        //  Logística
        Permission::firstOrCreate(['name' => 'logistica.index'])->syncRoles([$admin, $logistica]);
   
         // Permiso para Calidad
         Permission::firstOrCreate(['name' => 'calidad.index'])->syncRoles([$admin, $calidad]);
      // permiso cnc
      Permission::firstOrCreate(['name' => 'cnc.index'])->syncRoles([$admin, $cnc]);

    }
}

