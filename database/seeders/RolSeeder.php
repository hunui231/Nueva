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
        $taller = Role::firstOrCreate(['name' => 'taller']);
        $Administracion = Role::firstOrCreate(['name' => 'Administracion']);
        $Ventas = Role::firstOrCreate(['name' => 'Ventas']);
        $CoordinacionProyectos = Role::firstOrCreate(['name' => 'CoordinacionProyectos']);
        $Mantenimiento = Role::firstOrCreate(['name' => 'Mantenimiento']);
        $Produccion = Role::firstOrCreate(['name' => 'Produccion']);
        $RH = Role::firstOrCreate(['name' => 'RH']);
        $Gerencia = Role::firstOrCreate(['name' => 'Gerencia']);
        $AdministracionGIC = Role::firstOrCreate(['name' => 'AdministracionGIC']);




        // Crear permisos y asignarlos a los roles
        Permission::firstOrCreate(['name' => 'dashboard'])->syncRoles([$admin, $manager, $taller, $developer, $logistica, $cnc, $calidad, $Administracion, $Ventas, $CoordinacionProyectos, $Produccion, $Mantenimiento, $Gerencia, $RH, $AdministracionGIC]);
        Permission::firstOrCreate(['name' => 'users.index'])->syncRoles([$admin, $manager]);
        Permission::firstOrCreate(['name' => 'users.show'])->syncRoles([$admin, $manager]);
        Permission::firstOrCreate(['name' => 'users.create'])->syncRoles([$admin]);
        Permission::firstOrCreate(['name' => 'users.edit'])->syncRoles([$admin]);
        Permission::firstOrCreate(['name' => 'users.destroy'])->syncRoles([$admin]);


        //  Logística
        Permission::firstOrCreate(['name' => 'logistica.index'])->syncRoles([$admin, $logistica,  $manager, $Gerencia]);
        Permission::firstOrCreate(['name' => 'logistica.update'])->syncRoles([$admin]);//permiso de actualizar graficos

         // Permiso para Calidad
         Permission::firstOrCreate(['name' => 'calidad.index'])->syncRoles([$admin, $calidad,  $manager]);
         Permission::firstOrCreate(['name' => 'calidad.update'])->syncRoles([$admin]); // Permiso para actualizar gráficos
         
      // permiso cnc
      Permission::firstOrCreate(['name' => 'cnc.index'])->syncRoles([$admin, $cnc, $manager]);
      Permission::firstOrCreate(['name' => 'cnc.update'])->syncRoles([$admin]);
      
            // Permiso Taller
      Permission::firstOrCreate(['name' => 'taller.index'])->syncRoles([$admin, $taller, $manager, $Gerencia]);

      //Permiso Administracion
      Permission::firstOrCreate(['name' => 'administracion.index'])->syncRoles([$admin, $Administracion, $manager, $RH]);
    

      Permission::firstOrCreate(['name' => 'Ventas.index'])->syncRoles([$admin, $Ventas, $manager]);

      Permission::firstOrCreate(['name' => 'produccion.index'])->syncRoles([$admin, $Produccion, $manager]);

      Permission::firstOrCreate(['name' => 'rh.index'])->syncRoles([$admin, $RH, $manager, $Gerencia, $Administracion]);

      Permission::firstOrCreate(['name' => 'administraciongic.index'])->syncRoles([$admin, $AdministracionGIC, $manager, $Gerencia]);


    }
}


