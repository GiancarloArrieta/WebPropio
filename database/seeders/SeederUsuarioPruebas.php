<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SeederUsuarioPruebas extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('usuarios')->insert([
            ['name' => 'Giancarlo',
            'email' => 'giancarlo@dulcesricos.com',
            'password' => Hash::make('prueba123'),
            'id_rol' => 3],
            ['name' => 'Ventas',
            'email' => 'ventas@dulcesricos.com',
            'password' => Hash::make('ventas123'),
            'id_rol' => 2],
            ['name' => 'ADMIN',
            'email' => 'admin@dulcesricos.com',
            'password' => Hash::make('admin123'),
            'id_rol' => 1],
        ]);
    }
}
