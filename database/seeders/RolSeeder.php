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
        Permission::firstOrCreate(['name' => 'users.index'])->syncRoles([$admin, $manager, $Gerencia, $calidad]);
        Permission::firstOrCreate(['name' => 'users.show'])->syncRoles([$admin, $manager]);
        Permission::firstOrCreate(['name' => 'users.create'])->syncRoles([$admin]);
        Permission::firstOrCreate(['name' => 'users.edit'])->syncRoles([$admin]);
        Permission::firstOrCreate(['name' => 'users.destroy'])->syncRoles([$admin]);


        //  Logística
        Permission::firstOrCreate(['name' => 'logistica.index'])->syncRoles([$admin, $logistica,  $manager, $Gerencia, $calidad]);
        Permission::firstOrCreate(['name' => 'logistica.update'])->syncRoles([$admin, $logistica]);
         // Permiso para Calidad
         Permission::firstOrCreate(['name' => 'calidad.index'])->syncRoles([$admin, $calidad,  $manager, $Gerencia, $calidad]);
         Permission::firstOrCreate(['name' => 'calidad.update'])->syncRoles([$admin, $calidad]); // Permiso para actualizar gráficos
         
      
            // Permiso Taller
      Permission::firstOrCreate(['name' => 'taller.index'])->syncRoles([$admin, $taller, $manager, $Gerencia, $calidad]);
      Permission::firstOrCreate(['name' => 'taller.update'])->syncRoles([$taller, $admin]);


      //Permiso Administracion
      Permission::firstOrCreate(['name' => 'administracion.index'])->syncRoles([$admin, $Administracion, $manager]);
      Permission::firstOrCreate(['name' => 'adm.update'])->syncRoles([$Administracion, $AdministracionGIC, $admin]);

      Permission::firstOrCreate(['name' => 'Ventas.index'])->syncRoles([$admin, $Ventas, $manager]);
      Permission::firstOrCreate(['name' => 'ventas.update'])->syncRoles([$Ventas, $admin]);


      Permission::firstOrCreate(['name' => 'produccion.index'])->syncRoles([$admin, $Produccion, $manager, $calidad]);
      Permission::firstOrCreate(['name' => 'produccion.update'])->syncRoles([$Produccion, $admin]);


      Permission::firstOrCreate(['name' => 'rh.index'])->syncRoles([$admin, $RH, $manager]);
      Permission::firstOrCreate(['name' => 'rh.update'])->syncRoles([$RH, $admin]);


      Permission::firstOrCreate(['name' => 'administraciongic.index'])->syncRoles([$admin, $AdministracionGIC, $manager, $Gerencia, $calidad]);
      Permission::firstOrCreate(['name' => 'admgic.update'])->syncRoles([$Administracion, $AdministracionGIC, $admin]);

      Permission::firstOrCreate(['name' => 'mtto.index'])->syncRoles([$admin, $Mantenimiento, $manager, $calidad, $Gerencia]);
      
      Permission::firstOrCreate(['name' => 'admin.update'])->syncRoles([ $admin]);

    }
}


