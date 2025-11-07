<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SeederUsuariosPrueba extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('usuarios')->insert([
            ['name' => 'Ventas',
            'email' => 'ventas@dulcesricos.com',
            'password' => Hash::make('ventas123'),
            'departamento' => 'Ventas',
            'puesto' => 'Soporte Técnico',
            'id_rol' => 2],
            ['name' => 'ADMIN',
            'email' => 'admin@dulcesricos.com',
            'password' => Hash::make('admin123'),
            'departamento' => 'Administración',
            'puesto' => 'Jefe',
            'id_rol' => 1],
            ['name' => 'Giancarlo',
            'email' => 'giancarlo@dulcesricos.com',
            'departamento' => 'Ventas',
            'puesto' => 'Empleado',
            'password' => Hash::make('prueba123'),
            'id_rol' => 3],
        ]);
    }
}
