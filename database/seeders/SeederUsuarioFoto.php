<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SeederUsuarioFoto extends Seeder
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
            'profile_photo_path' => 'profiles/prueba.png',
            'id_rol' => 3],
        ]);
    }
}
