<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::firstOrCreate(
            ['email' => 'admin@admin.com'], // Condición para evitar duplicados
            [
                'first_name' => 'admin',
                'last_name' => 'admin',
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
                'phone_number' => '633401092',
                'remember_token' => Str::random(10),
            ]
        )->assignRole('admin'); // Asignar rol al admin existente o recién creado
    
        User::firstOrCreate(
            ['email' => 'logistica@conplasa.com.mx'], 
            [
                'first_name' => 'Diana Cristina',
                'last_name' => 'Hernandez Arellano',
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
                'phone_number' => '449789012',
                'remember_token' => Str::random(10),
            ]
        )->assignRole('logistica');
        
        User::firstOrCreate(
            ['email' => 'ingenieria@conplasa.com.mx'], 
            [
                'first_name' => 'Vanessa Itzel',
                'last_name' => 'Del Valle Alvarado',
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
                'phone_number' => '55 20678889',
                'remember_token' => Str::random(10),
            ]
        )->assignRole('cnc');
        
        User::firstOrCreate(
            ['email' => 'coordinacion.calidad@conplasa.com.mx'], 
            [
                'first_name' => 'Jose Dolores',
                'last_name' => 'Medel Soledad',
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
                'phone_number' => '55 2060908',
                'remember_token' => Str::random(10),
            ]
        )->assignRole('calidad');
        
        User::firstOrCreate(
            ['email' => 'd.solis@conplasa.com.mx'], 
            [
                'first_name' => 'Ricardo Daniel',
                'last_name' => 'Solis Quiroz',
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
                'phone_number' => '55 2060908',
                'remember_token' => Str::random(10),
            ]
        )->assignRole('manager');
        
        User::firstOrCreate(
            ['email' => 'logistica@conplasa.com.mx'], 
            [
                'first_name' => 'Diana Cristina',
                'last_name' => 'Hernandez Arellano',
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
                'phone_number' => '449789012',
                'remember_token' => Str::random(10),
            ]
        )->assignRole('logistica');
        
        User::firstOrCreate(
            ['email' => 'ingenieria@conplasa.com.mx'], 
            [
                'first_name' => 'Vanessa Itzel',
                'last_name' => 'Del Valle Alvarado',
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
                'phone_number' => '55 20678889',
                'remember_token' => Str::random(10),
            ]
        )->assignRole('cnc');
        
        User::firstOrCreate(
            ['email' => 'coordinacion.calidad@conplasa.com.mx'], 
            [
                'first_name' => 'Jose Dolores',
                'last_name' => 'Medel Soledad',
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
                'phone_number' => '55 2060908',
                'remember_token' => Str::random(10),
            ]
        )->assignRole('calidad');
        
        User::firstOrCreate(
            ['email' => 'd.solis@conplasa.com.mx'], 
            [
                'first_name' => 'Ricardo Daniel',
                'last_name' => 'Solis Quiroz',
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
                'phone_number' => '55 2060908',
                'remember_token' => Str::random(10),
            ]
        )->assignRole('manager');
        
        User::firstOrCreate(
            ['email' => 'coordinadorsistemas@conplasa.com.mx'], 
            [
                'first_name' => 'Hector Manuel',
                'last_name' => 'Castañeda Solis',
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
                'phone_number' => '55 2060908',
                'remember_token' => Str::random(10),
            ]
        )->assignRole('admin');

        User::firstOrCreate(
            ['email' => 'e.ortiz@conplasa.com.mx'], 
            [
                'first_name' => ' Erika',
                'last_name' => 'Ortiz Padilla',
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
                'phone_number' => '4494432',
                'remember_token' => Str::random(10),
            ]
        )->assignRole('Administracion');
        User::firstOrCreate(
            ['email' => 'm.hernandez@conplasa.com.mx'], 
            [
                'first_name' => 'Miguel',
                'last_name' => 'Hernandez',
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
                'phone_number' => '55 2060908',
                'remember_token' => Str::random(10),
            ]
        )->assignRole('Ventas');

         User::firstOrCreate(
            ['email' => 'a.zarate@conplasa.com.mx'], 
            [
                'first_name' => 'Alejandro',
                'last_name' => ' Garcia Zarate',
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
                'phone_number' => '55 2060908',
                'remember_token' => Str::random(10),
            ]
        )->assignRole('CoordinacionProyectos');

         User::firstOrCreate(
            ['email' => 'r.luna@conplasa.com.mx'], 
            [
                'first_name' => 'Reina Isela',
                'last_name' => 'Luna Soto',
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
                'phone_number' => '55 2060908',
                'remember_token' => Str::random(10),
            ]
        )->assignRole('Calidad');

        User::firstOrCreate(
            ['email' => 'mantenimiento@conplasa.com.mx'], 
            [
                'first_name' => ' Edgar Ricardo',
                'last_name' => 'Gonzalez Cortes',
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
                'phone_number' => '55 2060908',
                'remember_token' => Str::random(10),
            ]
        )->assignRole('Mantenimiento');
        User::firstOrCreate(
            ['email' => 'g.ortiz@conplasa.com.mx'], 
            [
                'first_name' => 'Gerardo',
                'last_name' => 'Ortiz',
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
                'phone_number' => '55 2060908',
                'remember_token' => Str::random(10),
            ]
        )->assignRole('Produccion');
        User::firstOrCreate(
            ['email' => 'c.gomez@conplasa.com.mx'], 
            [
                'first_name' => 'Cecy',
                'last_name' => 'Gomez',
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
                'phone_number' => '55 2060908',
                'remember_token' => Str::random(10),
            ]
        )->assignRole('Administracion');
         
        User::firstOrCreate(
            ['email' => 'rh@conplasa.com.mx'], 
            [
                'first_name' => 'Lucero',
                'last_name' => 'Ortiz',
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
                'phone_number' => '55 2060908',
                'remember_token' => Str::random(10),
            ]
        )->assignRole('RH');

        User::firstOrCreate(
            ['email' => 'v.renteria@conplasa.com.mx'], 
            [
                'first_name' => 'Victor Daniel',
                'last_name' => 'Renteria',
                'email_verified_at' => now(),
                'password' => bcrypt('VRENTERIA2'),
                'phone_number' => '55 2060908',
                'remember_token' => Str::random(10),
            ]
        )->assignRole('Gerencia');

        User::firstOrCreate(
            ['email' => 'sistemas@conplasa.com.mx'], 
            [
                'first_name' => 'Leonel',
                'last_name' => 'De Luna', 
                'email_verified_at' => now(),
                'password' => bcrypt('conplasa2025.'), 
                'phone_number' => '55 2060908',
                'remember_token' => Str::random(10),
            ]
        )->assignRole('admin'); 
        
        User::firstOrCreate(
            ['email' => 'a.sistemas2@conplasa.com.mx'], 
            [
                'first_name' => 'Daniela',
                'last_name' => 'Macias Rocha', 
                'email_verified_at' => now(),
                'password' => bcrypt('dany2210'),       
               'phone_number' => '55 2060908', 
               'remember_token' => Str::random(10),
            ]
        )->assignRole('admin'); 
    }
}
